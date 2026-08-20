@extends('layouts.app')

@section('content')

<!-- Header Area -->
<div class="page-header" style="position: fixed; top: 0 !important; left: 260px !important; width: calc(100% - 260px) !important; margin: 0 !important; border-radius: 0 !important; z-index: 9999;">
    <div class="flex items-center gap-2 text-white">
        <i data-feather="package" class="w-5 h-5"></i>
        <h1 class="text-xl font-bold tracking-wide">Ketersediaan Sparepart</h1>
        <span class="hidden md:inline-block text-white text-xs opacity-90 ml-4 border-l border-white/20 pl-4">Manage sparepart</span>
    </div>
    
    <div class="flex items-center gap-3">
        <div class="relative hidden sm:block">
            <i data-feather="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" placeholder="Search..." class="pl-9 pr-4 py-2 rounded-md border-0 focus:ring-2 focus:ring-blue-400 text-gray-700 text-sm w-48 lg:w-80 shadow-sm">
        </div>
        <button class="w-9 h-9 bg-white rounded-md flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors shadow-sm relative">
            <i data-feather="bell" class="w-4 h-4"></i>
        </button>
        <button class="w-9 h-9 bg-white rounded-md flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors shadow-sm relative">
            <i data-feather="mail" class="w-4 h-4"></i>
        </button>
    </div>
</div>

<div class="content">
    
    <div class="intro-y box mt-5 p-5">
        <div class="intro-y flex flex-col sm:flex-row items-center my-5">
            <h2 class="text-lg font-medium mr-auto">Order Sparepart</h2>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr class="bg-gray-700 text-white">
                    <th>Nomor</th>
                    <th>No Transaksi</th>
                    <th>Nama Customer</th>
                    <th>Nama Barang/Sparepart</th>
                    <th>Ketersediaan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; ?>
@foreach($spareparts as $row)
                <tr>
                    <td>{{ $no++; }}</td>
                    <td>{{ $row->trans_kode; }}</td>
                    <td>{{ $row->cos_nama; }}</td>
                    <td>{{ $row->barang_nama; }}</td>
                    <td>
                        @if ($row->ketersediaan=='ada')
                            <span style="color:green;font-weight:bold">Ada</span>
                        @else
                            <span style="color:red;font-weight:bold">Tidak Ada di Gudang</span>
                        @endif
                    </td>
                    <td>
                        @if ($row->status=='menunggu')
                        <a href="{{ url('Ketersediaan_sparepart/barang_sampai/'.$row->id) }}" class="btn btn-success">Barang Telah Sampai</a>
                        @else
                        <span class="text-success">Selesai</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="intro-y box mt-5 p-5">
            <div class="intro-y flex flex-col sm:flex-row items-center my-5">
                <h2 class="text-lg font-medium mr-auto">Ketersediaan Sparepart</h2>
            </div>
            <div class="mb-3 flex items-center">
                <input type="text" id="apiSearch" class="form-control w-1/3 border border-black rounded px-3 py-2" placeholder="Cari sparepart...">
                <button id="apiSearchBtn" class="btn btn-green ml-2">Cari</button>
            </div>
            <div id="apiSparepartTable"></div>
            <div class="mt-3 flex items-center">
                <button id="apiPrevPage" class="btn btn-secondary mr-2">Prev</button>
                <span id="apiPageInfo"></span>
                <button id="apiNextPage" class="btn btn-secondary ml-2">Next</button>
            </div>
        </div>

        <script>
            // Token from PHP config
            const Token = "{{ config('app.azventory_api_key') }}";
            let currentPage = 1;
            let perPage = 10;
            let currentSearch = "";

            function renderTable(data, page, total) {
                let html = `<table class="table table-bordered">
                    <thead>
                        <tr class="bg-gray-700 text-white">
                            <th>Nomor</th>
                            <th>Part Number</th>
                            <th>Nama</th>
                            <th>Brand</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Lokasi</th>
                            <th>Harga</th>
                            <th>Gambar</th>
                        </tr>
                    </thead>
                    <tbody>`;
                data.forEach((item, idx) => {
                    html += `<tr>
                        <td>${(page-1)*perPage + idx + 1}</td>
                        <td>${item.part_number}</td>
                        <td>${item.name}</td>
                        <td>${item.brand}</td>
                        <td>${item.category}</td>
                        <td>${item.stock.current} ${item.stock.unit} ${item.stock.is_low ? '<span style="color:red">(Low)</span>' : ''}</td>
                        <td>${item.location}</td>
                        <td>Rp${item.price.toLocaleString()}</td>
                        <td><img src="${item.image_url}" alt="img" style="width:50px;height:50px"></td>
                    </tr>`;
                });
                html += `</tbody></table>`;
                document.getElementById('apiSparepartTable').innerHTML = html;
                document.getElementById('apiPageInfo').textContent = `Page ${page}`;
            }

            function fetchSpareparts(page = 1, search = "") {
                let url = `https://azventory.azzahracomputertegal.com/api/v1/inventory?per_page=${perPage}&page=${page}`;
                if (search) url += `&search=${encodeURIComponent(search)}`;
                fetch(url, {
                    method: "GET",
                    headers: {
                        "Accept": "application/json",
                        "Authorization": `Bearer ${Token}`
                    }
                })
                .then(res => res.json())
                .then(res => {
                    renderTable(res.data, page, res.total);
                });
            }

            document.getElementById('apiSearchBtn').onclick = function() {
                currentSearch = document.getElementById('apiSearch').value;
                currentPage = 1;
                fetchSpareparts(currentPage, currentSearch);
            };
            document.getElementById('apiPrevPage').onclick = function() {
                if (currentPage > 1) {
                    currentPage--;
                    fetchSpareparts(currentPage, currentSearch);
                }
            };
            document.getElementById('apiNextPage').onclick = function() {
                currentPage++;
                fetchSpareparts(currentPage, currentSearch);
            };

            // Initial load
            fetchSpareparts();
        </script>
</div>

@endsection
