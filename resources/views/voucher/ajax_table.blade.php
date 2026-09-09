@if ($voucher->count() > 0)
    <div class="intro-y w-full">
        <table class="table table-report -mt-2 w-full min-w-full">
            <thead>
                <tr>
                    <th class="whitespace-nowrap text-center" style="width: 50px;">NO</th>
                    <th class="whitespace-nowrap text-center" style="width: 70px;">GAMBAR</th>
                    <th class="whitespace-nowrap" style="width: 140px;">VOUCHER CODE</th>
                    <th class="whitespace-nowrap" style="width: 180px;">DESCRIPTION</th>
                    <th class="whitespace-nowrap text-center" style="width: 90px;">DISCOUNT</th>
                    <th class="whitespace-nowrap text-center" style="width: 100px;">START DATE</th>
                    <th class="whitespace-nowrap text-center" style="width: 100px;">END DATE</th>
                    <th class="whitespace-nowrap text-center" style="width: 80px;">STATUS</th>
                    <th class="text-center whitespace-nowrap" style="width: 110px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = ($offset ?? 0) + 1;
                @endphp
                @foreach ($voucher as $row)
                <tr class="intro-x" id="row-{{ $row->voucher_id }}">
                    <td class="text-center font-medium">{{ $no++ }}</td>

                    <!-- GAMBAR -->
                    <td class="text-center">
                        @if ($row->voucher_gambar)
                            @php
                                $imgSrc = file_exists(public_path('storage/vouchers/' . $row->voucher_gambar))
                                    ? asset('storage/vouchers/' . $row->voucher_gambar)
                                    : (file_exists(public_path('assets/images/' . $row->voucher_gambar))
                                        ? asset('assets/images/' . $row->voucher_gambar)
                                        : asset('storage/vouchers/' . $row->voucher_gambar));
                            @endphp
                            <img
                                src="{{ $imgSrc }}"
                                alt="{{ $row->voucher_code }}"
                                class="w-12 h-12 object-cover rounded-lg mx-auto border-2 border-gray-200 shadow-sm hover:border-blue-500 transition-all cursor-pointer"
                                onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto border border-gray-200\'><i data-feather=\'image\' class=\'w-5 h-5 text-gray-400\'></i></div>'; if(typeof feather !== 'undefined') feather.replace();"
                                loading="lazy"
                                onclick="showImageModal('{{ $imgSrc }}', '{{ addslashes($row->voucher_code) }}')"
                                title="Klik untuk memperbesar"
                            >
                        @else
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto border border-gray-200">
                                <i data-feather="image" class="w-5 h-5 text-gray-400"></i>
                            </div>
                        @endif
                    </td>

                    <!-- VOUCHER CODE -->
                    <td>
                        <div class="font-bold text-blue-600 truncate" style="max-width: 140px;" title="{{ $row->voucher_code }}">
                            {{ $row->voucher_code }}
                        </div>
                    </td>

                    <!-- DESCRIPTION -->
                    <td>
                        <div class="text-gray-600 text-xs" style="max-width: 180px;" title="{{ $row->description }}">
                            {{ $row->description ?: '-' }}
                        </div>
                    </td>

                    <!-- DISCOUNT -->
                    <td class="text-center">
                        <span class="inline-block px-2.5 py-1 bg-green-100 text-green-700 font-bold rounded-lg text-xs">
                            {{ $row->discount_percent }}%
                        </span>
                    </td>

                    <!-- START DATE -->
                    <td class="text-center">
                        <div class="text-gray-600 text-xs whitespace-nowrap">
                            {{ $row->start_date ? date('d/m/Y', strtotime($row->start_date)) : '-' }}
                        </div>
                    </td>

                    <!-- END DATE -->
                    <td class="text-center">
                        <div class="text-gray-600 text-xs whitespace-nowrap">
                            {{ $row->end_date ? date('d/m/Y', strtotime($row->end_date)) : '-' }}
                        </div>
                    </td>

                    <!-- STATUS -->
                    <td class="text-center">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ ($row->status == 'active' || $row->status == 'aktif') ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ ucfirst($row->status) }}
                        </span>
                    </td>

                    <!-- AKSI -->
                    <td class="table-report__action">
                        <div class="flex justify-center items-center gap-1.5">
                            <button
                                type="button"
                                class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors detail-btn"
                                data-id="{{ $row->voucher_id }}"
                                data-code="{{ $row->voucher_code }}"
                                data-description="{{ $row->description }}"
                                data-discount="{{ $row->discount_percent }}"
                                data-start="{{ $row->start_date ? date('d/m/Y', strtotime($row->start_date)) : '-' }}"
                                data-end="{{ $row->end_date ? date('d/m/Y', strtotime($row->end_date)) : '-' }}"
                                data-max="{{ $row->max_usage ?? '-' }}"
                                data-status="{{ $row->status }}"
                                title="Detail Voucher">
                                <i data-feather="eye" class="w-4 h-4"></i>
                            </button>
                            <a
                                class="p-1.5 text-gray-500 hover:text-amber-600 hover:bg-amber-50 rounded-md transition-colors"
                                href="{{ route('admin.voucher.edit', $row->voucher_id) }}"
                                title="Edit Voucher">
                                <i data-feather="edit" class="w-4 h-4"></i>
                            </a>
                            <button
                                type="button"
                                class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors delete-btn"
                                data-id="{{ $row->voucher_id }}"
                                data-name="{{ $row->voucher_code }}"
                                data-url="{{ route('admin.voucher.delete', $row->voucher_id) }}"
                                title="Hapus Voucher">
                                <i data-feather="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
                <tr id="noSearchResultRow" style="display: none;">
                    <td colspan="9" class="text-center py-6 text-gray-500">
                        <i data-feather="search" class="w-8 h-8 mx-auto mb-2 opacity-40"></i>
                        <p>Tidak ada voucher yang cocok dengan pencarian.</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@else
    <div class="intro-y box px-5 py-12 bg-white rounded-xl shadow-sm border border-gray-100" id="empty-state">
        <div class="text-center">
            <div class="flex justify-center mb-3">
                <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center">
                    <i data-feather="percent" class="w-8 h-8"></i>
                </div>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Tidak ada voucher ditemukan</h3>
            <p class="text-gray-500 text-sm mt-1 max-w-sm mx-auto">Belum ada voucher yang dibuat atau tidak ada data yang cocok dengan pencarian.</p>
            <a href="{{ route('admin.voucher.add') }}" class="button inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-4 py-2.5 rounded-lg mt-5 shadow-sm transition-all">
                <i data-feather="plus" class="w-4 h-4"></i>
                Tambah Voucher Baru
            </a>
        </div>
    </div>
