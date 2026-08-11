@extends('layouts.app')

@section('content')
<header class="page-header mb-5">
    <div class="header-title">
        <h1><i data-feather="users" class="w-6 h-6 inline-block mr-2"></i>Customer</h1>                
        <p>Management Data Customer</p>
    </div>            
</header>

<div class="content mt-5">   
    @if(session('sukses'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('sukses') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#10b981'
            });
        });
    </script>
    @endif

    <div class="intro-y box p-5 mt-5">
        <form method="GET" action="{{ route('Customer.index') }}" class="mb-4 flex gap-2">
            <input type="text" name="search" class="input w-full sm:w-64 box border" placeholder="Cari customer..." value="{{ request('search') }}">
            <button type="submit" class="button bg-theme-1 text-white">Cari</button>
        </form>

        <div class="overflow-x-auto">
            <table class="table table-report -mt-2 w-full">
                <thead>
                    <tr>
                        <th class="whitespace-no-wrap">NAMA</th>
                        <th class="whitespace-no-wrap">ALAMAT</th>
                        <th class="text-center whitespace-no-wrap">NO HP</th>
                        <th class="text-center whitespace-no-wrap">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers ?? [] as $row)
                    <tr class="intro-x">
                        <td>
                            <div class="font-medium whitespace-no-wrap">{{ $row->cos_nama }}</div>
                        </td>
                        <td>{{ $row->cos_alamat }}</td>
                        <td class="text-center">{{ $row->cos_hp }}</td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a class="flex items-center mr-3" href="{{ route('Customer.edit', $row->id_costomer) }}">
                                    <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit
                                </a>
                                <form action="{{ route('Customer.destroy', $row->id_costomer) }}" method="POST" class="inline" onsubmit="return confirm('Hapus customer ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center text-theme-6">
                                        <i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center p-5 text-gray-600">Tidak ada data customer.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $customers->links() ?? '' }}
        </div>
    </div>
</div>
@endsection
