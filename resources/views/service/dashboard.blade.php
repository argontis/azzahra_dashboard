@extends('layouts.app')

@section('content')
<!-- Header Area -->
<header class="page-header">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="home" class="w-6 h-6 inline-block mr-2"></i>Dashboard</h1>
        <p>Welcome back, here's your business overview</p>
    </div>
    
    <div class="header-actions">
        <div class="search-input-wrapper">
            <i data-feather="search" class="search-icon"></i>
            <input type="text" class="search-input" placeholder="Search...">
        </div>
        <div class="header-btn header-btn-bell" id="topbar-bell-btn" title="Pemberitahuan Sistem & Maintenance" style="cursor: pointer; position: relative;">
            <i data-feather="bell"></i>
            <div class="badge-dot"></div>
        </div>
        <div class="header-btn header-btn-mail" id="topbar-mail-btn" title="Kotak Pesan" style="cursor: pointer; position: relative;">
            <i data-feather="mail"></i>
        </div>
    </div>
</header>

<div class="content">
    <div class="p-6 md:p-8 bg-gray-50/50 min-h-screen">
        <!-- General Report Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-gray-700">General Report</h2>
            <a href="{{ route('service.index') }}" class="flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                <i data-feather="refresh-ccw" class="w-4 h-4 mr-2"></i> Reload Data
            </a>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Transaksi Baru -->
            <a href="{{ route('service.antrean', 'baru') }}" class="block">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow h-full flex flex-col">
                    <div class="flex justify-between items-start mb-4">
                        <i data-feather="user-plus" class="w-6 h-6 text-blue-500"></i>
                        <div class="w-3 h-1 bg-blue-500 rounded-full"></div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-2">{{ $stats['baru'] }}</div>
                    <div class="text-sm text-gray-400 mt-auto">Transaksi Baru</div>
                </div>
            </a>

            <!-- Transaksi Diproses -->
            <a href="{{ route('service.antrean', 'proses') }}" class="block">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow h-full flex flex-col">
                    <div class="flex justify-between items-start mb-4">
                        <i data-feather="user-check" class="w-6 h-6 text-green-500"></i>
                        <div class="w-3 h-1 bg-green-500 rounded-full"></div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-2">{{ $stats['proses'] }}</div>
                    <div class="text-sm text-gray-400 mt-auto">Transaksi diproses</div>
                </div>
            </a>

            <!-- Konfirmasi -->
            <a href="{{ route('service.antrean', 'konfirmasi') }}" class="block">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow h-full flex flex-col">
                    <div class="flex justify-between items-start mb-4">
                        <i data-feather="phone-call" class="w-6 h-6 text-gray-400"></i>
                        <div class="w-3 h-1 bg-gray-400 rounded-full"></div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-2">{{ $stats['konfirmasi'] }}</div>
                    <div class="text-sm text-gray-400 mt-auto">Konfirmasi</div>
                </div>
            </a>

            <!-- Pelunasan -->
            <a href="{{ route('service.antrean', 'pelunasan') }}" class="block">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow h-full flex flex-col">
                    <div class="flex justify-between items-start mb-4">
                        <i data-feather="credit-card" class="w-6 h-6 text-red-500"></i>
                        <div class="w-3 h-1 bg-red-500 rounded-full"></div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-2">{{ $stats['pelunasan'] }}</div>
                    <div class="text-sm text-gray-400 mt-auto">Pelunasan</div>
                </div>
            </a>

            <!-- DP Non Tunai -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-full flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <i data-feather="credit-card" class="w-6 h-6 text-orange-400"></i>
                    <div class="w-3 h-1 bg-orange-400 rounded-full"></div>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-2">Rp. 0,-</div>
                <div class="text-sm text-gray-400 mt-auto">DP Non Tunai</div>
            </div>

            <!-- DP Tunai -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-full flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <i data-feather="dollar-sign" class="w-6 h-6 text-blue-600"></i>
                    <div class="w-3 h-1 bg-blue-600 rounded-full"></div>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-2">Rp. 0,-</div>
                <div class="text-sm text-gray-400 mt-auto">DP Tunai</div>
            </div>

            <!-- BANK BCA -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-full flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <i data-feather="layers" class="w-6 h-6 text-indigo-900"></i>
                    <div class="w-3 h-1 bg-indigo-900 rounded-full"></div>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-2">Rp. 0,-</div>
                <div class="text-sm text-gray-400 mt-auto uppercase">BANK BCA</div>
            </div>

            <!-- BANK MANDIRI -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-full flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <i data-feather="layers" class="w-6 h-6 text-slate-500"></i>
                    <div class="w-3 h-1 bg-slate-500 rounded-full"></div>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-2">Rp. 0,-</div>
                <div class="text-sm text-gray-400 mt-auto uppercase">BANK MANDIRI</div>
            </div>

        </div>
    </div>
</div>
@endsection
