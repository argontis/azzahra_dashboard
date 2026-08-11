<!DOCTYPE html>
<html lang="en">
   <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        
        <!-- Security Headers -->
        <meta http-equiv="X-Content-Type-Options" content="nosniff">
        <meta http-equiv="X-Frame-Options" content="SAMEORIGIN">
        
        <!-- SEO -->
        <meta name="description" content="Dashboard Admin - Azzahra Computer Tegal">
        <meta name="author" content="LEFT4CODE">
        <meta name="robots" content="noindex, nofollow">
        
        <!-- Favicon -->
        <link href="{{ asset('assets/template/beck/dist/images/logo.svg') }}" rel="shortcut icon">
        
        <!-- Title -->
        <title>{{ $title ?? 'Dashboard' }} - Azzahra Computer</title>
        
        <!-- Preconnect -->
        <link rel="preconnect" href="https://cdn.jsdelivr.net">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        
        <!-- BEGIN: CSS Assets -->
        <link rel="stylesheet" href="{{ asset('assets/template/beck/dist/css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/file/alert/animet.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css') }}?v={{ time() }}">
        
        <!-- Dashboard CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}?v={{ time() }}">
        
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        
        <style>
            /* HARDCODED LAYOUT FIX */
            body.app { padding-left: 0 !important; margin: 0 !important; }
            .app-layout { padding-left: 0 !important; margin-left: 0 !important; }
            .main-content { 
                padding-left: 260px !important; 
                margin-left: 0 !important;
                width: 100% !important; 
                box-sizing: border-box !important; 
            }
            .sidebar { left: 0 !important; }
            @media (max-width: 1024px) {
                .main-content { padding-left: 0 !important; }
            }
        </style>
        
        <!-- JS Pola -->
         <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
         
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
        
        <!-- Feather Icons -->
        <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
        <!-- END: CSS Assets -->
    </head>

