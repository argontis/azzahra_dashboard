@extends('layouts.app')

@section('content')
<div class="content">
    <header class="page-header mb-5">
        <div class="header-title">
            <h1><i data-feather="layout" class="w-6 h-6 inline-block mr-2"></i>{{ $title ?? 'Admin Dashboard' }}</h1>
        </div>            
        <div class="header-actions">
            <div class="search-input-wrapper">
                <i data-feather="search" class="search-icon"></i>
                <input type="text" class="search-input" placeholder="Search...">
            </div>
            <div class="header-btn header-btn-bell" id="topbar-bell-btn" title="Pemberitahuan Sistem & Maintenance" style="cursor: pointer; position: relative;">
                <i data-feather="bell"></i>
                <div class="badge-dot" style="display: none;"></div>
            </div>
            <div class="header-btn header-btn-mail" id="topbar-mail-btn" title="Kotak Pesan" style="cursor: pointer; position: relative;">
                <i data-feather="mail"></i>
                <span class="topbar-mail-badge" style="display: none; position: absolute; top: -4px; right: -4px; background: #ef4444; color: white; border-radius: 9999px; font-size: 10px; font-weight: bold; min-width: 16px; height: 16px; line-height: 16px; text-align: center; padding: 0 4px;"></span>
            </div>
        </div>
    </header>

    
    <div class="intro-y box p-5 mt-5">
        <div class="overflow-x-auto">
            <table class="table table-report table-report--bordered display datatable w-full">
                <thead>
                    <tr>
                        <th class="border-b-2 text-center whitespace-no-wrap">NO</th>
                        <th class="border-b-2 whitespace-no-wrap">NAMA CUSTOMER</th>
                        <th class="border-b-2 whitespace-no-wrap">NO HP</th>
                        <th class="border-b-2 whitespace-no-wrap">ALAMAT</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">TGL DAFTAR</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($custom as $index => $row)
                        <tr>
                            <td class="text-center border-b">{{ $index + 1 }}</td>
                            <td class="border-b font-medium">{{ $row->cos_nama }}</td>
                            <td class="border-b">{{ $row->cos_hp ?? '-' }}</td>
                            <td class="border-b">{{ $row->cos_alamat ?? '-' }}</td>
                            <td class="text-center border-b">{{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $custom->links() }}
        </div>
    </div>
</div>
@endsection
