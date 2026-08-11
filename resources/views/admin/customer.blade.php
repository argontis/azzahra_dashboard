@extends('layouts.app')

@section('content')
<div class="content">
    <header class="page-header mb-5">
    <div class="header-title">
        <h1><i data-feather="layout" class="w-6 h-6 inline-block mr-2"></i>{{ $title ?? 'Admin Dashboard' }}</h1>
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
