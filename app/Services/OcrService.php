<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use thiagoalessio\TesseractOCR\TesseractOCR;

/**
 * Service class untuk OCR KTP dan KK.
 * Ported dari standalone ocr 5.x/index.php ke arsitektur Laravel.
 */
class OcrService
{
    /**
     * Entry point utama: proses gambar KTP + KK dan return array field terstruktur.
     *
     * @param string $ktpPath  Absolute path ke gambar KTP
     * @param string $kkPath   Absolute path ke gambar KK
     * @return array ['fields' => [...], 'raw_text' => '...']
     */
    public function processKtpAndKk(string $ktpPath, string $kkPath): array
    {
        $result = [
            'fields' => [],
            'raw_text' => '',
        ];

        // --- 1. PROSES KTP ---
        $preprocessedKtp = tempnam(sys_get_temp_dir(), 'ocr_ktp_') . '.png';

        try {
            if (!$this->preprocessImage($ktpPath, $preprocessedKtp)) {
                Log::error('[OCR] Preprocessing KTP gagal');
                return $result;
            }

            $ocr = new TesseractOCR($preprocessedKtp);
            $ocr->lang('ind+eng')->psm(6)->oem(3);
            $extractedText = $ocr->run();

            if (empty($extractedText) || trim($extractedText) === '') {
                Log::error('[OCR] Tesseract KTP menghasilkan hasil kosong');
                return $result;
            }

            $processedText = $this->postProcessOCRText(trim((string)$extractedText));
            if (!is_string($processedText) || trim($processedText) === '') {
                $processedText = trim((string)$extractedText);
            }

            $result['raw_text'] = $processedText;
            $result['fields'] = $this->extractKtpFields($processedText, $preprocessedKtp);

        } catch (\Exception $e) {
            Log::error('[OCR] Error saat OCR KTP: ' . $e->getMessage());
            return $result;
        } finally {
            if (file_exists($preprocessedKtp)) @unlink($preprocessedKtp);
        }

        // --- 2. PROSES KK ---
        $preprocessedKk = tempnam(sys_get_temp_dir(), 'ocr_kk_') . '.png';

        try {
            if (!$this->preprocessImage($kkPath, $preprocessedKk)) {
                Log::warning('[OCR] Preprocessing KK gagal');
                return $result;
            }

            $ocr = new TesseractOCR($preprocessedKk);
            $ocr->lang('ind+eng')->psm(6)->oem(3);
            $extractedTextKk = $ocr->run();

            if (!empty($extractedTextKk)) {
                $nomorKk = $this->extractNomorKK($extractedTextKk);
                if ($nomorKk) {
                    $result['fields']['nomor_kk'] = $nomorKk;
                } else {
                    Log::warning('[OCR] Gagal menemukan Nomor KK dari teks OCR KK');
                }
            }
        } catch (\Exception $e) {
            Log::error('[OCR] Error saat OCR KK: ' . $e->getMessage());
        } finally {
            if (file_exists($preprocessedKk)) @unlink($preprocessedKk);
        }

        return $result;
    }

    // ============================================================
    // PREPROCESSING
    // ============================================================

    /**
     * Preprocessing gambar: grayscale, contrast, sharpen, resize.
     * Menggunakan Imagick jika tersedia, fallback ke GD.
     */
    public function preprocessImage(string $inputPath, string $outputPath): bool
    {
        if (!file_exists($inputPath)) {
            Log::error('[OCR] Input file not found: ' . $inputPath);
            return false;
        }

        if (extension_loaded('imagick') && class_exists('Imagick')) {
            return $this->preprocessImageImagick($inputPath, $outputPath);
        }

        return $this->preprocessImageGD($inputPath, $outputPath);
    }

    private function preprocessImageImagick(string $inputPath, string $outputPath): bool
    {
        try {
            $image = new \Imagick($inputPath);
            $image->setImageFormat('png');
            $image->transformImageColorspace(\Imagick::COLORSPACE_GRAY);
            $image->enhanceImage();
            $image->enhanceImage();
            $image->enhanceImage();
            $image->normalizeImage();
            $image->contrastImage(2.0);
            $image->brightnessContrastImage(20, 35);
            $image->sharpenImage(1, 1);
            $image->sharpenImage(0.5, 1);
            $image->equalizeImage();

            $dimensions = $image->getImageGeometry();
            if ($dimensions['width'] < 300) {
                $scale = 300 / $dimensions['width'];
                $image->scaleImage(
                    (int)($dimensions['width'] * $scale),
                    (int)($dimensions['height'] * $scale)
                );
            }

            $image->setImageResolution(300, 300);
            $image->writeImage($outputPath);
            $image->destroy();
            return true;
        } catch (\Exception $e) {
            Log::warning('[OCR] Imagick failed, falling back to GD: ' . $e->getMessage());
            return $this->preprocessImageGD($inputPath, $outputPath);
        }
    }

