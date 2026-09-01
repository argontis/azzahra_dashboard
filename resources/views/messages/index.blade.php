@extends('layouts.app')
@section('content')
<style>
.msg-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    padding: 20px 24px;
    margin-bottom: 16px;
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}
.msg-card:hover {
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}
.msg-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}
.msg-badge.all    { background: #ede9fe; color: #7c3aed; }
.msg-badge.Admin  { background: #fee2e2; color: #dc2626; }
.msg-badge.HR     { background: #fef3c7; color: #b45309; }
.msg-badge.Kasir  { background: #d1fae5; color: #059669; }
.msg-badge.Teknisi { background: #dbeafe; color: #1d4ed8; }
.msg-badge.Customer.Service { background: #fce7f3; color: #be185d; }
</style>

<!-- Header -->
<header class="page-header">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="mail" class="w-6 h-6 inline-block mr-2"></i>Kotak Pesan</h1>
        <p>Pesan Internal Karyawan</p>
    </div>
    <div class="header-actions">
        <div class="search-input-wrapper">
            <i data-feather="search" class="search-icon"></i>
            <input type="text" class="search-input" placeholder="Search...">
        </div>
    </div>
</header>

<div class="content-area">
    @if (session('sukses'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg border border-green-200">
            <span class="font-medium">Sukses!</span> {{ session('sukses') }}
        </div>
    @endif

    <div class="grid grid-cols-12 gap-6">
        <!-- Form Kirim Pesan -->
        <div class="col-span-12 lg:col-span-4">
            <div class="box p-6 bg-white rounded-xl border border-gray-100 shadow-sm">
                <h2 class="font-bold text-lg mb-4 text-gray-800 flex items-center gap-2">
                    <i data-feather="send" class="w-5 h-5 text-blue-600"></i> Kirim Pesan Baru
                </h2>
                <form action="{{ route('messages.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Kirim Ke</label>
                        <select name="target_role" class="input w-full border rounded-lg p-2 text-sm" required>
                            <option value="all">📢 Semua Karyawan</option>
                            <option value="Admin">👑 Admin</option>
                            <option value="HR">🧑‍💼 HR</option>
                            <option value="Kasir">💰 Kasir</option>
                            <option value="Teknisi">🔧 Teknisi</option>
                            <option value="Customer Service">🎧 Customer Service</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Judul Pesan</label>
                        <input type="text" name="judul" class="input w-full border rounded-lg p-2 text-sm" placeholder="Judul pesan..." required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Isi Pesan</label>
                        <textarea name="isi" rows="5" class="input w-full border rounded-lg p-2 text-sm" placeholder="Tulis pesan di sini..." required></textarea>
                    </div>
                    <button type="submit" class="button bg-theme-1 text-white w-full py-2.5 rounded-lg font-semibold text-sm shadow flex items-center justify-center gap-2">
                        <i data-feather="send" class="w-4 h-4"></i> Kirim Pesan
                    </button>
                </form>
            </div>
        </div>

        <!-- Daftar Pesan -->
        <div class="col-span-12 lg:col-span-8">
            <div class="box p-6 bg-white rounded-xl border border-gray-100 shadow-sm">
                <h2 class="font-bold text-lg mb-4 text-gray-800 flex items-center gap-2">
                    <i data-feather="inbox" class="w-5 h-5 text-indigo-600"></i> Pesan Masuk
                    <span class="text-sm font-normal text-gray-400">({{ $messages->count() }} pesan)</span>
                </h2>

                @forelse($messages as $msg)
                <div class="msg-card">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-gray-800 text-sm">{{ $msg->judul }}</span>
                                <span class="msg-badge {{ str_replace(' ', '.', $msg->target_role) }}">
                                    {{ $msg->target_role === 'all' ? '📢 Semua' : $msg->target_role }}
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm leading-relaxed">{{ $msg->isi }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr($msg->sender_nama, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-gray-700">{{ $msg->sender_nama }}</div>
                                <div class="text-xs text-gray-400">{{ $msg->sender_level }}</div>
                            </div>
                        </div>
                        <div class="text-xs text-gray-400">
                            {{ $msg->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-16 text-gray-400">
                    <i data-feather="inbox" class="w-12 h-12 mx-auto mb-3 opacity-30"></i>
                    <p class="font-medium">Belum ada pesan masuk</p>
                    <p class="text-xs mt-1">Kirim pesan pertama menggunakan form di sebelah kiri</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
