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
            'fields'   => [],
            'raw_text' => '',
        ];

        // --- 1. PROSES KTP ---
        $preprocessedKtp = tempnam(sys_get_temp_dir(), 'ocr_ktp_') . '.png';

        try {
            if (!$this->preprocessImage($ktpPath, $preprocessedKtp)) {
                Log::error('[OCR] Preprocessing KTP gagal');
                return $result;
            }

            $ocrKtp = new TesseractOCR($preprocessedKtp);
            $ocrKtp->lang('ind+eng')->psm(6)->oem(3);
            $extractedText = $ocrKtp->run();

            if (empty($extractedText) || trim($extractedText) === '') {
                Log::error('[OCR] Tesseract KTP menghasilkan hasil kosong');
                return $result;
            }

            $processedText = $this->postProcessOCRText(trim((string)$extractedText));
            if (!is_string($processedText) || trim($processedText) === '') {
                $processedText = trim((string)$extractedText);
            }

            $result['raw_text'] = $processedText;
            $result['fields']   = $this->extractKtpFields($processedText, $preprocessedKtp);

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

            $ocrKk = new TesseractOCR($preprocessedKk);
            $ocrKk->lang('ind+eng')->psm(6)->oem(3);
            $extractedTextKk = $ocrKk->run();

            if (!empty($extractedTextKk)) {
                $nomorKk = $this->extractNomorKK($extractedTextKk);
                if ($nomorKk) {
                    $result['fields']['nomor_kk'] = $nomorKk;
                } else {
                    Log::warning('[OCR] Gagal menemukan Nomor KK dari teks OCR KK');
                }
            } else {
                Log::warning('[OCR] Tesseract KK menghasilkan hasil kosong');
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
            $image     = null;

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

            $width  = imagesx($image);
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
                $scale  = 300 / $width;
                $newW   = (int)($width * $scale);
                $newH   = (int)($height * $scale);
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
        $processedText = (string)$text;

        // 1. Fix common character misreads
        $fixes = [
            '/([a-zA-Z0-9])\s*=\s*([a-zA-Z0-9])/' => '$1 : $2',
            '/\btg[!]?\b/'                          => 'tgl',
            '/\bTg[!]?\b/'                          => 'Tgl',
            '/\btg([!?])\s/'                        => 'tgl ',
            '/\bRT[!|]RW/'                          => 'RT/RW',
            '/\bsel[!a]tan\b/'                      => 'selatan',
            '/Kel\s*[.\/]\s*Desa/'                  => 'Kel/Desa',
            '/Kelurahan\s*[.\/]\s*Desa/'            => 'Kelurahan/Desa',
            '/Kel\b/'                                => 'Kelurahan',
            '/Peker[]j]aan/'                         => 'Pekerjaan',
            '/Kerja(?!an)/'                          => 'Pekerjaan',
            '/Berlaku\s+Hing[g]ga/'                 => 'Berlaku Hingga',
            '/Berlaku\s+s\.?d\.?/'                  => 'Berlaku Hingga',
            '/Sampai/'                               => 'Berlaku Hingga',
            '/Tempat\s+[\/|]\s+Tgl/'                => 'Tempat / Tgl',
            '/Tempat\s+Tgl\s+Lahir/'                => 'Tempat/Tgl Lahir',
            '/([A-Z0-9])-\s*([A-Z0-9])/'            => '$1 - $2',
            '/\s{2,}/'                               => ' ',
            '/(\d)\s+([.,])/'                        => '$1$2',
            '/\s+([,.:;])/'                          => '$1',
        ];

        foreach ($fixes as $pattern => $replacement) {
            $updated = @preg_replace($pattern, $replacement, $processedText);
            if ($updated !== null) $processedText = $updated;
        }

        // =========================================================
        // STEP 1.5: Pisahkan field yang menempel dalam 1 baris
        // OCR KTP fisik sering menggabungkan field dalam satu baris (e.g. NIK... Nama...)
        // Pisahkan dengan \n agar regex per field tidak melahap isi field lain!
        // =========================================================
        $splitKeywords = [
            'NIK', 'Nama', 'Tempat\/Tgl(?:\s*Lahir)?', 'Tempat\s+Tgl\s+Lahir',
            'Jenis\s+Kelamin', 'Gol\.?\s*Darah', 'Alamat',
            'RT\s*\/\s*RW', 'RT\s*\/\s*R[wW]',
            'Kel(?:urahan)?(?:\s*\/\s*Desa)?', 'Kecamatan',
            'Agama', 'Status\s+Perkawinan', 'Pekerjaan',
            'Kewarganegaraan', 'Berlaku\s*Hingga',
        ];
        $splitPattern  = '/(?:\s+|[|—\-,;]\s*)(?=' . implode('|', $splitKeywords) . '\s*[:.=—\->])/i';
        $processedText = preg_replace($splitPattern, "\n", $processedText);

        // 2. Fix NIK context
        $nikFixed = @preg_replace_callback(
            '/NIK\s*[©®°:.=>\-\s]+([0-9OolLISZBDd! \t\.\-]+)/i',
            function ($m) {
                $n = trim($m[1]);
                $n = preg_replace('/[Dd]/', '0', $n);
                $n = preg_replace('/[Oo]/', '0', $n);
                $n = preg_replace('/[lL!I]/', '1', $n);
                $n = preg_replace('/[Ss]/', '5', $n);
                $n = preg_replace('/[Zz]/', '2', $n);
                $n = preg_replace('/[Bb]/', '8', $n);
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
            $c    = @preg_replace('/\s+[!@#$%^&*()_+=\[\]{};\'",<>?\/\\\\|`~]$/', '', $line);
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
            'nik'               => null,
            'nama'              => null,
            'nomor_kk'          => null,
            'tempat_lahir'      => null,
            'tanggal_lahir'     => null,
            'jenis_kelamin'     => null,
            'gol_darah'         => null,
            'alamat'            => null,
            'rt_rw'             => null,
            'kelurahan'         => null,
            'kecamatan'         => null,
            'kota_kabupaten'    => null,
            'provinsi'          => null,
            'agama'             => null,
            'status_perkawinan' => null,
            'pekerjaan'         => null,
        ];

        $lines = explode("\n", $text);

        // ============================================================
        // LANGKAH 1: Extract NIK menggunakan crop gambar (PRIORITAS UTAMA)
        // Image crop jauh lebih akurat dari parsing teks OCR yang sering noise
        // ============================================================
        if ($imagePath !== null) {
            $imageNikDirect = $this->extractNikFromImage($imagePath);
            if (!empty($imageNikDirect)) {
                $fields['nik'] = $imageNikDirect;
                Log::debug('[NIK] Direct image crop SUCCESS: ' . $imageNikDirect);
            }
        }

        // ============================================================
        // LANGKAH 2: Parse baris per baris untuk field lainnya (dan NIK sebagai fallback)
        // ============================================================
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            $lineLower = strtolower($line);

            // 1. NIK: Prioritaskan dari teks mentah (text parsing SELALU jalan meskipun crop berhasil)
            if (stripos($lineLower, 'nik') !== false) {
                $nikCandidate = null;

                // Step A: Isolasi teks tepat SETELAH keyword NIK
                $nikPart = '';
                if (preg_match('/(?:NIK|Wik|N[i1]k)\s*[©:\-=>\.\s]\s*([0-9OolLISZBbDd8!\s\.]{10,25})/i', $line, $preMatches)) {
                    $nikPart = trim($preMatches[1]);
                }

                // Step B: Bersihkan nikPart
                if (!empty($nikPart)) {
                    $nikCompact = preg_replace('/\s+/', '', $nikPart);
                    if (preg_match('/([0-9OolLISZBbDd!]{14,18})/', $nikCompact, $matches)) {
                        $nikCandidate = $matches[1];
                    } elseif (preg_match('/([0-9OolLISZBbDd!\.\-]{10,20})/', $nikPart, $matches)) {
                        $nikCandidate = $matches[1];
                    }
                }

                // Step C: Fallback - cari sequence digit terpanjang di baris
                if (empty($nikCandidate)) {
                    preg_match_all('/[0-9OolLISZBbDd!]{6,}/', $line, $allDigitGroups);
                    if (!empty($allDigitGroups[0])) {
                        usort($allDigitGroups[0], function ($a, $b) { return strlen($b) - strlen($a); });
                        foreach ($allDigitGroups[0] as $group) {
                            if (strlen($group) >= 14) {
                                $nikCandidate = $group;
                                break;
                            }
                        }
                    }
                }

                if (!empty($nikCandidate)) {
                    $nikNormalized = $this->normalizeNikCandidate($nikCandidate);
                    if ($nikNormalized !== null && $nikNormalized[0] !== '0') {
                        $fields['nik'] = $nikNormalized;
                    }
                }
            }

            // 2. Nama
            if (preg_match('/Nama\s*[:=]\s*([^|\n\r]+)/i', $line, $m)) {
                $nama = trim($m[1]);

                // Potong TEPAT sebelum keyword field berikutnya
                $stopKeywords = [
                    'Tempat', 'Tgl', 'Lahir', 'Jenis', 'Kelamin', 'Gol', 'Darah',
                    'Alamat', 'RT', 'RW', 'Kel', 'Kecamatan', 'Agama', 'Status',
                    'Pekerjaan', 'Kewarganegaraan', 'Berlaku',
                ];
                foreach ($stopKeywords as $kw) {
                    if (preg_match('/\b' . preg_quote($kw, '/') . '\b/i', $nama, $kwMatch, PREG_OFFSET_CAPTURE)) {
                        $nama = substr($nama, 0, $kwMatch[0][1]);
                    }
                }

                // Potong sebelum digit panjang (bukan inisial/angka nama)
                $nama = preg_replace('/\s+\d{3,}.*$/s', '', $nama);

                $nama = $this->cleanupOCRNoise($nama);
                $nama = $this->cleanupFieldValue($nama, 'nama');
                $nama = preg_replace('/[^A-Za-z\s\.\,\-\']/u', '', $nama);
                $nama = preg_replace('/\s+/', ' ', $nama);
                $nama = trim($nama);

                // Hapus pola berulang di akhir: "SA SA", "KA KA", "DU DU"
                $nama = preg_replace('/(?:\s+([A-Za-z]{1,4}))(?:\s+\1)+\s*$/i', '', $nama);

                // Hapus trailing noise
                $nama = preg_replace('/\s+[A-Za-z]{1,3}\s*-\s*$/i', '', $nama);
                $nama = preg_replace('/\s+-\s*$/', '', $nama);
                $nama = preg_replace('/\s+[A-Z]{1,2}\s*$/u', '', $nama);
                $nama = trim($nama);

                if (!empty($nama) && strlen($nama) > 2) {
                    $fields['nama'] = strtoupper($nama);
                }
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
            } elseif (preg_match('/TempaTgl\s*[:=>|\s]\s*(.+)/i', $line, $m)) {
                $c = trim($m[1]);
                if (!empty($c) && strlen($c) > 3 && stripos($c, 'lahir') !== false && empty($fields['tempat_lahir']))
                    $combinedTTL = $this->cleanupTempatTglLahir($c);
            } elseif (preg_match('/Tempat\s+Lahir\s*[:=]\s*([^,|\n\r]+?)(?:\s+(\d{1,2}[-\/]\d{1,2}[-\/]\d{4}))?/i', $line, $m)) {
                $t = trim($m[1]);
                $d = isset($m[2]) ? trim($m[2]) : '';
                if (!empty($t) && strlen($t) > 2 && !empty($d) && empty($fields['tempat_lahir']))
                    $combinedTTL = $t . ' / ' . $d;
            }

            if ($combinedTTL === null && empty($fields['tempat_lahir']) && preg_match('/([A-Z][A-Za-z\s,.-]+?)(?:\s+\/?\ s+|,\s+)?(.*)$/i', $line, $m)) {
                if (stripos($lineLower, 'lahir') !== false) {
                    $t  = trim($m[1]);
                    $ds = isset($m[2]) ? trim($m[2]) : '';
                    if (!empty($ds)) $combinedTTL = $this->cleanupTempatTglLahir($t . ', ' . $ds);
                }
            }

            if (!empty($combinedTTL) && empty($fields['tempat_lahir'])) {
                if (preg_match('/^(.+?)\s*[\/,]\s*(\d{1,2}\s*[-\/]\s*\d{1,2}\s*[-\/]\s*\d{2,4})/', $combinedTTL, $sp)) {
                    $fields['tempat_lahir']  = trim($sp[1]);
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
            if (preg_match('/Alamat\s*[:.=\-—>]?\s*[:=]?\s*(.+?)(?=\s*(?:\||RT\s*\/\s*RW|RT\/RW|RT\s*[:=>]|RW\s*[:=>]|Kelurahan|Desa|Kel\.\s*\/?|Kecamatan|Kec\.?|$))/i', $line, $m)) {
                $al = trim($m[1]);
                if (($pp = strpos($al, '|')) !== false) $al = substr($al, 0, $pp);
                $al = preg_replace('/\s+[a-zA-Z]\s*[:=]\s*[a-zA-Z]?\s*$/', '', $al);
                $al = preg_replace('/\s*[§~!@#$%^&*()+=\[\]{}|\\<>?\/`]+\s*$/', '', $al);
                $al = preg_replace('/\s+["\'"]?\s*$/', '', $al);
                $al = rtrim($al, ' ,;:.-');
                $al = $this->cleanupOCRNoise($al);
                $al = $this->cleanupFieldValue($al, 'alamat');
                if (!empty($al) && strlen($al) > 2) $fields['alamat'] = strtoupper($al);
            }

            // 9. RT/RW
            if (preg_match('/RT\s*\/\s*R[wW]\s*[—\-\.]*\s*[:=>]?\s*[—\-:]*\s*(\d+)\s*\/\s*(\d+)/i', $line, $m)) {
                if (empty($fields['rt_rw'])) $fields['rt_rw'] = str_pad($m[1], 3, '0', STR_PAD_LEFT) . '/' . str_pad($m[2], 3, '0', STR_PAD_LEFT);
            } elseif (preg_match('/RT\s*[:=]?\s*(\d+).*?RW\s*[:=]?\s*(\d+)/i', $line, $m)) {
                if (empty($fields['rt_rw'])) $fields['rt_rw'] = str_pad($m[1], 3, '0', STR_PAD_LEFT) . '/' . str_pad($m[2], 3, '0', STR_PAD_LEFT);
            } elseif (preg_match('/\b(\d{1,3})\s*\/\s*(\d{1,3})\b/i', $line, $m)) {
                if ((stripos($lineLower, 'rt') !== false || stripos($lineLower, 'rw') !== false) && empty($fields['rt_rw']))
                    $fields['rt_rw'] = str_pad($m[1], 3, '0', STR_PAD_LEFT) . '/' . str_pad($m[2], 3, '0', STR_PAD_LEFT);
            }

            // 10. Kelurahan/Desa
            if (preg_match('/(?:Kelurahan|Desa|Kel(?:urahan)?\s*\/\s*Desa|Kel\.)\s*[—\-\.]*\s*[:=_]?\s*(.+?)(?=\s*(?:Kecamatan|Kec\.?|Kota|Kabupaten|Provinsi|Agama|Status|Perkawinan|Pekerjaan|Kewarganegaraan|Berlaku|$))/i', $line, $m)) {
                $kel = trim($m[1]);
                $kel = preg_replace('/^[—_\-:.\s]+/', '', $kel);
                $kel = rtrim($kel, ' ,;:.-');
                $kel = $this->cleanupOCRNoise($kel);
                if (!empty($kel) && strlen($kel) > 2 && empty($fields['kelurahan'])) $fields['kelurahan'] = $kel;
            }

            // 11. Kecamatan
            if (preg_match('/Kecamatan\s*[—\-\.]*\s*[:=]?\s*(.+?)(?=\s*(?:Kota|Kabupaten|Provinsi|Agama|Status|Perkawinan|Pekerjaan|Kewarganegaraan|Berlaku|$))/i', $line, $m)) {
                $kec = trim($m[1]);
                $kec = preg_replace('/^[—_\-:.\s]+/', '', $kec);
                $kec = rtrim($kec, ' ,;:.-');
                $kec = $this->cleanupOCRNoise($kec);
                if (!empty($kec) && strlen($kec) > 2) $fields['kecamatan'] = $kec;
            }

            // 12. Kota/Kabupaten
            $lineForKota = $line;
            if (preg_match('/^(.*?)\s+NIK\b/i', $lineForKota, $kc)) $lineForKota = $kc[1];
            if (preg_match('/(?:Kota\/Kabupaten|Kota|Kabupaten)\s*(?:[:=]\s*|\s+)([a-zA-Z][a-zA-Z\s]*?)(?=\s*(?:NIK|\d{4,}|Nama|Tempat|Tgl|Jenis|Gol|Alamat|RT\/?RW|Kelurahan|Desa|Kecamatan|Agama|Status|Perkawinan|Pekerjaan|Kewarganegaraan|Berlaku|$))/i', $lineForKota, $m)) {
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
                $agama     = trim($m[1]);
                $agamaList = ['ISLAM', 'KRISTEN', 'KATOLIK', 'HINDU', 'BUDDHA', 'BUDHA', 'KONGHUCU', 'KONG HU CU'];
                $found     = null;
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
            if (preg_match('/Pekerjaan\s*[:=>!\s]\s*([^\n\r|]+)/i', $line, $m)) {
                $pek = ltrim(trim($m[1]), '!>= ');
                $pek = preg_replace('/\s+\d{1,2}\s*[-\/]\s*\d{1,2}\s*[-\/]\s*\d{2,4}\s*$/', '', $pek);
                $pek = preg_replace('/\s+(?!MAHASISWA|SWASTA|WIRASWASTA|PELAJAR|PEGAWAI|NEGERI|SIPIL|BURUH|TANI|NELAYAN|GURU|KARYAWAN|DOKTER|TENTARA|POLISI|PERAWAT|PETANI|PEDAGANG)[A-Z]{4,}\s*$/i', '', $pek);
                $pek = preg_replace('/\s+[a-z]{1,5}\s*$/', '', $pek);
                $pek = $this->cleanupOCRNoise($pek);
                $pek = $this->cleanupFieldValue($pek, 'pekerjaan');
                $pek = strtoupper(trim($pek));
                if (!empty($pek) && strlen($pek) > 2 && empty($fields['pekerjaan'])) $fields['pekerjaan'] = $pek;
            } elseif (preg_match('/Kerjaan\s*[:=>|\s]\s*([^\n\r|]+)/i', $line, $m)) {
                $pek = trim($m[1]);
                $pek = preg_replace('/\s+\d{1,2}\s*[-\/]\s*\d{1,2}\s*[-\/]\s*\d{2,4}\s*$/', '', $pek);
                $pek = $this->cleanupOCRNoise($pek);
                $pek = $this->cleanupFieldValue($pek, 'pekerjaan');
                $pek = strtoupper(trim($pek));
                if (!empty($pek) && strlen($pek) > 2 && empty($fields['pekerjaan'])) $fields['pekerjaan'] = $pek;
            } elseif (empty($fields['pekerjaan']) && preg_match('/(?:Pekerjaan|Kerjaan|Kerja)\s+[>:\-=]\s*(.+)/i', $line, $m)) {
                $pek = $this->cleanupOCRNoise(trim($m[1]));
                $pek = $this->cleanupFieldValue($pek, 'pekerjaan');
                $pek = strtoupper(trim($pek));
                if (!empty($pek) && strlen($pek) > 2) $fields['pekerjaan'] = $pek;
            }
        }

        // Cleanup dan normalisasi fields
        foreach ($fields as $key => &$value) {
            if ($value !== null) {
                $value = preg_replace('/\s+/', ' ', trim($value));
                $value = rtrim($value, '.,;:!?');
            }
        }
        unset($value);

        // FALLBACK: Dedicated image crop OCR
        if (empty($fields['nik']) && $imagePath !== null) {
            $imageNik = $this->extractNikFromImage($imagePath);
            if (!empty($imageNik)) $fields['nik'] = $imageNik;
        }

        // Fallback 1: Cari dari keseluruhan teks OCR dengan label
        if (empty($fields['nik'])) {
            if (preg_match_all('/NIK\s*[:=\s]*([0-9OolISZB8\.\-\s]{12,30})/i', $text, $nm)) {
                foreach ($nm[1] as $c) {
                    $n = $this->normalizeNikCandidate($c);
                    if ($n !== null) { $fields['nik'] = $n; break; }
                }
            }
        }

        // Fallback 2: Cari 15-17 digit sequence tanpa label
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

        // Pre-clean: Hapus karakter noise umum dari OCR KK
        $cleanText = str_replace(['©', '®', '™', '°'], '', $text);

        // Pattern 1: "No." / "Nomor" / "N0" diikuti deretan digit
        if (preg_match('/(?:No|N0|Nomor|NOMOR)[\s\.,:=>\-]*([0-9OolLISZBD]{14,20})/i', $cleanText, $m)) {
            Log::debug('[KK] Pattern 1 matched: ' . $m[1]);
            return $this->normalizeNikCandidate($m[1]);
        }

        // Pattern 1b: "No." diikuti spasi lalu titik lalu digit
        if (preg_match('/(?:No|N0|Nomor)[\s\.]*[:\-=>]?\s*\.?\s*([0-9OolLISZBD\.\-\s]{14,25})/i', $cleanText, $m)) {
            $candidate = preg_replace('/[\s\.\-]/', '', $m[1]);
            if (strlen($candidate) >= 14) {
                Log::debug('[KK] Pattern 1b matched: ' . $candidate);
                return $this->normalizeNikCandidate($candidate);
            }
        }

        // Pattern 2: "KARTU KELUARGA" header lalu nomor
        if (preg_match('/KARTU\s+KELUARGA[\s\S]{0,50}?(?:No|N0|Nomor)?[\s\.,:=>\-]*([0-9OolLISZBD]{14,20})/i', $cleanText, $m)) {
            Log::debug('[KK] Pattern 2 (KARTU KELUARGA) matched: ' . $m[1]);
            return $this->normalizeNikCandidate($m[1]);
        }

        // Pattern 3: "KK" keyword diikuti digit
        if (preg_match('/KK[\s\.,:=>\-]*([0-9OolLISZBD]{14,20})/i', $cleanText, $m)) {
            Log::debug('[KK] Pattern 3 (KK keyword) matched: ' . $m[1]);
            return $this->normalizeNikCandidate($m[1]);
        }

        // Pattern 4: Cari sequence digit setelah baris "KELUARGA"
        $lines              = explode("\n", $cleanText);
        $foundKeluargaLine  = false;
        foreach ($lines as $kkLine) {
            $kkLine = trim($kkLine);
            if (stripos($kkLine, 'KELUARGA') !== false) {
                $foundKeluargaLine = true;
                if (preg_match('/([0-9OolLISZBD]{14,20})/', $kkLine, $m)) {
                    Log::debug('[KK] Pattern 4a (same line as KELUARGA): ' . $m[1]);
                    return $this->normalizeNikCandidate($m[1]);
                }
                continue;
            }
            if ($foundKeluargaLine && preg_match('/([0-9OolLISZBD]{14,20})/', $kkLine, $m)) {
                Log::debug('[KK] Pattern 4b (line after KELUARGA): ' . $m[1]);
                return $this->normalizeNikCandidate($m[1]);
            }
            if ($foundKeluargaLine && !empty($kkLine)) {
                $foundKeluargaLine = false;
            }
        }

        // Fallback: cari 14-18 digit sequences di mana saja
        if (preg_match_all('/([0-9OolLISZBD]{14,20})/i', $cleanText, $allMatches)) {
            foreach ($allMatches[1] as $candidate) {
                $normalized = $this->normalizeNikCandidate($candidate);
                if ($normalized) {
                    Log::debug('[KK] Fallback SUCCESS: ' . $normalized);
                    return $normalized;
                }
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
        $niks = preg_replace('/[\s\-\.\,:]/', '', trim($value));

        // Hanya konversi karakter yang secara visual SANGAT MIRIP dengan angka tertentu
        $niks = str_replace(['D'],         '0', $niks);
        $niks = str_replace(['O', 'o'],    '0', $niks);
        $niks = str_replace(['l', 'I', 'L', '!'], '1', $niks);
        $niks = str_replace(['S', 's'],    '5', $niks);
        $niks = str_replace(['Z', 'z'],    '2', $niks);
        $niks = str_replace(['B'],         '8', $niks);
        $niks = str_replace(['G', 'g'],    '9', $niks);
        $niks = str_replace(['Q', 'q'],    '0', $niks);
        $niks = str_replace(['T', 't'],    '1', $niks);
        $niks = str_replace(['V', 'v'],    '4', $niks);

        // Remove sisa karakter non-digit
        $niks = preg_replace('/\D/', '', $niks);

        if (strlen($niks) >= 16) return substr($niks, 0, 16);
        if (strlen($niks) >= 14) return $niks;

        return null;
    }

    public function cleanupOCRNoise(string $text): string
    {
        $text = trim($text);
        if (($p = strpos($text, '|')) !== false) $text = substr($text, 0, $p);
        $text = preg_replace('/[\*~!^#@$%&+=\(\)\[\]{}\\\<>\?`"\'\.]{2,}/i', '', $text);
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

        $watermarkWords = [
            'KARTU', 'TANDA', 'PENDUDUK', 'REPUBLIK', 'INDONESIA',
            'BERLAKU', 'SEUMUR', 'HIDUP',
            'PENDU', 'NDUK', 'TAND', 'ANDA',
        ];

        // Hanya hapus watermark jika bukan field berlaku_hingga
        if ($field !== 'berlaku_hingga') {
            foreach ($watermarkWords as $ww) {
                $value = preg_replace('/\s+' . preg_quote($ww, '/') . '\s*$/i', '', $value);
            }
        }

        // Khusus field nama
        if ($field === 'nama') {
            $value = preg_replace('/(?:\s+([A-Z]{1,4}))\s+\1(?:\s+\1)*\s*$/i', '', $value);
            $value = preg_replace('/\s+[A-Z]{1,3}\s*-\s*$/i', '', $value);
            $value = preg_replace('/\s+-\s*$/', '', $value);
        }

        $value = preg_replace('/\s+\d{1,2}\s*[-\/]\s*\d{1,2}\s*[-\/]\s*\d{2,4}\s*$/i', '', $value);
        $value = preg_replace('/\s+\d{1,2}\s*$/', '', $value);

        if (!in_array($field, ['gol_darah', 'kewarganegaraan'])) {
            $value = preg_replace('/\s+(?!WNI|WNA|A\b|AB\b|B\b|O\b)[A-Z]{1,2}\s*$/u', '', $value);
        }

        return preg_replace('/\s+/', ' ', trim($value));
    }

    public function cleanupTempatTglLahir(string $text): string
    {
        $text = trim($text);

        // Hapus prefix corrupted seperti "Te, mpat/Tgl: Lahir =:"
        $text = preg_replace('/^.*?[Ll][Aa][Hh][Ii][Rr]\s*[^A-Za-z0-9]*\s*/i', '', $text);

        if (preg_match('/^([^,\d]+?)\s*,?\s*(\d.*?)\s*$/i', $text, $m)) {
            $location = $this->cleanupOCRNoise(trim($m[1]));
            $dateStr  = trim($m[2]);
            $dateStr  = preg_replace('/[\*~!^|#@$%&+=\(\)\[\]{}\\\<>\/\?`"\']/i', '', $dateStr);

            if (preg_match('/\d+\s*[-\/]\s*\d+\s*[-\/]\s*\d+/i', $dateStr)) {
                preg_match_all('/\d+/', $dateStr, $dm);
                $digits = $dm[0];
                if (count($digits) >= 3) {
                    $day   = str_pad($digits[0], 2, '0', STR_PAD_LEFT);
                    $month = str_pad($digits[1], 2, '0', STR_PAD_LEFT);
                    $year  = $digits[2];

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

            // Strategy 0 (HIGHEST PRIORITY): Custom OCR-A trained model
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

            // Strategy 1: Digit-only allowlist dengan English model
            $bestNik = null;
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

            // Strategy 2: No allowlist, use normalizeNikCandidate
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
        try {
            // Prioritaskan Imagick jika tersedia (upscale 4x + threshold)
            if (extension_loaded('imagick') && class_exists('Imagick')) {
                return $this->createNikCropImagick($imagePath, $outputPath);
            }
            return $this->createNikCropGD($imagePath, $outputPath);
        } catch (\Exception $e) {
            Log::error('[OCR] NIK crop failed: ' . $e->getMessage());
            return false;
        }
    }

    private function createNikCropImagick(string $imagePath, string $outputPath): bool
    {
        try {
            $image      = new \Imagick($imagePath);
            $dimensions = $image->getImageGeometry();
            $w          = $dimensions['width'];
            $h          = $dimensions['height'];

            // KTP Indonesia: NIK row = 18-32% height
            $yStart     = (int)($h * 0.18);
            $yEnd       = (int)($h * 0.32);
            $cropW      = (int)($w * 0.70);
            $cropH      = $yEnd - $yStart;

            $image->cropImage($cropW, $cropH, 0, $yStart);
            $image->setImagePage(0, 0, 0, 0);

            // Upscale 4x
            $image->scaleImage($cropW * 4, $cropH * 4);
            $image->setImageFormat('png');
            $image->transformImageColorspace(\Imagick::COLORSPACE_GRAY);
            $image->normalizeImage();
            $image->enhanceImage();
            $image->thresholdImage(0.55 * \Imagick::getQuantum());
            $image->borderImage('white', 30, 30);
            $image->writeImage($outputPath);
            $image->destroy();
            return true;
        } catch (\Exception $e) {
            Log::warning('[OCR] Imagick NIK crop failed, using GD: ' . $e->getMessage());
            return $this->createNikCropGD($imagePath, $outputPath);
        }
    }

    private function createNikCropGD(string $imagePath, string $outputPath): bool
    {
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

            // KTP Indonesia: NIK row = 18-32% height
            $yStart = (int)($h * 0.18);
            $cropH  = (int)($h * 0.14);  // 32% - 18% = 14%
            $cropW  = (int)($w * 0.70);

            $crop = imagecreatetruecolor($cropW, $cropH);
            imagecopy($crop, $src, 0, 0, 0, $yStart, $cropW, $cropH);
            imagedestroy($src);

            // Upscale 4x
            $nw = $cropW * 4; $nh = $cropH * 4;
            $up = imagecreatetruecolor($nw, $nh);
            imagecopyresampled($up, $crop, 0, 0, 0, 0, $nw, $nh, $cropW, $cropH);
            imagedestroy($crop);

            @imagefilter($up, IMG_FILTER_GRAYSCALE);
            @imagefilter($up, IMG_FILTER_BRIGHTNESS, 10);
            @imagefilter($up, IMG_FILTER_CONTRAST, -70);

            $sm = [[-1,-1,-1],[-1,12,-1],[-1,-1,-1]];
            @imageconvolution($up, $sm, 4, 0);

            // Padding putih 30px
            $bordered = imagecreatetruecolor($nw + 60, $nh + 60);
            $white    = imagecolorallocate($bordered, 255, 255, 255);
            imagefill($bordered, 0, 0, $white);
            imagecopy($bordered, $up, 30, 30, 0, 0, $nw, $nh);
            imagedestroy($up);

            imagepng($bordered, $outputPath, 0);
            imagedestroy($bordered);
            return true;
        } catch (\Exception $e) {
            Log::error('[OCR] GD NIK crop failed: ' . $e->getMessage());
            return false;
        }
    }
}