@endif

<!-- Pagination Info -->
@if (isset($total_records) && $total_records > 0)
<div class="pagination-info-wrapper intro-y flex flex-wrap sm:flex-row sm:flex-nowrap items-center justify-between mt-4">
    <div class="text-gray-600 text-xs font-medium">
        Menampilkan
        <span class="font-bold text-gray-800">{{ ($offset ?? 0) + 1 }}</span> -
        <span class="font-bold text-gray-800">{{ min(($offset ?? 0) + ($per_page ?? 15), $total_records) }}</span> dari
        <span class="font-bold text-gray-800">{{ number_format($total_records, 0, ',', '.') }}</span> voucher
    </div>
    <div class="mt-2 sm:mt-0">
        {{ $voucher->links() }}
    </div>
</div>
@endif

<script>
// Function: Show Image Modal
function showImageModal(imageUrl, voucherCode) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: `<strong>${voucherCode}</strong>`,
            imageUrl: imageUrl,
            imageAlt: voucherCode,
            showCloseButton: true,
            showConfirmButton: false,
            width: '500px',
            customClass: {
                popup: 'rounded-2xl'
            }
        });
    }
}

// Attach Event Listeners to rendered buttons
function attachVoucherTableEvents() {
    // Detail button handler
    document.querySelectorAll('.detail-btn').forEach(btn => {
        btn.onclick = function() {
            const voucherCode = this.getAttribute('data-code');
            const description = this.getAttribute('data-description');
            const discountPercent = this.getAttribute('data-discount');
            const startDate = this.getAttribute('data-start');
            const endDate = this.getAttribute('data-end');
            const maxUsage = this.getAttribute('data-max');
            const status = this.getAttribute('data-status');
            const voucherId = this.getAttribute('data-id');

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '<strong>' + voucherCode + '</strong>',
                    icon: 'info',
                    html: `
                        <div class="text-left text-sm" style="line-height: 1.8;">
                            <table class="w-full text-sm">
                                <tr>
                                    <td class="py-1 font-semibold text-gray-600" style="width: 40%;">Kode Voucher</td>
                                    <td class="py-1 font-bold text-blue-600">: ${voucherCode}</td>
                                </tr>
                                <tr>
                                    <td class="py-1 font-semibold text-gray-600">Deskripsi</td>
                                    <td class="py-1 text-gray-800">: ${description || '-'}</td>
                                </tr>
                                <tr>
                                    <td class="py-1 font-semibold text-gray-600">Diskon</td>
                                    <td class="py-1 font-bold text-green-600">: ${discountPercent}%</td>
                                </tr>
                                <tr>
                                    <td class="py-1 font-semibold text-gray-600">Mulai Berlaku</td>
                                    <td class="py-1 text-gray-800">: ${startDate}</td>
                                </tr>
                                <tr>
                                    <td class="py-1 font-semibold text-gray-600">Berakhir</td>
                                    <td class="py-1 text-gray-800">: ${endDate}</td>
                                </tr>
                                <tr>
                                    <td class="py-1 font-semibold text-gray-600">Maks Penggunaan</td>
                                    <td class="py-1 text-gray-800">: ${maxUsage}</td>
                                </tr>
                                <tr>
                                    <td class="py-1 font-semibold text-gray-600">Status</td>
                                    <td class="py-1">: <span class="px-2 py-0.5 rounded-full text-xs font-semibold ${status === 'active' || status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">${status}</span></td>
                                </tr>
                            </table>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: '<i data-feather="edit" class="w-4 h-4 inline mr-1"></i> Edit Voucher',
                    cancelButtonText: 'Tutup',
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#64748b',
                    width: '550px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ url('Admin/voucher/edit') }}/" + voucherId;
                    }
                });
            }
        };
    });

    // Delete button handler
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.onclick = function() {
            const name = this.getAttribute('data-name');
            const deleteUrl = this.getAttribute('data-url');

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Hapus Voucher?',
                    html: `Apakah Anda yakin ingin menghapus voucher <strong>${name}</strong>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return fetch(deleteUrl, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('Gagal menghapus');
                            return response.json();
                        })
                        .then(data => {
                            if (!data.success) {
                                throw new Error(data.message || 'Gagal menghapus voucher');
                            }
                            return data;
                        })
                        .catch(error => {
                            Swal.showValidationMessage(`Error: ${error.message || error}`);
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Voucher telah berhasil dihapus.',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        setTimeout(() => {
                            if (typeof loadData === 'function') {
                                loadData(currentPage, currentSearch);
                            } else {
                                window.location.reload();
                            }
                        }, 1200);
                    }
                });
            }
        };
    });

    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

// Auto-run event attachment
attachVoucherTableEvents();
</script>