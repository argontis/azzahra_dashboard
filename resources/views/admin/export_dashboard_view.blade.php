<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Dashboard - Azzahra Computer</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Kustomisasi Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Animasi Transisi Card */
        .hover-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .hover-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.1); border-color: #e2e8f0; }
    </style>
</head>

<!-- Background diubah ke warna Soft Blue-Grey (#f0f4f8) yang sangat nyaman untuk mata -->
<body class="flex h-screen overflow-hidden text-slate-700 antialiased bg-[#f0f4f8]">

    <!-- Sidebar Kiri (Putih Bersih) -->
    <aside class="w-20 bg-white border-r border-slate-200/60 flex flex-col items-center py-6 z-20 flex-shrink-0 shadow-sm">
        <!-- Logo -->
        <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-md shadow-blue-500/30 mb-8 cursor-pointer hover:scale-105 transition-transform">
            <i data-feather="cpu" class="w-6 h-6"></i>
        </div>
        
        <!-- Menu Icons -->
        <nav class="flex flex-col gap-4 w-full px-3">
            <a href="#" class="p-3 bg-blue-50 text-blue-600 rounded-xl flex justify-center relative group">
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-8 bg-blue-600 rounded-r-full"></div>
                <i data-feather="grid" class="w-5 h-5"></i>
            </a>
            <a href="#" class="p-3 text-slate-400 hover:text-blue-600 hover:bg-slate-50 rounded-xl transition-colors flex justify-center"><i data-feather="users" class="w-5 h-5"></i></a>
            <a href="#" class="p-3 text-slate-400 hover:text-blue-600 hover:bg-slate-50 rounded-xl transition-colors flex justify-center"><i data-feather="tool" class="w-5 h-5"></i></a>
            <a href="#" class="p-3 text-slate-400 hover:text-blue-600 hover:bg-slate-50 rounded-xl transition-colors flex justify-center"><i data-feather="pie-chart" class="w-5 h-5"></i></a>
            <a href="#" class="p-3 text-slate-400 hover:text-blue-600 hover:bg-slate-50 rounded-xl transition-colors flex justify-center mt-auto"><i data-feather="settings" class="w-5 h-5"></i></a>
        </nav>
    </aside>

    <!-- Konten Utama -->
    <div class="flex-1 flex flex-col overflow-hidden relative">
        
        <!-- Topbar Solid Biru (Sama dengan gambar referensi) -->
        <header class="h-16 bg-blue-600 flex items-center justify-between px-8 z-10 sticky top-0 shadow-md">
            <div class="flex items-center gap-4">
                <h1 class="text-lg font-semibold text-white tracking-wide">Dashboard Overview</h1>
            </div>
            
            <div class="flex items-center gap-6">
                <!-- Search Bar -->
                <div class="relative hidden md:block">
                    <i data-feather="search" class="w-4 h-4 text-blue-200 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input type="text" placeholder="Cari transaksi, customer..." class="pl-10 pr-4 py-2 bg-blue-700/50 border-transparent rounded-full text-sm text-white placeholder-blue-200 focus:bg-white focus:text-slate-800 focus:placeholder-slate-400 outline-none transition-all w-64 shadow-inner">
                </div>
                
                <!-- Notifikasi -->
                <button class="relative p-2 text-blue-100 hover:text-white transition-colors">
                    <i data-feather="bell" class="w-5 h-5"></i>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-rose-500 rounded-full border border-blue-600"></span>
                </button>
                
                <!-- Profile -->
                <div class="flex items-center gap-3 pl-4 border-l border-blue-500/50 cursor-pointer hover:opacity-80 transition">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-white">Admin Azzahra</p>
                        <p class="text-xs text-blue-200">Super Admin</p>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=Admin+Azzahra&background=ffffff&color=2563eb&bold=true" alt="Profile" class="w-9 h-9 rounded-full border-2 border-blue-400">
                </div>
            </div>
        </header>

        <!-- Area Scroll Content -->
        <main class="flex-1 overflow-y-auto p-6 md:p-8">
            
            <!-- Greeting & Filter -->
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-800 mb-1">Selamat Datang, Admin 👋</h2>
                    <p class="text-sm text-slate-500">Berikut adalah ringkasan performa sistem hari ini.</p>
                </div>
                
                <!-- Navigasi Tabs (Pill Style) -->
                <div class="bg-white p-1 rounded-xl inline-flex text-sm font-medium border border-slate-200 shadow-sm">
                    <button class="px-5 py-2 bg-blue-50 text-blue-600 rounded-lg shadow-sm border border-blue-100/50">Overview</button>
                    <button class="px-5 py-2 text-slate-500 hover:text-slate-700 rounded-lg transition-colors">Laporan</button>
                    <button class="px-5 py-2 text-slate-500 hover:text-slate-700 rounded-lg transition-colors">Karyawan</button>
                </div>
            </div>

            <!-- Grid KPI Baris 1 -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Card 1: Revenue -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover-card group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-semibold text-slate-500 mb-1">Pendapatan Hari Ini</p>
                            <h3 class="text-2xl font-extrabold text-slate-800 group-hover:text-blue-600 transition-colors">Rp 3.5M</h3>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                            <i data-feather="dollar-sign"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded-md flex items-center gap-1"><i data-feather="trending-up" class="w-3 h-3"></i> 12%</span>
                        <span class="text-slate-400 ml-2 font-medium">vs kemarin</span>
                    </div>
                </div>

                <!-- Card 2: Service Done -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover-card group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-semibold text-slate-500 mb-1">Service Selesai</p>
                            <h3 class="text-3xl font-extrabold text-slate-800 group-hover:text-emerald-500 transition-colors">36</h3>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500">
                            <i data-feather="check-circle"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded-md flex items-center gap-1"><i data-feather="trending-up" class="w-3 h-3"></i> 5%</span>
                        <span class="text-slate-400 ml-2 font-medium">vs kemarin</span>
                    </div>
                </div>

                <!-- Card 3: Pending -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover-card group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-semibold text-slate-500 mb-1">Menunggu Konfirmasi</p>
                            <h3 class="text-3xl font-extrabold text-slate-800 group-hover:text-amber-500 transition-colors">14</h3>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500">
                            <i data-feather="clock"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="bg-rose-100 text-rose-700 font-bold px-2 py-0.5 rounded-md flex items-center gap-1"><i data-feather="alert-circle" class="w-3 h-3"></i> Segera</span>
                        <span class="text-slate-400 ml-2 font-medium">butuh tindakan</span>
                    </div>
                </div>

                <!-- Card 4: Customers -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover-card group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-semibold text-slate-500 mb-1">Customer Baru</p>
                            <h3 class="text-3xl font-extrabold text-slate-800 group-hover:text-indigo-500 transition-colors">8</h3>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-500">
                            <i data-feather="user-plus"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded-md flex items-center gap-1"><i data-feather="trending-up" class="w-3 h-3"></i> 2%</span>
                        <span class="text-slate-400 ml-2 font-medium">vs kemarin</span>
                    </div>
                </div>
            </div>

            <!-- Grid Charts Baris 2 -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Line Chart (Kiri) -->
                <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-slate-800">Tren Transaksi & Service</h3>
                        <button class="text-sm text-blue-600 font-semibold hover:text-blue-700">Lihat Detail &rarr;</button>
                    </div>
                    <div class="h-72">
                        <canvas id="mainChart"></canvas>
                    </div>
                </div>
                
                <!-- Doughnut Chart (Kanan) -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col">
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Metode Pembayaran</h3>
                    <p class="text-sm text-slate-500 mb-6">Distribusi transaksi bulan ini.</p>
                    
                    <div class="flex-1 flex items-center justify-center relative">
                        <div class="w-48 h-48 relative">
                            <canvas id="doughnutChart"></canvas>
                            <!-- Teks di tengah Donut -->
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <span class="text-2xl font-black text-slate-800">142</span>
                                <span class="text-xs font-semibold text-slate-400">Total</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Legend Custom -->
                    <div class="mt-6 flex justify-center gap-4 text-sm font-medium">
                        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-500"></span> BCA</div>
                        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-emerald-400"></span> Tunai</div>
                        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-amber-400"></span> BRI</div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- Inisialisasi Script -->
    <script>
        // Aktifkan Feather Icons
        feather.replace();

        // Konfigurasi Default Font Chart.js
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#64748b';

        // 1. LINE CHART (Tren Transaksi)
        const ctxMain = document.getElementById('mainChart').getContext('2d');
        
        // Membuat efek Gradient di bawah garis
        let gradientBlue = ctxMain.createLinearGradient(0, 0, 0, 300);
        gradientBlue.addColorStop(0, 'rgba(37, 99, 235, 0.2)');
        gradientBlue.addColorStop(1, 'rgba(37, 99, 235, 0)');

        new Chart(ctxMain, {
            type: 'line',
            data: {
                labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                datasets: [{
                    label: 'Total Transaksi',
                    data: [12, 19, 15, 25, 22, 30, 28],
                    borderColor: '#2563eb', // Blue 600
                    backgroundColor: gradientBlue,
                    borderWidth: 3,
                    tension: 0.4, // Garis melengkung halus
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 14 },
                        displayColors: false,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        border: { display: false },
                        grid: { color: '#f1f5f9' }
                    },
                    x: { 
                        border: { display: false },
                        grid: { display: false }
                    }
                }
            }
        });

        // 2. DOUGHNUT CHART (Metode Pembayaran)
        const ctxDoughnut = document.getElementById('doughnutChart').getContext('2d');
        new Chart(ctxDoughnut, {
            type: 'doughnut',
            data: {
                labels: ['BCA', 'Tunai', 'BRI', 'Mandiri'],
                datasets: [{
                    data: [55, 30, 10, 5],
                    backgroundColor: [
                        '#3b82f6', // Blue
                        '#34d399', // Emerald
                        '#fbbf24', // Amber
                        '#818cf8'  // Violet
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%', // Membuat lubang tengah lebih besar
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        cornerRadius: 8,
                    }
                }
            }
        });
    </script>
</body>
</html>