<body class="app">
    <!-- BEGIN: Preloader -->
    <div id="midone-preloader" style="position:fixed; top:0; left:0; width:100%; height:100%; background:white; z-index:9999; display:flex; justify-content:center; align-items:center; transition:opacity 0.5s ease;">
        <svg width="40" height="40" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" stroke="#4472C4">
            <style><![CDATA[.spinner_V8m1{transform-origin:center;animation:spinner_zKoa 2s linear infinite}.spinner_V8m1 circle{stroke-linecap:round;animation:spinner_YpZS 1.5s ease-in-out infinite}@keyframes spinner_zKoa{100%{transform:rotate(360deg)}}@keyframes spinner_YpZS{0%{stroke-dasharray:0 150;stroke-dashoffset:0}47.5%{stroke-dasharray:42 150;stroke-dashoffset:-16}95%,100%{stroke-dasharray:42 150;stroke-dashoffset:-59}}]]></style>
            <g class="spinner_V8m1"><circle cx="12" cy="12" r="9.5" fill="none" stroke-width="3"></circle></g>
        </svg>
    </div>
    <!-- END: Preloader -->
    <div class="app-layout">
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" id="mobileMenuBtn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </button>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
            <!-- Logo -->
            <div class="logo-section">
                <div class="logo-mark">
                    <img src="{{ asset('assets/image/logo.png') }}" alt="Logo">
                </div>
                <div class="logo-name">
                    <h1>Azzahra Computer</h1>
                    
                </div>
                <div class="sidebar-toggle" onclick="toggleSidebar()">
                    <i data-feather="chevron-left"></i>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="nav-menu">
                @php 
                    $level = Auth::check() ? Auth::user()->kry_level : ''; 
                    $title = $title ?? '';
                @endphp

                @if ($level == 'Customer Service')
                <div class="nav-group">
                    <div class="nav-group-title">Menu</div>
                    <a href="{{ route('service.index') }}" class="nav-link {{ $title == 'Dashboard' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="home"></i></div>
                        <span class="nav-text">Dashboard</span>
                    </a>
                    <a href="{{ route('service.antrean', 'baru') }}" class="nav-link {{ $title == 'Customer' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="users"></i></div>
                        <span class="nav-text">Customer</span>
                    </a>
                    <a href="{{ route('quickservice.index') }}" class="nav-link {{ $title == 'Quick Service' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="zap"></i></div>
                        <span class="nav-text">Quick Service</span>
                    </a>
                    <a href="{{ url('Service/pembayaran') }}" class="nav-link {{ $title == 'Pembayaran' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="credit-card"></i></div>
                        <span class="nav-text">Pembayaran</span>
                    </a>
                    <a href="{{ url('Service/laporan') }}" class="nav-link {{ $title == 'Laporan' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="activity"></i></div>
                        <span class="nav-text">Laporan</span>
                    </a>
                    <a href="{{ url('Admin/mou') }}" class="nav-link {{ $title == 'Mou' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="file-text"></i></div>
                        <span class="nav-text">Mou</span>
                    </a>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">Management E-Service</div>
                    <a href="{{ url('Admin/order') }}" class="nav-link {{ $title == 'Order' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="shopping-cart"></i></div>
                        <span class="nav-text">Order</span>
                    </a>
                    @php
                        // To be migrated: count sparepart
                        $count_sparepart = 0;
                    @endphp
                    <a href="{{ url('Admin/ketersediaan_sparepart') }}" class="nav-link {{ $title == 'Ketersediaan Sparepart' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="package"></i></div>
                        <span class="nav-text">Ketersediaan Sparepart
                            @if($count_sparepart > 0)
                                <span style="background:red;color:white;border-radius:10px;padding:2px 8px;font-size:12px;margin-left:5px;vertical-align:middle;">
                                    {{ $count_sparepart }}
                                </span>
                            @endif
                        </span>
                    </a>
                    <a href="{{ url('Admin/voucher') }}" class="nav-link {{ $title == 'Voucher Discount' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="percent"></i></div>
                        <span class="nav-text">Voucher Discount</span>
                    </a>
                </div>
                
                @elseif ($level == 'Kasir')
                <div class="nav-group">
                    <div class="nav-group-title">Menu</div>
                    <a href="{{ url('Kasir') }}" class="nav-link {{ $title == 'Customer' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="users"></i></div>
                        <span class="nav-text">Customer</span>
                    </a>
                    <a href="{{ route('quickservice.index') }}" class="nav-link {{ $title == 'Quick Service' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="zap"></i></div>
                        <span class="nav-text">Quick Service</span>
                    </a>
                    <a href="{{ url('Kasir/pembayaran') }}" class="nav-link {{ $title == 'Pembayaran' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="credit-card"></i></div>
                        <span class="nav-text">Pembayaran</span>
                    </a>
                    <a href="{{ url('Kasir/laporan') }}" class="nav-link {{ $title == 'Laporan' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="activity"></i></div>
                        <span class="nav-text">Laporan</span>
                    </a>
                    <a href="{{ url('Admin/mou') }}" class="nav-link {{ $title == 'Mou' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="file-text"></i></div>
                        <span class="nav-text">Mou</span>
                    </a>
                </div>
                
                @elseif ($level == 'Teknisi')
                <div class="nav-group">
                    <div class="nav-group-title">Menu Teknisi</div>
                    <a href="{{ url('Teknisi') }}" class="nav-link {{ $title == 'Dashboard Teknisi' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="home"></i></div>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </div>
                
                @elseif ($level == 'HR')
                 <div class="nav-group">
                        <div class="nav-group-title">Menu HR</div>
                        <a href="{{ url('HR') }}" class="nav-link {{ $title == 'HR Dashboard' ? 'active' : '' }}">
                                <div class="nav-icon"><i data-feather="home"></i></div>
                                <span class="nav-text">Overview</span>
                            </a>
                            <a href="{{ url('HR/karyawan') }}" class="nav-link {{ $title == 'Data Karyawan' ? 'active' : '' }}">
                                <div class="nav-icon"><i data-feather="users"></i></div>
                                <span class="nav-text">Karyawan</span>
                            </a>
                            <a href="{{ url('HR/absensi') }}" class="nav-link {{ $title == 'Absensi Karyawan' ? 'active' : '' }}">
                                <div class="nav-icon"><i data-feather="clock"></i></div>
                                <span class="nav-text">Absensi</span>
                            </a>
                            <a href="{{ url('HR/kpi') }}" class="nav-link {{ $title == 'KPI Karyawan' ? 'active' : '' }}">
                                <div class="nav-icon"><i data-feather="bar-chart-2"></i></div>
                                <span class="nav-text">KPI</span>
                            </a>
                            <a href="{{ url('HR/interview') }}" class="nav-link {{ $title == 'Interview Kandidat' ? 'active' : '' }}">
                                <div class="nav-icon"><i data-feather="user-check"></i></div>
                                <span class="nav-text">Interview</span>
                            </a>
                            <a href="{{ url('HR/pencatatan') }}" class="nav-link {{ $title == 'Pencatatan Barang' ? 'active' : '' }}">
                                <div class="nav-icon"><i data-feather="package"></i></div>
                                <span class="nav-text">Pencatatan Keuangan</span>
                            </a>
                            <a href="{{ url('HR/laporan_mingguan') }}" class="nav-link {{ $title == 'Laporan Mingguan' ? 'active' : '' }}">
                                <div class="nav-icon"><i data-feather="edit-3"></i></div>
                                <span class="nav-text">Laporan Mingguan</span>
                            </a>
                            <a href="{{ url('HR/rekap') }}" class="nav-link {{ $title == 'Rekap HR' ? 'active' : '' }}">
                                <div class="nav-icon"><i data-feather="file-text"></i></div>
                                <span class="nav-text">Rekap</span>
                            </a>
                            <a href="{{ url('HR/certificate_generator') }}" class="nav-link {{ $title == 'Generator Sertifikat' ? 'active' : '' }}">
                                <div class="nav-icon"><i data-feather="award"></i></div>
                                <span class="nav-text">Sertifikat</span>
                            </a>
                            <a href="{{ url('HR/arsip') }}" class="nav-link {{ $title == 'Arsip Dokumen' ? 'active' : '' }}">
                                <div class="nav-icon"><i data-feather="archive"></i></div>
                                <span class="nav-text">Arsip</span>
                            </a>
                             <a href="{{ url('Admin/mou') }}" class="nav-link {{ ($title == 'Mou' || $title == 'Rekap MOU') ? 'active' : '' }}">
                                <div class="nav-icon"><i data-feather="file-text"></i></div>
                                <span class="nav-text">Mou</span>
                            </a>
                        </div>
                @else
                <div class="nav-group">
                    <div class="nav-group-title">Menu</div>
                    <a href="{{ url('Admin') }}" class="nav-link {{ $title == 'Dashboard' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="home"></i></div>
                        <span class="nav-text">Dashboard</span>
                    </a>
                    <a href="{{ url('HR/karyawan') }}" class="nav-link {{ $title == 'Data Karyawan' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="user"></i></div>
                        <span class="nav-text">Karyawan</span>
                    </a>
                    <a href="{{ url('Customer') }}" class="nav-link {{ $title == 'Customer' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="users"></i></div>
                        <span class="nav-text">Customer</span>
                    </a>
                    <a role="button" class="nav-link laporan-toggle {{ $title == 'Laporan' ? 'active' : '' }}" onclick="toggleLaporanDropdown()">
                        <div class="nav-icon"><i data-feather="activity"></i></div>
                        <span class="nav-text">Laporan</span>
                        <div class="dropdown-chevron">
                            <i data-feather="chevron-up" class="w-4 h-4"></i>
                        </div>
                    </a>
                    <div class="nav-dropdown-menu" id="laporanDropdownMenu">
                        <a href="{{ url('Admin/lap_perhari') }}" class="dropdown-menu-item">
                            <div class="nav-icon"><i data-feather="book-open"></i></div>
                            <span class="nav-text">Harian</span>
                        </a>
                        <a href="{{ url('Admin/laporan') }}" class="dropdown-menu-item">
                            <div class="nav-icon"><i data-feather="book"></i></div>
                            <span class="nav-text">Bulanan</span>
                        </a>
                    </div>
                    <a href="{{ url('Admin/mou') }}" class="nav-link {{ $title == 'Mou' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="file-text"></i></div>
                        <span class="nav-text">Mou</span>
                    </a>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">Transaksi</div>
                    <a href="{{ url('Admin/cus_baru') }}" class="nav-link {{ $title == 'Transaksi-Baru' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="user-plus"></i></div>
                        <span class="nav-text">Baru</span>
                    </a>
                    <a href="{{ url('Admin/cus_konf_bank') }}" class="nav-link {{ $title == 'Transaksi-Bank Transfer' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="credit-card"></i></div>
                        <span class="nav-text">Bank Transfer</span>
                    </a>
                    <a href="{{ url('Admin/cus_proses') }}" class="nav-link {{ $title == 'Transaksi-Proses' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="play-circle"></i></div>
                        <span class="nav-text">Proses</span>
                    </a>

                    @php
                        // To be migrated: count transaksi diproses
                        $konf = 0;
                    @endphp
                    <a href="{{ url('Admin/cus_konf') }}" class="nav-link {{ $title == 'Transaksi-Konfirmasi' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="bell"></i></div>
                        <span class="nav-text">Konfirmasi</span>
                        @if($konf > 0)
                            <span class="nav-badge">{{ $konf }}</span>
                        @endif
                    </a>
                     <a href="{{ url('Admin/voucher') }}" class="nav-link {{ $title == 'Discount' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="message-square"></i></div>
                        <span class="nav-text">Discount</span>
                    </a>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">Management E-Service</div>                   
                    <a href="{{ url('Produk') }}" class="nav-link {{ $title == 'Produk' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="package"></i></div>
                        <span class="nav-text">Produk</span>
                    </a>                    
                    <a href="{{ url('Admin/order') }}" class="nav-link {{ $title == 'Order' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="shopping-cart"></i></div>
                        <span class="nav-text">Order</span>
                    </a>
                    <a role="button" class="nav-link order-approval-toggle {{ str_contains($title ?? '', 'Order Approval') ? 'active' : '' }}" onclick="document.getElementById('orderApprovalDropdownMenu').classList.toggle('active'); this.classList.toggle('active');">
                        <div class="nav-icon"><i data-feather="check-circle"></i></div>
                        <span class="nav-text">Order Approval</span>
                        <div class="dropdown-chevron"><i data-feather="chevron-down" class="w-4 h-4"></i></div>
                    </a>
                    <div class="nav-dropdown-menu" id="orderApprovalDropdownMenu" style="{{ str_contains($title ?? '', 'Order Approval') ? 'display:block;' : '' }}">
                        <a href="{{ route('admin.order_approval.oow') }}" class="dropdown-menu-item">
                            <div class="nav-icon"><i data-feather="corner-down-right"></i></div>
                            <span class="nav-text">Out of Warranty</span>
                        </a>
                        <a href="{{ route('admin.order_approval.iw') }}" class="dropdown-menu-item">
                            <div class="nav-icon"><i data-feather="corner-down-right"></i></div>
                            <span class="nav-text">In Warranty</span>
                        </a>
                    </div>
                    <a href="{{ url('Admin/voucher') }}" class="nav-link {{ $title == 'Voucher Discount' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="percent"></i></div>
                        <span class="nav-text">Voucher Discount</span>
                    </a>
                    @php
                        // To be migrated
                        $count_sparepart = 0;
                    @endphp
                    <a href="{{ url('Admin/ketersediaan_sparepart') }}" class="nav-link {{ $title == 'Ketersediaan Sparepart' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="package"></i></div>
                        <span class="nav-text">Ketersediaan Sparepart
                            @if($count_sparepart > 0)
                                <span style="background:red;color:white;border-radius:10px;padding:2px 8px;font-size:12px;margin-left:5px;vertical-align:middle;">
                                    {{ $count_sparepart }}
                                </span>
                            @endif
                        </span>
                    </a>
                </div>
                @endif
            </nav>

            <!-- Footer -->
            <div class="sidebar-footer">
                <div class="user-profile" onclick="toggleUserDropdown()">
                    <div class="user-avatar">{{ Auth::check() ? strtoupper(substr(Auth::user()->kry_nama, 0, 1)) : '' }}</div>
                    <div class="user-info">
                        <h4>{{ Auth::check() ? Auth::user()->kry_nama : '' }}</h4>
                        <p>{{ Auth::check() ? Auth::user()->kry_level : '' }}</p>
                    </div>
                    <div class="dropdown-chevron">
                        <i data-feather="chevron-up" class="w-4 h-4"></i>
                    </div>
                </div>
                
                <!-- Dropdown Menu -->
                <div class="user-dropdown-menu" id="userDropdownMenu">
                    @if ($level != 'Customer Service' && $level != 'Kasir' && $level != 'Teknisi')
                    <a href="{{ url('HR/karyawan') }}" class="dropdown-menu-item">
                        <div class="nav-icon"><i data-feather="user-plus"></i></div>
                        <span class="nav-text">Add Account</span>
                    </a>
                    @endif
                    <a href="{{ url('Auth/reset') }}" class="dropdown-menu-item">
                        <div class="nav-icon"><i data-feather="lock"></i></div>
                        <span class="nav-text">Reset Password</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="dropdown-menu-item logout">
                            <div class="nav-icon"><i data-feather="log-out"></i></div>
                            <span class="nav-text">Logout</span>
                        </a>
                    </form>
                </div>
            </div>
        </aside>
        
    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- JavaScript for Sidebar Toggle -->
    <script>
        // Function to toggle sidebar collapse
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }

        // Function to toggle mobile sidebar
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('mobile-active');
            overlay.classList.toggle('active');
        }

        // Function to close mobile sidebar
        function closeMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.remove('mobile-active');
            overlay.classList.remove('active');
        }

        // Function to toggle laporan dropdown
        function toggleLaporanDropdown() {
            const dropdown = document.getElementById('laporanDropdownMenu');
            const toggle = document.querySelector('.laporan-toggle');
            dropdown.classList.toggle('active');
            toggle.classList.toggle('active');
        }

        // Function to toggle user dropdown
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdownMenu');
            const profile = document.querySelector('.user-profile');
            dropdown.classList.toggle('active');
            profile.classList.toggle('active');
        }

        // Hide preloader on load
        window.addEventListener('load', function() {
            setTimeout(function() {
                var preloader = document.getElementById('midone-preloader');
                if(preloader) {
                    preloader.style.opacity = '0';
                    setTimeout(function() { preloader.style.display = 'none'; }, 500);
                }
            }, 300); // Add a tiny delay for visual effect
        });

        // Initialize Feather Icons
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
            
    <!-- BEGIN: JS Assets-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/template/beck/dist/js/app.js') }}"></script>
    <!-- SweetAlert -->
    <script src="{{ asset('assets/file/alert/alertscript.js') }}"></script>
    <!-- myjs -->
    <script src="{{ asset('assets/file/js/rupiah.js') }}"></script>
    <script src="{{ asset('assets/file/js/modal.js') }}"></script>
    
    @if(session('just_logged_in'))
    <script>
        sessionStorage.setItem('just_logged_in', 'true');
        {{ session()->forget('just_logged_in') }}
    </script>
    @endif

    <script>
        // Init Feather Icons
        if (typeof feather !== 'undefined') {
            feather.replace();
            
            // Re-init setiap 1 detik untuk icon yang dimuat via AJAX
            setInterval(function() {
                feather.replace();
            }, 1000);
        }
    </script>
    <!-- END: JS Assets-->
     
    <!-- Dashboard Scripts -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Feather Icons
        feather.replace();
        
        // Initialize Weekly Chart (only if not already initialized by page-specific script)
        const ctx = document.getElementById('weeklyChart');
        if (ctx && !ctx.chart) {
            const weekly_labels = ["'Mon'", "'Tue'", "'Wed'", "'Thu'", "'Fri'", "'Sat'", "'Sun'"];
            const weekly_data = [12, 19, 15, 25, 22, 30, 28];

            ctx.chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: weekly_labels,                            
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            borderRadius: 8,
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
        
        // Animate traffic progress bars
        setTimeout(() => {
            document.querySelectorAll('.traffic-progress-bar').forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => bar.style.width = width, 100);
            });
        }, 500);
        
        // Filter buttons functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });
    });
    </script>

    <!-- Script for Sidebar and User Dropdown -->
    <script>
        // Toggle User Dropdown
        function toggleUserDropdown() {
            const userProfile = document.querySelector('.user-profile');
            const dropdownMenu = document.getElementById('userDropdownMenu');

            if (userProfile) userProfile.classList.toggle('active');
            if (dropdownMenu) dropdownMenu.classList.toggle('active');

            // Refresh feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }

        // Toggle Laporan Dropdown
        function toggleLaporanDropdown() {
            const laporanToggle = document.querySelector('.laporan-toggle');
            const dropdownMenu = document.getElementById('laporanDropdownMenu');

            if (laporanToggle) laporanToggle.classList.toggle('active');
            if (dropdownMenu) dropdownMenu.classList.toggle('active');

            // Refresh feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const sidebarFooter = document.querySelector('.sidebar-footer');
            const userProfile = document.querySelector('.user-profile');
            const userDropdownMenu = document.getElementById('userDropdownMenu');

            if (sidebarFooter && !sidebarFooter.contains(event.target)) {
                if (userProfile) userProfile.classList.remove('active');
                if (userDropdownMenu) userDropdownMenu.classList.remove('active');
            }
        });

        // Close laporan dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const navMenu = document.querySelector('.nav-menu');
            const laporanToggle = document.querySelector('.laporan-toggle');
            const laporanDropdownMenu = document.getElementById('laporanDropdownMenu');

            if (!navMenu || !laporanToggle || !laporanDropdownMenu) return;

            if (!navMenu.contains(event.target)) {
                laporanToggle.classList.remove('active');
                laporanDropdownMenu.classList.remove('active');
            }
        });

        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const userProfile = document.querySelector('.user-profile');
            const userDropdownMenu = document.getElementById('userDropdownMenu');
            const laporanToggle = document.querySelector('.laporan-toggle');
            const laporanDropdownMenu = document.getElementById('laporanDropdownMenu');

            if (sidebar) sidebar.classList.toggle('collapsed');

            // Close dropdown when collapsing sidebar
            if (sidebar && sidebar.classList.contains('collapsed')) {
                if (userProfile) userProfile.classList.remove('active');
                if (userDropdownMenu) userDropdownMenu.classList.remove('active');
                if (laporanToggle) laporanToggle.classList.remove('active');
                if (laporanDropdownMenu) laporanDropdownMenu.classList.remove('active');
            }

            if (sidebar) localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }

        // Toggle Mobile Sidebar
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar) sidebar.classList.toggle('mobile-active');
            if (overlay) overlay.classList.toggle('active');
        }

        // Remember sidebar state
        window.addEventListener('DOMContentLoaded', () => {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            const sidebar = document.getElementById('sidebar');
            if (isCollapsed && window.innerWidth > 1024 && sidebar) {
                sidebar.classList.add('collapsed');
            }

            // Initialize feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
</body>
</html>
