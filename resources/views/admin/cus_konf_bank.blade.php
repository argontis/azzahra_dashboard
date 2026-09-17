@extends('layouts.app')

@section('content')
<!-- Header -->
<header class="page-header mb-5">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="credit-card" class="w-6 h-6 inline-block mr-2"></i>Bank Transfer &amp; Xendit</h1>                
        <p>Konfirmasi Pembayaran Transfer Bank &amp; Tagihan Online Xendit</p>	
    </div>
    <div class="header-actions">
        <div class="search-input-wrapper">
            <i data-feather="search" class="search-icon"></i>
            <input type="text" class="search-input" id="tableSearchInput" placeholder="Cari transaksi / customer...">
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

<div class="content" style="margin-top: 60px">
    <div class="sukses" data-sukses="{{ session('sukses') }}"></div>
    @if(session('gagal'))
        <div class="rounded-md flex items-center px-5 py-4 mb-4 bg-theme-6 text-white">
            <i data-feather="alert-circle" class="w-6 h-6 mr-2"></i> {{ session('gagal') }}
        </div>
    @endif

    <div class="grid grid-cols-12 gap-5 mt-5">
        <div class="col-span-12">
            <div class="intro-y datatable-wrapper box p-5">
                <div class="flex flex-col sm:flex-row items-center justify-between pb-4 mb-4 border-b border-gray-200">
                    <div>
                        <h2 class="text-base font-semibold text-gray-800">Daftar Konfirmasi Bank Transfer</h2>
                        <p class="text-xs text-gray-500">Pilih opsi <strong>Setor Manual</strong> untuk setoran fisik atau <strong>Xendit</strong> untuk pembayaran online (QRIS/VA).</p>
                    </div>
                    <div class="mt-3 sm:mt-0 text-xs text-gray-500">
                        Total: <span class="font-bold text-gray-800">{{ count($trans) }}</span> Transaksi Menunggu
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="table table-report table-report--bordered display datatable w-full text-sm" id="tableBankTransfer">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700">
                                <th class="border-b-2 text-center whitespace-no-wrap w-12">NO</th>
                                <th class="border-b-2 whitespace-no-wrap">NAMA CUSTOMER</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">JML BAYAR</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">BANK</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">STATUS</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">SETORAN</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">STATUS XENDIT</th>
                                <th class="border-b-2 text-center whitespace-no-wrap" style="min-width: 190px;">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 0; @endphp
                            @forelse ($trans as $row)
                                @php
                                    $xenditStatus = strtoupper($row->xendit_status ?? '');
                                    $isPaid = in_array($xenditStatus, ['PAID', 'SETTLED']);
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors" id="row-{{ $row->dtl_kode }}">
                                    <td class="text-center border-b font-medium text-gray-600">{{ ++$no }}</td>
                                    <td class="border-b">
                                        <div class="font-semibold text-gray-800">{{ $row->cos_nama }}</div>
                                        <div class="text-xs text-gray-500 flex items-center mt-0.5">
                                            <i data-feather="phone" class="w-3 h-3 mr-1 inline"></i>
                                            {{ !empty($row->cos_hp) ? $row->cos_hp : '-' }}
                                            <span class="mx-1">•</span>
                                            <span class="text-gray-400 font-mono">{{ $row->trans_kode }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center border-b font-bold text-gray-800">
                                        Rp {{ number_format($row->dtl_jml_bayar, 0, ',', '.') }},-
                                    </td>
                                    <td class="text-center border-b">
                                        <span class="px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                            {{ $row->dtl_bank ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center border-b">
                                        <span class="px-2 py-1 rounded text-xs font-semibold bg-gray-100 text-gray-700">
                                            {{ $row->dtl_status }}
                                        </span>
                                    </td>
                                    <td class="text-center border-b">
                                        @if($row->dtl_stt_stor === 'Disetorkan')
                                            <span class="px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                                <i data-feather="check-circle" class="w-3 h-3 inline mr-1"></i> Disetorkan
                                            </span>
                                        @else
                                            <span class="px-2 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                {{ $row->dtl_stt_stor }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center border-b" id="xendit-badge-cell-{{ $row->dtl_kode }}">
                                        @if($isPaid)
                                            <span class="px-2 py-1 rounded text-xs font-bold bg-green-500 text-white shadow-sm flex items-center justify-center gap-1 mx-auto w-24">
                                                <i data-feather="check" class="w-3 h-3"></i> LUNAS
                                            </span>
                                        @elseif($xenditStatus === 'PENDING')
                                            <span class="px-2 py-1 rounded text-xs font-bold bg-yellow-500 text-white shadow-sm flex items-center justify-center gap-1 mx-auto w-24 animate-pulse">
                                                <i data-feather="clock" class="w-3 h-3"></i> PENDING
                                            </span>
                                        @elseif($xenditStatus === 'EXPIRED')
                                            <span class="px-2 py-1 rounded text-xs font-bold bg-red-500 text-white shadow-sm flex items-center justify-center gap-1 mx-auto w-24">
                                                <i data-feather="x-circle" class="w-3 h-3"></i> EXPIRED
                                            </span>
                                        @else
                                            <span class="px-2 py-1 rounded text-xs font-medium bg-gray-200 text-gray-600 mx-auto inline-block">
                                                Belum Ada
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center border-b">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Manual Setor Button -->
                                            <a href="#" 
                                               class="button button--sm py-1.5 px-2.5 flex items-center justify-center bg-gray-600 hover:bg-gray-700 text-white rounded text-xs font-semibold shadow-sm modal-setoran transition-all" 
                                               data-kode="{{ $row->dtl_kode }}" 
                                               data-bank="{{ $row->dtl_bank }}" 
                                               data-jml_setoran="{{ $row->dtl_jml_bayar }}"
                                               title="Setor Manual ke Rekening">
                                                <i data-feather="database" class="w-3.5 h-3.5 mr-1"></i> Setor
                                            </a>

                                            <!-- Xendit Online Option Button -->
                                            <button type="button" 
                                                    class="button button--sm py-1.5 px-2.5 flex items-center justify-center bg-theme-1 hover:bg-blue-700 text-white rounded text-xs font-semibold shadow-sm btn-open-xendit transition-all"
                                                    data-kode="{{ $row->dtl_kode }}"
                                                    data-trans="{{ $row->trans_kode }}"
                                                    data-customer="{{ $row->cos_nama }}"
                                                    data-hp="{{ $row->cos_hp ?? '' }}"
                                                    data-jml="{{ $row->dtl_jml_bayar }}"
                                                    data-jml-fmt="{{ number_format($row->dtl_jml_bayar, 0, ',', '.') }}"
                                                    data-invoice-id="{{ $row->xendit_invoice_id ?? '' }}"
                                                    data-invoice-url="{{ $row->xendit_invoice_url ?? '' }}"
                                                    data-status="{{ $row->xendit_status ?? '' }}"
                                                    title="Opsi Pembayaran Xendit">
                                                <i data-feather="credit-card" class="w-3.5 h-3.5 mr-1"></i> Xendit
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-8 text-gray-500">
                                        <i data-feather="inbox" class="w-10 h-10 mx-auto mb-2 opacity-30"></i>
                                        <p class="font-medium">Tidak ada transaksi bank transfer yang menunggu setoran.</p>
                                    </td>
                                </tr>
                            @endforelse
                            <tr id="noSearchResultRow" style="display: none;">
                                <td colspan="8" class="text-center py-6 text-gray-500">
                                    <i data-feather="search" class="w-8 h-8 mx-auto mb-2 opacity-40"></i>
                                    <p>Tidak ada transaksi yang cocok dengan pencarian.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Setoran Manual -->
<div class="modal" id="setoran">
    <div class="modal__content max-w-md">
        <div class="flex items-center px-5 py-4 border-b border-gray-200">
            <h2 class="font-semibold text-base mr-auto flex items-center text-gray-800">
                <i data-feather="database" class="w-4 h-4 mr-2 text-theme-1"></i> Konfirmasi Transfer Bank Manual
            </h2>
            <button type="button" data-dismiss="modal" class="text-gray-400 hover:text-gray-600">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form method="post" action="{{ url('Admin/setoran') }}">
            @csrf
            <div class="p-5 grid grid-cols-12 gap-4">
                <div class="col-span-12">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Bank Tujuan</label>
                    <input type="hidden" name="kode" id="modal-kode">
                    <input type="text" class="input w-full border mt-1 bg-gray-100 text-gray-700 font-semibold" id="modal-bank" readonly>
                </div>
                <div class="col-span-12">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Jumlah Transfer (Rp)</label>
                    <input type="number" class="input w-full border mt-1 font-bold text-gray-800" id="modal-jml-tranfer" placeholder="Masukan Jumlah Transfer" name="jml_tranfer" required>
                </div>
                <div class="col-span-12 p-3 bg-blue-50 border border-blue-200 rounded-md text-xs text-blue-800 flex items-start gap-2">
                    <i data-feather="info" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                    <span>Tindakan ini mengonfirmasi bahwa dana telah masuk ke rekening toko dan menandai status sebagai <strong>Disetorkan</strong>.</span>
                </div>
            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200 bg-gray-50 flex items-center justify-between">
                <button type="button" id="btn-switch-to-xendit" class="text-xs text-theme-1 font-semibold hover:underline flex items-center gap-1">
                    <i data-feather="credit-card" class="w-3.5 h-3.5"></i> Opsi via Xendit
                </button>
                <div class="flex items-center gap-2">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 bg-white hover:bg-gray-100">Batal</button>
                    <button type="submit" class="button w-24 bg-theme-1 text-white font-semibold shadow">Setorkan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Xendit Online Payment -->
<div class="modal" id="xendit-modal">
    <div class="modal__content max-w-lg">
        <div class="flex items-center px-5 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-700 to-indigo-800 text-white rounded-t-md">
            <div class="mr-auto">
                <div class="flex items-center gap-2">
                    <i data-feather="credit-card" class="w-5 h-5"></i>
                    <h2 class="font-bold text-base tracking-wide">Opsi Pembayaran Xendit</h2>
                </div>
                <p class="text-xs text-blue-200 mt-0.5">Virtual Account • QRIS • E-Wallet • Retail Outlet</p>
            </div>
            <button type="button" data-dismiss="modal" class="text-blue-200 hover:text-white transition-colors">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>

        <div class="p-5">
            <!-- Customer Summary Card -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3.5 mb-4">
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <span class="text-gray-500 block">Customer:</span>
                        <span class="font-bold text-gray-800 text-sm" id="xendit-cust-name">-</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">No. Transaksi:</span>
                        <span class="font-mono font-bold text-gray-800 text-sm" id="xendit-trans-kode">-</span>
                    </div>
                    <div class="mt-1">
                        <span class="text-gray-500 block">No. WhatsApp/HP:</span>
                        <span class="font-medium text-gray-700" id="xendit-cust-hp">-</span>
                    </div>
                    <div class="mt-1">
                        <span class="text-gray-500 block">Total Tagihan:</span>
                        <span class="font-extrabold text-blue-600 text-sm" id="xendit-amount">-</span>
                    </div>
                </div>
            </div>

            <!-- Loading Spinner State -->
            <div id="xendit-loading" class="text-center py-6" style="display: none;">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-blue-600 border-t-transparent"></div>
                <p class="text-xs text-gray-600 font-semibold mt-2" id="xendit-loading-text">Menghubungkan ke Xendit...</p>
            </div>

            <!-- View: Invoice NOT Created Yet -->
            <div id="xendit-view-empty" class="text-center py-4">
                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 mx-auto flex items-center justify-center mb-3">
                    <i data-feather="link-2" class="w-6 h-6"></i>
                </div>
                <h3 class="text-sm font-bold text-gray-800 mb-1">Buat Tagihan Online Xendit</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto mb-4">
                    Sistem akan membuat link pembayaran resmi dari Xendit. Pelanggan dapat membayar via BCA, BRI, Mandiri, BNI, QRIS, mau pun ShopeePay/OVO.
                </p>
                <button type="button" id="btn-generate-xendit" class="button bg-theme-1 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded shadow flex items-center justify-center gap-2 mx-auto text-xs">
                    <i data-feather="plus-circle" class="w-4 h-4"></i> Generate Invoice Xendit
                </button>
            </div>

            <!-- View: Invoice ALREADY Created -->
            <div id="xendit-view-active" style="display: none;">
                <div class="flex items-center justify-between pb-2 mb-3 border-b border-gray-100">
                    <span class="text-xs font-semibold text-gray-600">Status Tagihan Xendit:</span>
                    <span id="xendit-status-badge" class="px-2.5 py-1 rounded text-xs font-bold bg-yellow-500 text-white">
                        PENDING
                    </span>
                </div>

                <!-- Invoice Link Box -->
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Link Pembayaran (Invoice URL):</label>
                    <div class="flex gap-1.5">
                        <input type="text" id="xendit-invoice-url-input" class="input w-full border text-xs bg-gray-50 text-gray-700 font-mono" readonly>
                        <button type="button" id="btn-copy-xendit-link" class="button border bg-white hover:bg-gray-100 text-gray-700 text-xs px-3 flex items-center gap-1" title="Salin Link">
                            <i data-feather="copy" class="w-3.5 h-3.5"></i> <span id="copy-text">Salin</span>
                        </button>
                    </div>
                </div>

                <!-- Action Buttons Grid -->
                <div class="grid grid-cols-2 gap-2.5 mb-3">
                    <a href="#" id="btn-open-checkout" target="_blank" class="button bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold py-2 px-3 rounded flex items-center justify-center gap-1.5 shadow-sm text-center">
                        <i data-feather="external-link" class="w-3.5 h-3.5"></i> Buka Pembayaran
                    </a>

                    <a href="#" id="btn-send-whatsapp" target="_blank" class="button bg-green-600 hover:bg-green-700 text-white text-xs font-semibold py-2 px-3 rounded flex items-center justify-center gap-1.5 shadow-sm text-center">
                        <i data-feather="message-circle" class="w-3.5 h-3.5"></i> Kirim WhatsApp
                    </a>
                </div>

                <div class="grid grid-cols-2 gap-2.5 pt-2 border-t border-gray-100">
                    <button type="button" id="btn-check-xendit-status" class="button bg-gray-800 hover:bg-gray-900 text-white text-xs font-semibold py-2 px-3 rounded flex items-center justify-center gap-1.5 shadow-sm">
                        <i data-feather="refresh-cw" class="w-3.5 h-3.5"></i> Cek Status Xendit
                    </button>

                    <button type="button" id="btn-regenerate-xendit" class="button border border-gray-300 hover:bg-gray-100 text-gray-700 text-xs font-semibold py-2 px-3 rounded flex items-center justify-center gap-1.5">
                        <i data-feather="rotate-cw" class="w-3.5 h-3.5"></i> Buat Invoice Baru
                    </button>
                </div>
            </div>
        </div>

        <div class="px-5 py-3 text-right border-t border-gray-200 bg-gray-50 flex items-center justify-between">
            <span class="text-xs text-gray-400 font-mono" id="xendit-invoice-id-display"></span>
            <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 bg-white hover:bg-gray-100 text-xs">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var currentDtlKode = null;
    var currentTransKode = null;
    var currentCustomer = null;
    var currentHp = null;
    var currentJml = null;
    var currentInvoiceUrl = null;

    // Search filter for table
    $('#tableSearchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        var matchCount = 0;

        $('#tableBankTransfer tbody tr:not(#noSearchResultRow)').filter(function() {
            var match = $(this).text().toLowerCase().indexOf(value) > -1;
            $(this).toggle(match);
            if (match) matchCount++;
        });

        $('#noSearchResultRow').toggle(matchCount === 0);
    });

    // Specific handler for Setoran Modal
    $(document).on('click', '.modal-setoran', function(e) {
        e.preventDefault();
        var kode        = $(this).data('kode');
        var bank        = $(this).data('bank');
        var jml_setoran = $(this).data('jml_setoran');

        $('#modal-kode').val(kode);
        $('#modal-bank').val(bank);
        $('#modal-jml-tranfer').val(jml_setoran);
        window.openAppModal('#setoran');
    });

    // Switch from Setor Modal to Xendit Modal
    $('#btn-switch-to-xendit').on('click', function(e) {
        e.preventDefault();
        window.closeAppModal('#setoran');
        var targetBtn = $('.btn-open-xendit[data-kode="' + $('#modal-kode').val() + '"]');
        if (targetBtn.length) {
            targetBtn.trigger('click');
        }
    });

    // Open Xendit Modal
    $(document).on('click', '.btn-open-xendit', function(e) {
        e.preventDefault();
        var $btn = $(this);

        currentDtlKode    = $btn.data('kode');
        currentTransKode  = $btn.data('trans');
        currentCustomer   = $btn.data('customer');
        currentHp         = $btn.data('hp');
        currentJml        = $btn.data('jml');
        var jmlFmt        = $btn.data('jml-fmt');
        var invoiceId     = $btn.data('invoice-id');
        var invoiceUrl    = $btn.data('invoice-url');
        var status        = ($btn.data('status') || '').toUpperCase();

        $('#xendit-cust-name').text(currentCustomer || '-');
        $('#xendit-trans-kode').text(currentTransKode || '-');
        $('#xendit-cust-hp').text(currentHp || '(Tidak ada nomor)');
        $('#xendit-amount').text('Rp ' + jmlFmt + ',-');

        renderXenditModalContent(invoiceId, invoiceUrl, status);
        window.openAppModal('#xendit-modal');
    });

    var autoPollInterval = null;

    function startAutoPolling(dtlKode) {
        stopAutoPolling();
        if (!dtlKode) return;

        autoPollInterval = setInterval(function() {
            $.ajax({
                url: "{{ url('Admin/xendit/status') }}/" + dtlKode,
                type: "GET",
                dataType: "json",
                success: function(res) {
                    if (res.success && res.is_paid) {
                        stopAutoPolling();
                        updateRowBadge(dtlKode, res.status);
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Pembayaran Lunas Terdeteksi!',
                                text: 'Pembayaran Xendit telah terkonfirmasi otomatis.',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(function() {
                                window.location.reload();
                            });
                        } else {
                            window.location.reload();
                        }
                    }
                }
            });
        }, 3500);
    }

    function stopAutoPolling() {
        if (autoPollInterval) {
            clearInterval(autoPollInterval);
            autoPollInterval = null;
        }
    }

    // Stop polling when modal is closed
    $('[data-dismiss="modal"]').on('click', function() {
        stopAutoPolling();
    });

    function renderXenditModalContent(invoiceId, invoiceUrl, status) {
        currentInvoiceUrl = invoiceUrl;

        $('#xendit-loading').hide();

        if (!invoiceUrl || status === '') {
            stopAutoPolling();
            $('#xendit-view-empty').show();
            $('#xendit-view-active').hide();
            $('#xendit-invoice-id-display').text('');
        } else {
            $('#xendit-view-empty').hide();
            $('#xendit-view-active').show();

            $('#xendit-invoice-url-input').val(invoiceUrl);
            $('#btn-open-checkout').attr('href', invoiceUrl);
            $('#xendit-invoice-id-display').text('ID: ' + (invoiceId || '-'));

            // WhatsApp link
            var waUrl = generateWhatsAppLink(currentHp, currentCustomer, currentTransKode, currentJml, invoiceUrl);
            $('#btn-send-whatsapp').attr('href', waUrl);

            // Badge styling
            var $badge = $('#xendit-status-badge');
            $badge.text(status || 'PENDING').removeClass('bg-green-500 bg-yellow-500 bg-red-500 bg-gray-500');

            if (status === 'PAID' || status === 'SETTLED') {
                stopAutoPolling();
                $badge.addClass('bg-green-500').text('LUNAS (PAID)');
            } else if (status === 'EXPIRED') {
                stopAutoPolling();
                $badge.addClass('bg-red-500').text('EXPIRED');
            } else {
                $badge.addClass('bg-yellow-500').text('PENDING');
                // Auto-poll in background while waiting for customer payment
                startAutoPolling(currentDtlKode);
            }
        }

        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }

    // Generate Invoice Xendit (AJAX)
    $('#btn-generate-xendit, #btn-regenerate-xendit').on('click', function(e) {
        e.preventDefault();
        var isForce = $(this).attr('id') === 'btn-regenerate-xendit';

        if (!currentDtlKode) return;

        $('#xendit-view-empty').hide();
        $('#xendit-view-active').hide();
        $('#xendit-loading').show();
        $('#xendit-loading-text').text('Membuat invoice resmi Xendit...');

        $.ajax({
            url: "{{ route('admin.xendit.create') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                dtl_kode: currentDtlKode,
                force_new: isForce ? 1 : 0
            },
            dataType: "json",
            success: function(res) {
                $('#xendit-loading').hide();
                if (res.success && res.data) {
                    var data = res.data;
                    // Update trigger button data
                    var $btn = $('.btn-open-xendit[data-kode="' + currentDtlKode + '"]');
                    $btn.data('invoice-id', data.invoice_id);
                    $btn.data('invoice-url', data.invoice_url);
                    $btn.data('status', data.status);

                    // Update table badge
                    updateRowBadge(currentDtlKode, data.status);

                    renderXenditModalContent(data.invoice_id, data.invoice_url, data.status);

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Invoice Berhasil Dibuat!',
                            text: 'Link pembayaran Xendit siap dibagikan ke pelanggan.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                } else {
                    $('#xendit-view-empty').show();
                    alert(res.message || 'Gagal membuat tagihan Xendit.');
                }
            },
            error: function(xhr) {
                $('#xendit-loading').hide();
                $('#xendit-view-empty').show();
                var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Membuat Tagihan',
                        text: msg
                    });
                } else {
                    alert(msg);
                }
            }
        });
    });

    // Check Status Xendit (AJAX)
    $('#btn-check-xendit-status').on('click', function(e) {
        e.preventDefault();
        if (!currentDtlKode) return;

        var $btn = $(this);
        $btn.prop('disabled', true).addClass('opacity-50');

        $.ajax({
            url: "{{ url('Admin/xendit/status') }}/" + currentDtlKode,
            type: "GET",
            dataType: "json",
            success: function(res) {
                $btn.prop('disabled', false).removeClass('opacity-50');
                if (res.success) {
                    var status = res.status;
                    var $openBtn = $('.btn-open-xendit[data-kode="' + currentDtlKode + '"]');
                    $openBtn.data('status', status);

                    updateRowBadge(currentDtlKode, status);
                    renderXenditModalContent($openBtn.data('invoice-id'), $openBtn.data('invoice-url'), status);

                    if (res.is_paid) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Pembayaran LUNAS!',
                                text: 'Pembayaran telah dikonfirmasi dan status disetorkan.',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location.reload();
                            });
                        } else {
                            alert('Pembayaran LUNAS!');
                            window.location.reload();
                        }
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'info',
                                title: 'Status Terkini: ' + status,
                                text: 'Pembayaran belum diselesaikan oleh pelanggan.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    }
                } else {
                    alert(res.message || 'Gagal memeriksa status.');
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).removeClass('opacity-50');
                var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal menghubungi server.';
                alert(msg);
            }
        });
    });

    // Copy to clipboard
    $('#btn-copy-xendit-link').on('click', function() {
        var input = document.getElementById('xendit-invoice-url-input');
        input.select();
        input.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(input.value).then(function() {
            $('#copy-text').text('Tersalin!');
            setTimeout(function() {
                $('#copy-text').text('Salin');
            }, 2000);
        });
    });

    // Helper: format Indonesian phone and generate WhatsApp URL
    function generateWhatsAppLink(phone, name, transKode, amount, invoiceUrl) {
        if (!phone) return '#';

        var cleanPhone = phone.replace(/[^0-9]/g, '');
        if (cleanPhone.startsWith('0')) {
            cleanPhone = '62' + cleanPhone.slice(1);
        } else if (cleanPhone.startsWith('8')) {
            cleanPhone = '62' + cleanPhone;
        }

        var amountFmt = new Intl.NumberFormat('id-ID').format(amount || 0);
        var message = "Halo Kak *" + (name || 'Pelanggan') + "*,\n\n"
            + "Berikut adalah tagihan servis dari *Azzahra Computer*:\n\n"
            + "• No. Transaksi: *" + transKode + "*\n"
            + "• Total Tagihan: *Rp " + amountFmt + ",-*\n\n"
            + "Untuk kemudahan pembayaran, Kakak dapat membayar online (Transfer Bank / Virtual Account BCA, Mandiri, BRI, BNI, QRIS, atau E-Wallet) melalui link resmi Xendit berikut:\n"
            + "👉 " + invoiceUrl + "\n\n"
            + "Pembayaran akan terverifikasi secara otomatis oleh sistem kami. Terima kasih! 🙏";

        return 'https://wa.me/' + cleanPhone + '?text=' + encodeURIComponent(message);
    }

    // Helper: update table row badge
    function updateRowBadge(dtlKode, status) {
        var $cell = $('#xendit-badge-cell-' + dtlKode);
        if (!$cell.length) return;

        status = (status || '').toUpperCase();
        if (status === 'PAID' || status === 'SETTLED') {
            $cell.html('<span class="px-2 py-1 rounded text-xs font-bold bg-green-500 text-white shadow-sm flex items-center justify-center gap-1 mx-auto w-24"><i data-feather="check" class="w-3 h-3"></i> LUNAS</span>');
        } else if (status === 'PENDING') {
            $cell.html('<span class="px-2 py-1 rounded text-xs font-bold bg-yellow-500 text-white shadow-sm flex items-center justify-center gap-1 mx-auto w-24 animate-pulse"><i data-feather="clock" class="w-3 h-3"></i> PENDING</span>');
        } else if (status === 'EXPIRED') {
            $cell.html('<span class="px-2 py-1 rounded text-xs font-bold bg-red-500 text-white shadow-sm flex items-center justify-center gap-1 mx-auto w-24"><i data-feather="x-circle" class="w-3 h-3"></i> EXPIRED</span>');
        }

        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }

    // Auto-sync any PENDING invoices on page load
    $('.btn-open-xendit').each(function() {
        var dtlKode = $(this).data('kode');
        var status = ($(this).data('status') || '').toUpperCase();
        if (status === 'PENDING') {
            $.ajax({
                url: "{{ url('Admin/xendit/status') }}/" + dtlKode,
                type: "GET",
                dataType: "json",
                success: function(res) {
                    if (res.success && res.is_paid) {
                        updateRowBadge(dtlKode, res.status);
                        setTimeout(function() {
                            window.location.reload();
                        }, 1200);
                    }
                }
            });
        }
    });

    // Check if returning from checkout with success status
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('status') === 'success') {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Pembayaran Diterima!',
                text: 'Pembayaran Xendit Anda telah diverifikasi oleh sistem.',
                timer: 3000,
                showConfirmButton: false
            });
        }
    }
});
</script>
@endpush
