@extends('layouts.app')

@section('content')
<!-- Header -->
<header class="page-header mb-5">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="bell" class="w-6 h-6 inline-block mr-2 text-yellow-400"></i>Pemberitahuan Sistem</h1>
        <p>Pusat Informasi &amp; Jadwal Maintenance Resmi dari Administrator</p>
    </div>
    <div class="header-actions">
        <div class="search-input-wrapper">
            <i data-feather="search" class="search-icon"></i>
            <input type="text" id="filterAnnounceSearch" class="search-input" placeholder="Cari pemberitahuan..." onkeyup="filterAnnouncements()">
        </div>
        <div class="header-btn header-btn-bell" id="topbar-bell-btn" title="Pemberitahuan Sistem &amp; Maintenance" style="cursor: pointer; position: relative;">
            <i data-feather="bell"></i>
            <div class="badge-dot"></div>
        </div>
        <div class="header-btn header-btn-mail" id="topbar-mail-btn" title="Kotak Pesan" style="cursor: pointer; position: relative;">
            <i data-feather="mail"></i>
        </div>
    </div>
</header>

<div class="content" style="margin-top: 40px; padding: 1.5rem 2rem;">

    @if(session('sukses'))
        <div class="alert alert-success d-flex align-items-center mb-5" role="alert" style="background:#ecfdf5;border:1.5px solid #a7f3d0;color:#065f46;border-radius:12px;padding:12px 18px;display:flex;gap:10px;align-items:center;">
            <i data-feather="check-circle" class="w-5 h-5 text-emerald-600"></i>
            <span style="font-weight:600;font-size:14px;">{{ session('sukses') }}</span>
        </div>
    @endif

    @if(session('gagal'))
        <div class="alert alert-danger d-flex align-items-center mb-5" role="alert" style="background:#fef2f2;border:1.5px solid #fecaca;color:#991b1b;border-radius:12px;padding:12px 18px;display:flex;gap:10px;align-items:center;">
            <i data-feather="alert-circle" class="w-5 h-5 text-red-600"></i>
            <span style="font-weight:600;font-size:14px;">{{ session('gagal') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-12 gap-6">

        @if($isAdmin)
        <!-- ADMIN ONLY: Buat Pemberitahuan Baru Form Card -->
        <div class="col-span-12 lg:col-span-4">
            <div class="intro-y box p-6 bg-white shadow-sm rounded-2xl border border-gray-100" style="max-height: calc(100vh - 120px); overflow-y: auto;">
                <div class="flex items-center gap-3 pb-4 border-b border-gray-100 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <i data-feather="send" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-800">Buat Pemberitahuan</h2>
                        <p class="text-xs text-gray-500">Siarkan ke semua role (Kasir, CS, HR, Teknisi)</p>
                    </div>
                </div>

                <form action="{{ route('announcements.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Tipe Pemberitahuan <span class="text-red-500">*</span></label>
                        <select name="tipe" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all" required>
                            <option value="maintenance">🛠️ Maintenance / Pemeliharaan Sistem</option>
                            <option value="penting">⚠️ Pengumuman Penting</option>
                            <option value="info">📢 Informasi Umum / Update</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Judul Pemberitahuan <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all" placeholder="Contoh: Maintenance Server Jam 22:00" required>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Mulai (Opsional)</label>
                            <input type="datetime-local" name="mulai_pada" class="w-full px-2.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:bg-white focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Selesai (Opsional)</label>
                            <input type="datetime-local" name="selesai_pada" class="w-full px-2.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:bg-white focus:border-blue-500">
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Isi Pesan / Instruksi <span class="text-red-500">*</span></label>
                        <textarea name="isi" rows="4" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all" placeholder="Tuliskan rincian pemberitahuan, durasi pengerjaan, atau instruksi bagi karyawan..." required></textarea>
                    </div>

                    <button type="submit" style="display:flex; align-items:center; justify-content:center; gap:8px; width:100%; padding:14px 16px; background:linear-gradient(135deg,#2563eb,#4f46e5); color:#ffffff; font-weight:700; font-size:14px; border:none; border-radius:12px; box-shadow:0 4px 15px rgba(37,99,235,0.4); cursor:pointer; transition:all 0.2s; min-height:50px; letter-spacing:0.3px;" onmouseover="this.style.background='linear-gradient(135deg,#1d4ed8,#4338ca)'" onmouseout="this.style.background='linear-gradient(135deg,#2563eb,#4f46e5)'">
                        📢 Siarkan Pemberitahuan
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Announcement List -->
        <div class="col-span-12 {{ $isAdmin ? 'lg:col-span-8' : 'lg:col-span-12' }}">
            <div class="intro-y box p-6 bg-white shadow-sm rounded-2xl border border-gray-100">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                            <i data-feather="list" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-800">Daftar Pemberitahuan</h2>
                            <p class="text-xs text-gray-500">Informasi aktif dari Administrator</p>
                        </div>
                    </div>
                    <div>
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-full">
                            Total: {{ $announcements->count() }}
                        </span>
                    </div>
                </div>

                @if($announcements->isEmpty())
                    <div class="text-center py-12 text-gray-400">
                        <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-gray-50 flex items-center justify-center text-gray-300">
                            <i data-feather="bell-off" class="w-8 h-8"></i>
                        </div>
                        <p class="font-medium text-sm text-gray-500">Belum ada pemberitahuan atau jadwal maintenance aktif.</p>
                        <p class="text-xs text-gray-400 mt-1">Sistem berjalan dengan normal.</p>
                    </div>
                @else
                    <div class="space-y-4" id="announceListContainer">
                        @foreach($announcements as $item)
                        @php
                            $badgeColor = match($item->tipe) {
                                'maintenance' => 'bg-amber-100 text-amber-800 border-amber-300',
                                'penting' => 'bg-red-100 text-red-800 border-red-300',
                                default => 'bg-blue-100 text-blue-800 border-blue-300'
                            };
                            $badgeIcon = match($item->tipe) {
                                'maintenance' => '🛠️ Maintenance',
                                'penting' => '⚠️ Penting',
                                default => '📢 Informasi'
                            };
                        @endphp
                        <div class="announce-card p-5 rounded-2xl border transition-all hover:shadow-md bg-white border-gray-200" data-search="{{ strtolower($item->judul . ' ' . $item->isi) }}">
                            <div class="flex items-start justify-between gap-4 mb-3">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg border {{ $badgeColor }}">
                                        {{ $badgeIcon }}
                                    </span>
                                    <h3 class="text-base font-bold text-gray-900">{{ $item->judul }}</h3>
                                </div>
                                @if($isAdmin)
                                <form action="{{ route('announcements.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pemberitahuan ini?');" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors p-1" title="Hapus Pemberitahuan">
                                        <i data-feather="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                @endif
                            </div>

                            <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line mb-4">{{ $item->isi }}</p>

                            @if($item->mulai_pada || $item->selesai_pada)
                            <div class="flex items-center gap-4 text-xs font-medium text-gray-500 bg-gray-50 p-2.5 rounded-xl mb-3 border border-gray-100">
                                <i data-feather="clock" class="w-4 h-4 text-amber-500"></i>
                                <span>
                                    @if($item->mulai_pada) Mulai: <strong>{{ $item->mulai_pada->format('d M Y H:i') }}</strong> @endif
                                    @if($item->selesai_pada) &bull; Selesai: <strong>{{ $item->selesai_pada->format('d M Y H:i') }}</strong> @endif
                                </span>
                            </div>
                            @endif

                            <div class="flex items-center justify-between text-xs text-gray-400 pt-3 border-t border-gray-100">
                                <span>Diterbitkan oleh: <strong class="text-gray-600">{{ $item->creator ? $item->creator->kry_nama : 'Admin' }}</strong></span>
                                <span>{{ $item->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

<script>
function filterAnnouncements() {
    const q = document.getElementById('filterAnnounceSearch').value.toLowerCase();
    document.querySelectorAll('.announce-card').forEach(el => {
        const text = el.dataset.search || '';
        el.style.display = text.includes(q) ? 'block' : 'none';
    });
}
</script>
@endsection