    private function preprocessImageGD(string $inputPath, string $outputPath): bool
    {
        try {
            $imageInfo = getimagesize($inputPath);
            $image = null;

            if (!$imageInfo) {
                $image = imagecreatefromstring(file_get_contents($inputPath));
            } else {
                switch ($imageInfo[2]) {
                    case IMAGETYPE_JPEG: $image = imagecreatefromjpeg($inputPath); break;
                    case IMAGETYPE_PNG:  $image = imagecreatefrompng($inputPath); break;
                    case IMAGETYPE_GIF:  $image = imagecreatefromgif($inputPath); break;
                    default: $image = imagecreatefromstring(file_get_contents($inputPath));
                }
            }

            if (!$image) {
                throw new \Exception('Gagal membuat image resource');
            }

            $width = imagesx($image);
            $height = imagesy($image);

            @imagefilter($image, IMG_FILTER_GRAYSCALE);
            @imagefilter($image, IMG_FILTER_SMOOTH, 1);
            @imagefilter($image, IMG_FILTER_BRIGHTNESS, 15);
            @imagefilter($image, IMG_FILTER_CONTRAST, 35);

            $sharpenMatrix = [[-1,-1,-1],[-1,16,-1],[-1,-1,-1]];
            @imageconvolution($image, $sharpenMatrix, 8, 0);
            @imageconvolution($image, $sharpenMatrix, 8, 0);

            @imagefilter($image, IMG_FILTER_SMOOTH, 1);

            if ($width < 300) {
                $scale = 300 / $width;
                $newW = (int)($width * $scale);
                $newH = (int)($height * $scale);
                $resized = imagecreatetruecolor($newW, $newH);
                imagecopyresampled($resized, $image, 0, 0, 0, 0, $newW, $newH, $width, $height);
                imagedestroy($image);
                $image = $resized;
            }

            $ok = imagepng($image, $outputPath, 9);
            imagedestroy($image);

            if (!$ok || !file_exists($outputPath)) {
                throw new \Exception('Gagal menyimpan PNG');
            }

            return true;
        } catch (\Exception $e) {
            Log::error('[OCR] GD Preprocessing failed: ' . $e->getMessage());
            return false;
        }
    }

    // ============================================================
    // POST-PROCESSING OCR TEXT
    // ============================================================

    public function postProcessOCRText(string $text): string
    {
        $processedText = $text;

        $fixes = [
            '/([a-zA-Z0-9])\s*=\s*([a-zA-Z0-9])/' => '$1 : $2',
            '/\btg[!]?\b/' => 'tgl',
            '/\bTg[!]?\b/' => 'Tgl',
            '/\btg([!?])\s/' => 'tgl ',
            '/\bRT[!|]RW/' => 'RT/RW',
            '/\bsel[!a]tan\b/' => 'selatan',
            '/Kel\s*[.\/]\s*Desa/' => 'Kel/Desa',
            '/Kelurahan\s*[.\/]\s*Desa/' => 'Kelurahan/Desa',
            '/Kel\b/' => 'Kelurahan',
            '/Peker[]j]aan/' => 'Pekerjaan',
            '/Kerja(?!an)/' => 'Pekerjaan',
            '/Berlaku\s+Hing[g]ga/' => 'Berlaku Hingga',
            '/Berlaku\s+s\.?d\.?/' => 'Berlaku Hingga',
            '/Tempat\s+[\/|]\s+Tgl/' => 'Tempat / Tgl',
            '/Tempat\s+Tgl\s+Lahir/' => 'Tempat/Tgl Lahir',
            '/([A-Z0-9])-\s*([A-Z0-9])/' => '$1 - $2',
            '/\s{2,}/' => ' ',
            '/(\d)\s+([.,])/' => '$1$2',
            '/\s+([,.:;])/' => '$1',
        ];

        foreach ($fixes as $pattern => $replacement) {
            $updated = @preg_replace($pattern, $replacement, $processedText);
            if ($updated !== null) $processedText = $updated;
        }

        // Fix NIK context
        $nikFixed = @preg_replace_callback(
            '/NIK\s*[:=\s]+([^|\n\r]*)/',
            function ($m) {
                $n = trim($m[1]);
                $n = preg_replace('/[O]/i', '0', $n);
                $n = preg_replace('/[l]/i', '1', $n);
                $n = preg_replace('/[S]/i', '5', $n);
                $n = preg_replace('/[Z]/i', '2', $n);
                $n = preg_replace('/[B]/i', '8', $n);
                $n = preg_replace('/[I|!]/', '1', $n);
                $n = preg_replace('/[^0-9]/', '', $n);
                return 'NIK : ' . $n;
            },
            $processedText
        );
        if ($nikFixed !== null) $processedText = $nikFixed;

        // Cleanup lines
        $lines = explode("\n", $processedText);
        $clean = [];
        foreach ($lines as $line) {
            $line = trim($line);
            $c = @preg_replace('/\s+[!@#$%^&*()_+=\[\]{};\'",<>?\/\\\\|`~]$/', '', $line);
            if ($c !== null) $line = $c;
            if (!empty($line)) $clean[] = $line;
        }

        $processedText = trim(implode("\n", $clean));
        $f = @preg_replace('/\n{3,}/', "\n\n", $processedText);
        if ($f !== null) $processedText = $f;

        return $processedText;
    }

