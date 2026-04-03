<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Petugas - Sistem Informasi Kelurahan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite('resources/css/app.css')
</head>
<body class="warga-home-page">
    @include('layout.header')
    
    <div class="main-container">
        @include('layout.petugas-sidebar')
        
        <main class="main-content">
            <div class="content-wrapper">
                <div class="greeting-section">
                    <h1>Selamat Datang [Nama Petugas]</h1>
                </div>

                <div class="stats-cards">
                    <div class="stat-card">
                        <div class="stat-icon pending">
                            <i class="fas fa-file"></i>
                        </div>
                        <div class="stat-info">
                            <p class="stat-label">Total Pengajuan Bulan Ini</p>
                            <p class="stat-count">7</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon download">
                            <i class="fas fa-pen-fancy"></i>
                        </div>
                        <div class="stat-info">
                            <p class="stat-label">Menunggu Tanda Tangan</p>
                            <p class="stat-count">1</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon add">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="stat-info">
                            <p class="stat-label">Analisis Demografi & Sosial</p>
                            <button class="stat-action btn-lihat-detail" id="btn-analisis-demografi">Lihat Detail</button>
                        </div>
                    </div>
                </div>

                <div class="activity-section">
                    <h2>Pengajuan Terbaru</h2>
                    <table class="activity-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Alamat</th>
                                <th>Jenis Surat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Ahmad Dian</td>
                                <td>3312450123231</td>
                                <td>Jalan Pisang No 8</td>
                                <td>Surat Keterangan Kelahiran</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-detail">Lihat Detail</button>
                                        <button class="btn-action btn-verifikasi">Verifikasi</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Siti Nurhaliza</td>
                                <td>3312450123232</td>
                                <td>Jalan Mangga No 5</td>
                                <td>Surat Keterangan Domisili</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-detail">Lihat Detail</button>
                                        <button class="btn-action btn-verifikasi">Verifikasi</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Budi Santoso</td>
                                <td>3312450123233</td>
                                <td>Jalan Jeruk No 12</td>
                                <td>Surat Keterangan Kemiskinan</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-detail">Lihat Detail</button>
                                        <button class="btn-action btn-verifikasi">Verifikasi</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    @include('layout.footer')

    <!-- Modal Analisis Demografi -->
    <div id="modal-demografi" class="modal hidden">
        <div class="modal-overlay" id="modal-overlay"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h2>Analisis Demografi - Distribusi Usia Penduduk</h2>
                <button class="modal-close" id="btn-close-modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body">
                <!-- Tabs untuk berbagai tipe grafik -->
                <div class="chart-tabs">
                    <button class="chart-tab active" data-chart="usia">
                        <i class="fas fa-chart-pie"></i> Distribusi Usia
                    </button>
                    <button class="chart-tab" data-chart="gender">
                        <i class="fas fa-venus-mars"></i> Jenis Kelamin
                    </button>
                    <button class="chart-tab" data-chart="agama">
                        <i class="fas fa-heart"></i> Agama
                    </button>
                </div>

                <!-- Container untuk Grafik -->
                <div class="chart-container">
                    <!-- Chart Usia -->
                    <div class="chart-wrapper active" id="chart-usia">
                        <canvas id="myChartUsia"></canvas>
                    </div>

                    <!-- Chart Gender -->
                    <div class="chart-wrapper" id="chart-gender">
                        <canvas id="myChartGender"></canvas>
                    </div>

                    <!-- Chart Agama -->
                    <div class="chart-wrapper" id="chart-agama">
                        <canvas id="myChartAgama"></canvas>
                    </div>
                </div>

                <!-- Statistik Summary -->
                <div class="chart-summary">
                    <div class="summary-item">
                        <span class="summary-label">Total Penduduk</span>
                        <span class="summary-value">2,547</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Rata-rata Usia</span>
                        <span class="summary-value">34.5 Tahun</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Usia Termuda</span>
                        <span class="summary-value">2 Tahun</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Usia Tertua</span>
                        <span class="summary-value">87 Tahun</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-export" id="btn-export-data">
                    <i class="fas fa-download"></i> Export Data
                </button>
                <button class="btn-close" id="btn-close-modal-footer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        const modalDemografi = document.getElementById('modal-demografi');
        const btnAnalisisDemografi = document.getElementById('btn-analisis-demografi');
        const btnCloseModal = document.getElementById('btn-close-modal');
        const btnCloseModalFooter = document.getElementById('btn-close-modal-footer');
        const modalOverlay = document.getElementById('modal-overlay');
        const chartTabs = document.querySelectorAll('.chart-tab');
        const chartWrappers = document.querySelectorAll('.chart-wrapper');

        let chartUsia = null;
        let chartGender = null;
        let chartAgama = null;

        // Open Modal
        btnAnalisisDemografi.addEventListener('click', function(e) {
            e.preventDefault();
            modalDemografi.classList.remove('hidden');
            setTimeout(() => {
                initCharts();
            }, 100);
        });

        // Close Modal Functions
        function closeModal() {
            modalDemografi.classList.add('hidden');
        }

        btnCloseModal.addEventListener('click', closeModal);
        btnCloseModalFooter.addEventListener('click', closeModal);
        modalOverlay.addEventListener('click', closeModal);

        // Close modal saat tekan ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modalDemografi.classList.contains('hidden')) {
                closeModal();
            }
        });

        // Chart Tab Switching
        chartTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const chartType = this.getAttribute('data-chart');

                // Remove active class dari semua tabs dan wrappers
                chartTabs.forEach(t => t.classList.remove('active'));
                chartWrappers.forEach(w => w.classList.remove('active'));

                // Add active class ke tab dan wrapper yang dipilih
                this.classList.add('active');
                document.getElementById(`chart-${chartType}`).classList.add('active');
            });
        });

        // Initialize Charts
        function initCharts() {
            // Destroy existing charts jika ada
            if (chartUsia) chartUsia.destroy();
            if (chartGender) chartGender.destroy();
            if (chartAgama) chartAgama.destroy();

            // Chart Usia (Pie Chart)
            const ctxUsia = document.getElementById('myChartUsia').getContext('2d');
            chartUsia = new Chart(ctxUsia, {
                type: 'doughnut',
                data: {
                    labels: [
                        '0-5 Tahun',
                        '6-12 Tahun',
                        '13-18 Tahun',
                        '19-35 Tahun',
                        '36-50 Tahun',
                        '51-65 Tahun',
                        '> 65 Tahun'
                    ],
                    datasets: [{
                        label: 'Jumlah Penduduk',
                        data: [145, 287, 312, 638, 452, 256, 112],
                        backgroundColor: [
                            '#FF6B6B',
                            '#4ECDC4',
                            '#45B7D1',
                            '#FFA07A',
                            '#98D8C8',
                            '#F7DC6F',
                            '#BB8FCE'
                        ],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                font: { size: 12, weight: 500 },
                                color: '#374151'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });

            // Chart Gender (Bar Chart)
            const ctxGender = document.getElementById('myChartGender').getContext('2d');
            chartGender = new Chart(ctxGender, {
                type: 'bar',
                data: {
                    labels: ['Laki-Laki', 'Perempuan'],
                    datasets: [{
                        label: 'Jumlah Penduduk',
                        data: [1247, 1300],
                        backgroundColor: [
                            '#4f46e5',
                            '#ec4899'
                        ],
                        borderRadius: 8,
                        borderSkipped: false,
                        borderColor: 'rgba(255, 255, 255, 0.3)',
                        borderWidth: 2
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Jumlah: ' + context.parsed.x + ' orang';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            max: 1500,
                            ticks: {
                                color: '#6b7280'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        y: {
                            ticks: {
                                color: '#6b7280',
                                font: { size: 12, weight: 500 }
                            }
                        }
                    }
                }
            });

            // Chart Agama (Horizontal Bar Chart)
            const ctxAgama = document.getElementById('myChartAgama').getContext('2d');
            chartAgama = new Chart(ctxAgama, {
                type: 'bar',
                data: {
                    labels: ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Kong Hu Cu'],
                    datasets: [{
                        label: 'Jumlah Penduduk',
                        data: [1456, 542, 287, 156, 98, 8],
                        backgroundColor: [
                            '#10b981',
                            '#3b82f6',
                            '#f59e0b',
                            '#ef4444',
                            '#8b5cf6',
                            '#ec4899'
                        ],
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Jumlah: ' + context.parsed.x + ' orang';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                color: '#6b7280'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        y: {
                            ticks: {
                                color: '#6b7280',
                                font: { size: 12, weight: 500 }
                            }
                        }
                    }
                }
            });
        }

        // Export Data
        document.getElementById('btn-export-data').addEventListener('click', function() {
            alert('📊 Fitur export data akan segera tersedia!');
        });
    </script>
</body>
</html>
