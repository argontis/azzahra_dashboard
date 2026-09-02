@extends('layouts.app')

@section('content')
<header class="page-header">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="percent" class="w-5 h-5 inline-block mr-2"></i>Voucher Discount</h1>
        <p>Manage dan pantau data voucher diskon pelanggan</p>
    </div>
    
    <div class="header-actions">
        <a href="{{ route('admin.voucher.add') }}" class="button text-white bg-blue-600 shadow-sm mr-2 hover:bg-blue-700 transition-all px-3 py-2 rounded-lg text-xs font-semibold flex items-center gap-1">
            <i data-feather="plus" class="w-4 h-4"></i> Tambah Voucher
        </a>
        <div class="search-input-wrapper">
            <i data-feather="search" class="search-icon"></i>
            <input type="text" class="search-input" id="topbar-search-input" placeholder="Search...">
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

<!-- BEGIN: Content -->
<div class="content-area">
    @if(session('sukses'))
    <div id="success-alert" class="bg-emerald-500 text-white p-4 rounded-xl shadow-md mb-5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <i data-feather="check-circle" class="w-5 h-5 text-white"></i>
            <span class="font-medium text-sm">{{ session('sukses') }}</span>
        </div>
        <button class="text-white opacity-80 hover:opacity-100" onclick="document.getElementById('success-alert').remove()">
            <i data-feather="x" class="w-4 h-4"></i>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div id="error-alert" class="bg-rose-500 text-white p-4 rounded-xl shadow-md mb-5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <i data-feather="alert-circle" class="w-5 h-5 text-white"></i>
            <span class="font-medium text-sm">{{ session('error') }}</span>
        </div>
        <button class="text-white opacity-80 hover:opacity-100" onclick="document.getElementById('error-alert').remove()">
            <i data-feather="x" class="w-4 h-4"></i>
        </button>
    </div>
    @endif

    <!-- BEGIN: Search & Action Box -->
    <div class="intro-y box p-4 bg-white rounded-xl shadow-sm border border-gray-100 mb-5">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="relative w-full sm:w-72">
                <input
                    type="text"
                    id="search-input"
                    class="input w-full pl-9 pr-10 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all"
                    placeholder="Cari voucher..."
                    autocomplete="off"
                >
                <i data-feather="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" id="search-icon"></i>
                <div class="hidden w-4 h-4 absolute right-3 top-1/2 transform -translate-y-1/2" id="search-spinner">
                    <svg class="animate-spin h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.voucher.add') }}" class="button bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold text-xs flex items-center gap-1.5 shadow-sm transition-all">
                    <i data-feather="plus" class="w-4 h-4"></i> Tambah Voucher
                </a>
            </div>
        </div>
    </div>
    <!-- END: Search Box -->

    <!-- BEGIN: Data List Container -->
    <div class="intro-y col-span-12" style="position: relative;">
        <!-- Loading Overlay -->
        <div id="loading-overlay" class="hidden absolute inset-0 bg-white/80 backdrop-blur-xs flex items-center justify-center z-10 rounded-xl" style="min-height: 200px;">
            <div class="flex flex-col items-center">
                <svg class="animate-spin h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-2 text-gray-500 text-xs font-semibold">Memuat data...</p>
            </div>
        </div>

        <!-- Table Container -->
        <div id="table-container" class="w-full bg-white rounded-xl shadow-sm border border-gray-100 p-4 overflow-x-auto">
            @include('voucher.ajax_table')
        </div>
    </div>
</div>

<script>
let searchTimeout;
let currentSearch = '';
let currentPage = 1;

// Load data AJAX function
function loadData(page = 1, search = '') {
    currentPage = page;
    const url = '{{ route("admin.voucher.search") }}?search=' + encodeURIComponent(search) + '&page=' + page;

    const loadingOverlay = document.getElementById('loading-overlay');
    const tableContainer = document.getElementById('table-container');

    if (loadingOverlay) loadingOverlay.classList.remove('hidden');

    fetch(url)
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.text();
        })
        .then(html => {
            if (tableContainer) {
                tableContainer.innerHTML = html;
            }
            if (loadingOverlay) loadingOverlay.classList.add('hidden');

            const spinner = document.getElementById('search-spinner');
            const icon = document.getElementById('search-icon');
            if (spinner) spinner.classList.add('hidden');
            if (icon) icon.classList.remove('hidden');

            if (typeof attachVoucherTableEvents === 'function') {
                attachVoucherTableEvents();
            }
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        })
        .catch(error => {
            console.error('Error loading vouchers:', error);
            if (loadingOverlay) loadingOverlay.classList.add('hidden');
            const spinner = document.getElementById('search-spinner');
            const icon = document.getElementById('search-icon');
            if (spinner) spinner.classList.add('hidden');
            if (icon) icon.classList.remove('hidden');
        });
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const topbarSearchInput = document.getElementById('topbar-search-input');

    function triggerSearch(val) {
        clearTimeout(searchTimeout);
        currentSearch = val;

        const spinner = document.getElementById('search-spinner');
        const icon = document.getElementById('search-icon');
        if (spinner) spinner.classList.remove('hidden');
        if (icon) icon.classList.add('hidden');

        searchTimeout = setTimeout(() => {
            loadData(1, currentSearch);
        }, 400);
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            triggerSearch(this.value);
        });
    }

    if (topbarSearchInput) {
        topbarSearchInput.addEventListener('input', function() {
            if (searchInput) searchInput.value = this.value;
            triggerSearch(this.value);
        });
    }

    // Intercept pagination clicks
    document.addEventListener('click', function(e) {
        const paginationLink = e.target.closest('.pagination a, .pagination-wrapper-custom a');
        if (paginationLink) {
            e.preventDefault();
            const href = paginationLink.getAttribute('href');
            if (href) {
                const urlParams = new URLSearchParams(href.split('?')[1]);
                const page = urlParams.get('page') || 1;
                loadData(page, currentSearch);
            }
        }
    });

    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endsection