    // ============================================================
    // EXTRACT KTP FIELDS
    // ============================================================

    public function extractKtpFields(string $text, ?string $imagePath = null): array
    {
        $fields = [
            'nik' => null,
            'nama' => null,
            'nomor_kk' => null,
            'tempat_lahir' => null,
            'tanggal_lahir' => null,
            'jenis_kelamin' => null,
            'gol_darah' => null,
            'alamat' => null,
            'rt_rw' => null,
            'kelurahan' => null,
            'kecamatan' => null,
            'kota_kabupaten' => null,
            'provinsi' => null,
            'agama' => null,
            'status_perkawinan' => null,
            'pekerjaan' => null,
        ];

        $lines = explode("\n", $text);

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            $lineLower = strtolower($line);

            // 1. NIK
            if (stripos($lineLower, 'nik') !== false) {
                $nikCandidate = null;
                $nikPart = '';
                if (preg_match('/NIK\s*[:=]?\s*([0-9A-Za-z?|!\s\.\-]{8,25})/i', $line, $pre)) {
                    $nikPart = trim($pre[1]);
                    if (strpos($nikPart, ':') !== false) {
                        $parts = explode(':', $nikPart);
                        $nikPart = trim(end($parts));
                    }
                }
                if (!empty($nikPart) && preg_match('/([0-9OolISZBb8\.\-]{10,20})/', $nikPart, $m)) {
                    $nikCandidate = $m[1];
                }
                if (empty($nikCandidate)) {
                    if (preg_match('/NIK\s*[:=]?\s*([0-9OolISZB8\.\-]{10,20})/i', $line, $m)) {
                        $nikCandidate = $m[1];
                    } elseif (preg_match('/NIK[:\s]+([0-9OolISZB8\s\.\-]{10,25}?)(?:\s{2,}|[^0-9OolISZB8\s\.\-]|$)/i', $line, $m)) {
                        $nikCandidate = trim($m[1]);
                    } elseif (preg_match('/([0-9OolISZB8dqg]{14,18})/', $line, $m)) {
                        if (preg_match('/\d{12,}/', $m[1])) $nikCandidate = $m[1];
                    }
                }
                if (!empty($nikCandidate)) {
                    $nikCandidate = $this->normalizeNikCandidate($nikCandidate);
                    if ($nikCandidate !== null) $fields['nik'] = $nikCandidate;
                }
            }

            // 2. Nama
            if (preg_match('/Nama\s*[:=]\s*([^|\n\r]+)/i', $line, $m)) {
                $nama = trim($m[1]);
                $nama = preg_replace('/\s*\d+.*$/s', '', $nama);
                $nama = $this->cleanupOCRNoise($nama);
                $nama = $this->cleanupFieldValue($nama, 'nama');
                $nama = preg_replace('/[^A-Za-z\s\.\,\-\']/u', '', $nama);
                $nama = preg_replace('/\s+/', ' ', trim($nama));
                if (!empty($nama) && strlen($nama) > 2) $fields['nama'] = strtoupper($nama);
            }

            // 3. Nomor KK (dari teks KTP — jarang tapi mungkin)
            if (preg_match('/(?:No(?:mor)?\.?\s*)?Kartu\s*Keluarga\s*[:=]\s*(\d{16})/i', $line, $m)) {
                $fields['nomor_kk'] = $m[1];
            } elseif (preg_match('/NK\s*[:=]\s*(\d{16})/i', $line, $m)) {
                $fields['nomor_kk'] = $m[1];
            }

            // 4-5. Tempat & Tanggal Lahir (terpisah)
            $combinedTTL = null;
            if (preg_match('/Tempat\s*\/\s*Tgl\s*(?:Lahir)?\s*[:=]\s*(.+)/i', $line, $m)) {
                $c = trim($m[1]);
                if (!empty($c) && strlen($c) > 3 && empty($fields['tempat_lahir']))
                    $combinedTTL = $this->cleanupTempatTglLahir($c);
            } elseif (preg_match('/Tempat\s+Tgl\s+Lahir\s*[:=]\s*(.+)/i', $line, $m)) {
                $c = trim($m[1]);
                if (!empty($c) && strlen($c) > 3 && empty($fields['tempat_lahir']))
                    $combinedTTL = $this->cleanupTempatTglLahir($c);
            } elseif (preg_match('/TempaTgl\s*[:=]\s*Lahir\s*[:=]\s*(.+)/i', $line, $m)) {
                $c = trim($m[1]);
                if (!empty($c) && strlen($c) > 3 && empty($fields['tempat_lahir']))
                    $combinedTTL = $this->cleanupTempatTglLahir($c);
            } elseif (preg_match('/TempaTgl\s*[:=>\s]\s*(.+)/i', $line, $m)) {
                $c = trim($m[1]);
                if (!empty($c) && strlen($c) > 3 && stripos($c, 'lahir') !== false && empty($fields['tempat_lahir']))
                    $combinedTTL = $this->cleanupTempatTglLahir($c);
            } elseif (preg_match('/Tempat\s+Lahir\s*[:=]\s*([^,|\n\r]+?)(?:\s+(\d{1,2}[-\/]\d{1,2}[-\/]\d{4}))?/i', $line, $m)) {
                $t = trim($m[1]);
                $d = isset($m[2]) ? trim($m[2]) : '';
                if (!empty($t) && strlen($t) > 2 && !empty($d) && empty($fields['tempat_lahir']))
                    $combinedTTL = $t . ' / ' . $d;
            }
            if ($combinedTTL === null && empty($fields['tempat_lahir']) && preg_match('/([A-Z][A-Za-z\s,.-]+?)(?:\s+\/?\s+|,\s+)?(.*)$/i', $line, $m)) {
                if (stripos($lineLower, 'lahir') !== false) {
                    $t = trim($m[1]);
                    $ds = isset($m[2]) ? trim($m[2]) : '';
                    if (!empty($ds)) $combinedTTL = $this->cleanupTempatTglLahir($t . ', ' . $ds);
                }
            }
            if (!empty($combinedTTL) && empty($fields['tempat_lahir'])) {
                if (preg_match('/^(.+?)\s*[\/,]\s*(\d{1,2}\s*[-\/]\s*\d{1,2}\s*[-\/]\s*\d{2,4})/', $combinedTTL, $sp)) {
                    $fields['tempat_lahir'] = trim($sp[1]);
                    $fields['tanggal_lahir'] = preg_replace('/\s*[-\/]\s*/', '-', trim($sp[2]));
                } else {
                    $fields['tempat_lahir'] = $combinedTTL;
                }
            }

            // 6. Jenis Kelamin
            if (preg_match('/Jenis\s*Kelamin\s*[:=!]\s*([^\n\r|]+)/i', $line, $m)) {
                $jk = trim($m[1]);
                if (preg_match('/^(.*?)\s*Gol/i', $jk, $jkCut)) $jk = trim($jkCut[1]);
                if (preg_match('/\b(LAKI\s*-\s*LAKI|LAKI|PEREMPUAN)\b/i', $jk, $jkm)) {
                    $v = strtoupper(preg_replace('/\s+/', ' ', trim($jkm[1])));
                    $fields['jenis_kelamin'] = str_replace('LAKI LAKI', 'LAKI-LAKI', $v);
                }
            }
            if (empty($fields['jenis_kelamin']) && stripos($line, 'kelamin') !== false) {
                if (preg_match('/\b(LAKI\s*-\s*LAKI|PEREMPUAN)\b/i', $line, $jkm)) {
                    $v = strtoupper(preg_replace('/\s+/', ' ', trim($jkm[1])));
                    $fields['jenis_kelamin'] = str_replace('LAKI LAKI', 'LAKI-LAKI', $v);
                }
            }

            // 7. Golongan Darah
            if (preg_match('/(?:Gol|Golongan)\.?\s*Darah\s*[:=]\s*([^\n\r|]+)/i', $line, $m)) {
                $gol = trim($m[1]);
                if (preg_match('/(AB|O|B|A)[\+\-]?/i', $gol, $gm)) {
                    $fields['gol_darah'] = strtoupper($gm[1]);
                }
            }

            // 8. Alamat
            if (preg_match('/Alamat\s*[:=]\s*(.+?)(?=\s*(?:\||RT\s*\/\s*RW|RT\/RW|RT\s*[:=>]|RW\s*[:=>]|Kelurahan|Desa|Kel\.?\s*\/?|Kecamatan|Kec\.?|$))/i', $line, $m)) {
                $al = trim($m[1]);
                if (($pp = strpos($al, '|')) !== false) $al = substr($al, 0, $pp);
                $al = rtrim($al, ' ,;:.-');
                $al = $this->cleanupOCRNoise($al);
                $al = $this->cleanupFieldValue($al, 'alamat');
                if (!empty($al) && strlen($al) > 2) $fields['alamat'] = strtoupper($al);
            }

            // 9. RT/RW
            if (preg_match('/RT\s*\/\s*RW\s*[:=>]\s*(\d+)\s*\/\s*(\d+)/i', $line, $m)) {
                if (empty($fields['rt_rw'])) $fields['rt_rw'] = str_pad($m[1],3,'0',STR_PAD_LEFT) . '/' . str_pad($m[2],3,'0',STR_PAD_LEFT);
            } elseif (preg_match('/RT\s*[:=]?\s*(\d+).*?RW\s*[:=]?\s*(\d+)/i', $line, $m)) {
                if (empty($fields['rt_rw'])) $fields['rt_rw'] = str_pad($m[1],3,'0',STR_PAD_LEFT) . '/' . str_pad($m[2],3,'0',STR_PAD_LEFT);
            } elseif (preg_match('/\b(\d{1,3})\s*\/\s*(\d{1,3})\b/i', $line, $m)) {
                if ((stripos($lineLower, 'rt') !== false || stripos($lineLower, 'rw') !== false) && empty($fields['rt_rw']))
                    $fields['rt_rw'] = str_pad($m[1],3,'0',STR_PAD_LEFT) . '/' . str_pad($m[2],3,'0',STR_PAD_LEFT);
            }

            // 10. Kelurahan/Desa
            if (preg_match('/(?:Kelurahan|Desa|Kel\.?\s*\/?Desa|Kel\.)\s*[:=]\s*(.+?)(?=\s*(?:Kecamatan|Kec\.?|Kota|Kabupaten|Provinsi|Agama|Status|Perkawinan|Pekerjaan|$))/i', $line, $m)) {
                $kel = rtrim(trim($m[1]), ' ,;:.-');
                $kel = $this->cleanupOCRNoise($kel);
                if (!empty($kel) && strlen($kel) > 2 && empty($fields['kelurahan'])) $fields['kelurahan'] = $kel;
            }

            // 11. Kecamatan
            if (preg_match('/Kecamatan\s*[:=]\s*(.+?)(?=\s*(?:Kota|Kabupaten|Provinsi|Agama|Status|Perkawinan|Pekerjaan|$))/i', $line, $m)) {
                $kec = rtrim(trim($m[1]), ' ,;:.-');
                $kec = $this->cleanupOCRNoise($kec);
                if (!empty($kec) && strlen($kec) > 2) $fields['kecamatan'] = $kec;
            }

            // 12. Kota/Kabupaten
            $lineForKota = $line;
            if (preg_match('/^(.*?)\s+NIK\b/i', $lineForKota, $kc)) $lineForKota = $kc[1];
            if (preg_match('/(?:Kota\/Kabupaten|Kota|Kabupaten)\s*(?:[:=]\s*|\s+)([a-zA-Z][a-zA-Z\s]*?)(?=\s*(?:NIK|\d{4,}|Nama|Tempat|Tgl|Jenis|Gol|Alamat|RT\/?RW|Kelurahan|Desa|Kecamatan|Agama|Status|Perkawinan|Pekerjaan|$))/i', $lineForKota, $m)) {
                $k = preg_replace('/\s+/', ' ', trim($m[1]));
                $k = preg_replace('/^(KOTA|KABUPATEN)\s+\1\b/i', '$1', $k);
                $k = rtrim($k, ' ,;:.-');
                $k = $this->cleanupOCRNoise($k);
                if (!empty($k) && strlen($k) > 2) $fields['kota_kabupaten'] = $k;
            }

            // 13. Provinsi
            if (preg_match('/Provinsi\s*(?:[:=]\s*|\s+)([^\n\r|]+)/i', $line, $m)) {
                $p = $this->cleanupOCRNoise(preg_replace('/\s+/', ' ', trim($m[1])));
                if (!empty($p) && strlen($p) > 2) $fields['provinsi'] = $p;
            }

            // 14. Agama
            if (preg_match('/Agama\s*[:=]\s*([^\n\r|]+)/i', $line, $m)) {
                $agama = trim($m[1]);
                $agamaList = ['ISLAM','KRISTEN','KATOLIK','HINDU','BUDDHA','BUDHA','KONGHUCU','KONG HU CU'];
                $found = null;
                foreach ($agamaList as $ag) {
                    if (stripos($agama, $ag) !== false) { $found = $ag; break; }
                }
                if ($found) {
                    $fields['agama'] = strtoupper($found);
                } else {
                    $a = $this->cleanupOCRNoise($agama);
                    $w = explode(' ', $a);
                    if (!empty($w[0]) && strlen($w[0]) > 2) $fields['agama'] = strtoupper($w[0]);
                }
            }

            // 15. Status Perkawinan
            if (preg_match('/(?:Status\s+)?Perkawinan\s*[:=]\s*([^\n\r|]+)/i', $line, $m)) {
                $st = trim($m[1]);
                if (!empty($st) && strlen($st) > 2) {
                    if (preg_match('/\b(BELUM\s+KAWIN|KAWIN|CERAI\s+HIDUP|CERAI\s+MATI)\b/i', $st, $sm)) {
                        $fields['status_perkawinan'] = strtoupper(preg_replace('/\s+/', ' ', trim($sm[1])));
                    } else {
                        $st = $this->cleanupFieldValue($st, 'status_perkawinan');
                        $fields['status_perkawinan'] = strtoupper(preg_replace('/\s+/', ' ', trim($st)));
                    }
                }
            }

            // 16. Pekerjaan
            if (preg_match('/Pekerjaan\s*[:=>\!\s]\s*([^\n\r|]+)/i', $line, $m)) {
                $pek = ltrim(trim($m[1]), '!>= ');
                $pek = preg_replace('/\s+\d{1,2}\s*[-\/]\s*\d{1,2}\s*[-\/]\s*\d{2,4}\s*$/', '', $pek);
                $pek = preg_replace('/\s+[A-Z]{5,}\s*$/', '', $pek);
                $pek = $this->cleanupOCRNoise($pek);
                $pek = $this->cleanupFieldValue($pek, 'pekerjaan');
                $pek = strtoupper(trim($pek));
                if (!empty($pek) && strlen($pek) > 2 && empty($fields['pekerjaan'])) $fields['pekerjaan'] = $pek;
            } elseif (empty($fields['pekerjaan']) && preg_match('/(?:Kerjaan|Kerja)\s+[>:\-=]\s*(.+)/i', $line, $m)) {
                $pek = $this->cleanupOCRNoise(trim($m[1]));
                $pek = $this->cleanupFieldValue($pek, 'pekerjaan');
                $pek = strtoupper(trim($pek));
                if (!empty($pek) && strlen($pek) > 2) $fields['pekerjaan'] = $pek;
            }
        }

        // Cleanup
        foreach ($fields as $key => &$value) {
            if ($value !== null) {
                $value = preg_replace('/\s+/', ' ', trim($value));
                $value = rtrim($value, '.,;:!?');
            }
        }
        unset($value);

        // Fallback NIK via image crop
        if (empty($fields['nik']) && $imagePath !== null) {
            $imageNik = $this->extractNikFromImage($imagePath);
            if (!empty($imageNik)) $fields['nik'] = $imageNik;
        }

        // Fallback NIK from full text
        if (empty($fields['nik'])) {
            if (preg_match_all('/NIK\s*[:=\s]*([0-9OolISZB8\.\-\s]{12,30})/i', $text, $nm)) {
                foreach ($nm[1] as $c) {
                    $n = $this->normalizeNikCandidate($c);
                    if ($n !== null) { $fields['nik'] = $n; break; }
                }
            }
        }
        if (empty($fields['nik'])) {
            if (preg_match_all('/\b([0-9OolISZBdqg]{15,20})\b/i', $text, $dm)) {
                foreach ($dm[1] as $c) {
                    if (strlen($c) >= 15) {
                        $n = $this->normalizeNikCandidate($c);
                        if ($n !== null) { $fields['nik'] = $n; break; }
                    }
                }
            }
        }

        return $fields;
    }

    // ============================================================
    // EXTRACT NOMOR KK
    // ============================================================

    public function extractNomorKK(string $text): ?string
    {
        Log::debug('[KK] extractNomorKK called, text length: ' . strlen($text));

        if (preg_match('/(?:No|Nomor)[\.\s,:=]*([0-9OolISZB]{14,18})/i', $text, $m)) {
            return $this->normalizeNikCandidate($m[1]);
        }
        if (preg_match('/KK[\.\s,:=]*([0-9OolISZB]{14,18})/i', $text, $m)) {
            return $this->normalizeNikCandidate($m[1]);
        }
        if (preg_match_all('/\b([0-9OolISZB]{14,18})\b/i', $text, $m)) {
            foreach ($m[1] as $c) {
                $n = $this->normalizeNikCandidate($c);
                if ($n) return $n;
            }
        }

        Log::warning('[KK] No Nomor KK found');
        return null;
    }

    // ============================================================
    // HELPER FUNCTIONS
    // ============================================================

    public function normalizeNikCandidate(string $value): ?string
    {
        $niks = preg_replace('/[\s\-\.\,]/', '', trim($value));
        $niks = preg_replace('/[Oo]/', '0', $niks);
        $niks = preg_replace('/[lI|!]/', '1', $niks);
        $niks = preg_replace('/[Zz]/', '2', $niks);
        $niks = preg_replace('/[Bb]/', '8', $niks);
        $niks = preg_replace('/[Ss]/', '5', $niks);
        $niks = preg_replace('/[Gg]/', '9', $niks);
        $niks = preg_replace('/[Qq]/', '0', $niks);
        $niks = preg_replace('/[Dd]/', '0', $niks);
        $niks = preg_replace('/[Tt]/', '1', $niks);
        $niks = preg_replace('/[Vv]/', '4', $niks);
        $niks = preg_replace('/\D/', '', $niks);

        if (strlen($niks) >= 14) return $niks;
        return null;
    }

    public function cleanupOCRNoise(string $text): string
    {
        $text = trim($text);
        if (($p = strpos($text, '|')) !== false) $text = substr($text, 0, $p);
        $text = preg_replace('/[\*~!^#@$%&+=\(\)\[\]{}\\\<>\?`"\'.]{2,}/i', '', $text);
        $text = preg_replace('/[\*~!^#@$%&+=\[\]{}\\\<>\?`"\']/', '', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = preg_replace('/\s+\d+\s+[a-z]{1,3}\s*$/i', '', $text);
        $text = preg_replace('/\s+[a-z]{1,2}\s*$/i', '', $text);
        $text = preg_replace('/\s+[a-z]{1,2}\s*~+\s*[a-z]?\s*$/i', '', $text);
        return trim($text);
    }

    public function cleanupFieldValue(string $value, string $field = ''): string
    {
        $value = trim($value);
        if (($p = strpos($value, '|')) !== false) $value = substr($value, 0, $p);

        $watermarkWords = ['KARTU','TANDA','PENDUDUK','REPUBLIK','INDONESIA','BERLAKU','SEUMUR','HIDUP'];
        foreach ($watermarkWords as $ww) {
            $value = preg_replace('/\s+' . preg_quote($ww, '/') . '\s*$/i', '', $value);
        }

        $value = preg_replace('/\s+\d{1,2}\s*[-\/]\s*\d{1,2}\s*[-\/]\s*\d{2,4}\s*$/i', '', $value);
        $value = preg_replace('/\s+\d{1,2}\s*$/', '', $value);

        if (!in_array($field, ['gol_darah'])) {
            $value = preg_replace('/\s+(?!WNI|WNA|A\b|AB\b|B\b|O\b)[A-Z]{1,2}\s*$/u', '', $value);
        }

        return preg_replace('/\s+/', ' ', trim($value));
    }

    public function cleanupTempatTglLahir(string $text): string
    {
        $text = trim($text);
        $text = preg_replace('/^.*?[Ll][Aa][Hh][Ii][Rr]\s*[^A-Za-z0-9]*\s*/i', '', $text);

        if (preg_match('/^([^,\d]+?)\s*,?\s*(\d.*?)\s*$/i', $text, $m)) {
            $location = $this->cleanupOCRNoise(trim($m[1]));
            $dateStr = trim($m[2]);
            $dateStr = preg_replace('/[\*~!^|#@$%&+=\(\)\[\]{}\\\<>\/\?`"\']/i', '', $dateStr);

            if (preg_match('/\d+\s*[-\/]\s*\d+\s*[-\/]\s*\d+/i', $dateStr)) {
                preg_match_all('/\d+/', $dateStr, $dm);
                $digits = $dm[0];
                if (count($digits) >= 3) {
                    $day = str_pad($digits[0], 2, '0', STR_PAD_LEFT);
                    $month = str_pad($digits[1], 2, '0', STR_PAD_LEFT);
                    $year = $digits[2];
                    if (strlen($year) == 3 && count($digits) >= 4) $year .= $digits[3];
                    if (strlen($year) < 4 && strlen($year) == 3 && $year[0] == '1') $year .= '0';
                    return $location . ' / ' . $day . '-' . $month . '-' . $year;
                }
            }
        }

        return $text;
    }

    public function extractNikFromImage(string $imagePath): ?string
    {
        if (!file_exists($imagePath)) return null;

        $tempCrop = tempnam(sys_get_temp_dir(), 'nik_') . '.png';

        try {
            if (!$this->createNikCrop($imagePath, $tempCrop)) return null;

            $bestNik = null;

            // Strategy 1: OCR-A model
            $ocraPath = 'C:/Program Files/Tesseract-OCR/tessdata/ocra.traineddata';
            if (file_exists($ocraPath)) {
                foreach ([7, 13, 8] as $psm) {
                    try {
                        $ocr = new TesseractOCR($tempCrop);
                        $ocr->lang('ocra')->psm($psm)->oem(1)->allowlist('0123456789');
                        $nikText = trim((string)$ocr->run());
                        if (empty($nikText)) continue;
                        $digits = preg_replace('/\D/', '', $nikText);
                        if (strlen($digits) == 16) return $digits;
                        if (strlen($digits) > 16 && !$bestNik) $bestNik = substr($digits, 0, 16);
                        if (strlen($digits) >= 15 && strlen($digits) <= 17 && !$bestNik) $bestNik = substr($digits, 0, 16);
                    } catch (\Exception $e) { continue; }
                }
                if ($bestNik) return $bestNik;
            }

            // Strategy 2: Digit-only
            foreach ([7, 8, 13] as $psm) {
                try {
                    $ocr = new TesseractOCR($tempCrop);
                    $ocr->lang('eng')->psm($psm)->oem(3)->allowlist('0123456789');
                    $nikText = trim((string)$ocr->run());
                    if (empty($nikText)) continue;
                    $digits = preg_replace('/\D/', '', $nikText);
                    if (strlen($digits) == 16) return $digits;
                    if (strlen($digits) > 16 && !$bestNik) $bestNik = substr($digits, 0, 16);
                } catch (\Exception $e) { continue; }
            }
            if ($bestNik) return $bestNik;

            // Strategy 3: No allowlist
            foreach ([6, 7, 8] as $psm) {
                try {
                    $ocr = new TesseractOCR($tempCrop);
                    $ocr->lang('eng')->psm($psm)->oem(3);
                    $nikText = trim((string)$ocr->run());
                    if (!empty($nikText)) {
                        $nik = $this->normalizeNikCandidate($nikText);
                        if ($nik !== null) return $nik;
                    }
                } catch (\Exception $e) { continue; }
            }

            return null;
        } finally {
            if (file_exists($tempCrop)) @unlink($tempCrop);
        }
    }

    private function createNikCrop(string $imagePath, string $outputPath): bool
    {
        // Use GD for maximum compatibility
        try {
            $imageInfo = getimagesize($imagePath);
            if (!$imageInfo) return false;

            $w = $imageInfo[0]; $h = $imageInfo[1];
            switch ($imageInfo[2]) {
                case IMAGETYPE_JPEG: $src = imagecreatefromjpeg($imagePath); break;
                case IMAGETYPE_PNG:  $src = imagecreatefrompng($imagePath); break;
                case IMAGETYPE_GIF:  $src = imagecreatefromgif($imagePath); break;
                default: $src = imagecreatefromstring(file_get_contents($imagePath));
            }
            if (!$src) return false;

            $yStart = (int)($h * 0.10);
            $cropH = (int)($h * 0.15);
            $cropW = (int)($w * 0.65);

            $crop = imagecreatetruecolor($cropW, $cropH);
            imagecopy($crop, $src, 0, 0, 0, $yStart, $cropW, $cropH);
            imagedestroy($src);

            $nw = $cropW * 3; $nh = $cropH * 3;
            $up = imagecreatetruecolor($nw, $nh);
            imagecopyresampled($up, $crop, 0, 0, 0, 0, $nw, $nh, $cropW, $cropH);
            imagedestroy($crop);

            @imagefilter($up, IMG_FILTER_GRAYSCALE);
            @imagefilter($up, IMG_FILTER_BRIGHTNESS, 20);
            @imagefilter($up, IMG_FILTER_CONTRAST, -80);

            $sm = [[-1,-1,-1],[-1,12,-1],[-1,-1,-1]];
            @imageconvolution($up, $sm, 4, 0);

            $bordered = imagecreatetruecolor($nw + 40, $nh + 40);
            $white = imagecolorallocate($bordered, 255, 255, 255);
            imagefill($bordered, 0, 0, $white);
            imagecopy($bordered, $up, 20, 20, 0, 0, $nw, $nh);
            imagedestroy($up);

            imagepng($bordered, $outputPath, 0);
            imagedestroy($bordered);
            return true;
        } catch (\Exception $e) {
            Log::error('[OCR] NIK crop failed: ' . $e->getMessage());
            return false;
        }
    }
}
