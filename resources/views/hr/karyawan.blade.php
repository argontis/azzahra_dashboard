@extends('layouts.app')
@section('content')

<style>
    .content-area {
        margin-top: 4rem;
        padding: 2rem;
    }

    /* Fix button styles for HR views */
    .btn-primary {
        background: #0041c3 !important;
        color: white !important;
        border: 1px solid #0041c3 !important;
    }

    .btn-primary:hover {
        background: #003399 !important;
        border-color: #003399 !important;
    }

    .btn-outline {
        background: white !important;
        color: #0041c3 !important;
        border: 1px solid #0041c3 !important;
    }

    .btn-outline:hover {
        background: #0041c3 !important;
        color: white !important;
    }

    .btn-outline-danger {
        background: white !important;
        color: #ef4444 !important;
        border: 1px solid #ef4444 !important;
    }

    .btn-outline-danger:hover {
        background: #ef4444 !important;
        color: white !important;
    }

    /* Tab Styles */
    .tabs {
        display: flex;
        border-bottom: 1px solid #e2e8f0;
    }

    .tab-button {
        background: none;
        border: none;
        padding: 1rem 1.5rem;
        cursor: pointer;
        font-weight: 500;
        color: #64748b;
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
    }

    .tab-button.active {
        color: #0041c3;
        border-bottom-color: #0041c3;
    }

    .tab-button:hover {
        color: #0041c3;
    }

    .tab-content {
        display: none !important;
    }

    .tab-content.active {
        display: block !important;
    }

    .progress {
        background-color: #e2e8f0;
        border-radius: 0.25rem;
    }

    .progress-bar {
        background-color: #0041c3;
        color: white;
        text-align: center;
        font-size: 0.75rem;
        font-weight: 600;
    }

</style>

<div class="page-header">
    <div class="page-header-left">
        <div class="page-title-section">
            <h1 class="page-title">
                <i data-feather="users" class="w-10 h-10 inline-block mr-2"></i>
                Data Karyawan
            </h1>
            <p class="page-subtitle">
                <i data-feather="user-check"></i>
                Kelola data karyawan perusahaan
            </p>
        </div>
    </div>
    <div class="page-header-right">
        <div class="header-actions">
            <a role="button" class="btn btn-primary add-karyawan-btn">
                <i data-feather="plus-circle"></i> Tambah Karyawan
            </a>
        </div>
    </div>
</div>

<div class="content-area">
    <div class="dashboard-container">

        <!-- Flash Messages -->
        <?php if (session('sukses')): ?>
            <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
                <i data-feather="check-circle" class="mr-2"></i>
                <span><?= session('sukses'); ?></span>
            </div>
        <?php endif; ?>

        <?php if (session('gagal')): ?>
            <div class="alert alert-danger d-flex align-items-center mb-3" role="alert">
                <i data-feather="alert-circle" class="mr-2"></i>
                <span><?= session('gagal'); ?></span>
            </div>
        <?php endif; ?>

        <!-- Tabs -->
        <div class="chart-card">
            <div class="chart-header">
                <div class="tabs">
                    <button type="button" class="tab-button active" data-tab="data-karyawan">Data Karyawan</button>
                    <button type="button" class="tab-button" data-tab="performa">Sistem Performa</button>
                </div>
            </div>

            <!-- Tab Content: Data Karyawan -->
            <div id="data-karyawan" class="tab-content active" style="padding: 1.5rem;">
                <div class="table-responsive">
                    <table id="tableKaryawan" class="table table-hover w-100">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">No</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Nama</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Jabatan</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Telepon</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Alamat</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Status</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Tanggal Masuk</th>
                                <th class="border-0 text-center" style="font-weight: 600; color: #4b5563;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            if (!empty($karyawan_list)):
                                foreach ($karyawan_list as $row):
                                    ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td class="text-muted"><?= htmlspecialchars($row->kry_nama); ?></td>
                                        <td class="text-muted"><?= htmlspecialchars($row->kry_level); ?></td>
                                        <td class="text-muted"><?= htmlspecialchars($row->kry_telp ?? '-'); ?></td>
                                        <td class="text-muted"><?= htmlspecialchars($row->kry_alamat ?? '-'); ?></td>
                                        <td>
                                            <?php $status = isset($row->kry_status) ? $row->kry_status : 'Aktif'; ?>
                                            <span class="badge" style="background-color: <?= $status == 'Aktif' ? '#10b981' : ($status == 'Cuti' ? '#f59e0b' : '#ef4444') ?>; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem;">
                                                <?= htmlspecialchars($status); ?>
                                            </span>
                                        </td>
                                        <td class="text-muted">
                                            <?= $row->kry_join_date ? date('d/m/Y', strtotime($row->kry_join_date)) : '-'; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary edit-btn"
                                                        data-kode="<?= $row->kry_kode; ?>"
                                                        data-nama="<?= htmlspecialchars($row->kry_nama); ?>"
                                                        data-level="<?= htmlspecialchars($row->kry_level); ?>"
                                                        data-tlp="<?= htmlspecialchars($row->kry_telp ?? ''); ?>"
                                                        data-alamat="<?= htmlspecialchars($row->kry_alamat ?? ''); ?>"
                                                        data-status="<?= htmlspecialchars(isset($row->kry_status) ? $row->kry_status : 'Aktif'); ?>"
                                                        data-tgl-masuk="<?= $row->kry_join_date; ?>">
                                                    <i data-feather="edit-2" style="width: 16px; height: 16px;"></i>
                                                </button>
                                                <a href="<?= url('HR/delete_karyawan/' . $row->kry_kode); ?>"
                                                   class="btn btn-sm btn-outline-danger onclick-confirm">
                                                    <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i data-feather="inbox" style="width: 48px; height: 48px;"></i>
                                        <p class="mt-2">Belum ada data karyawan</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab Content: Sistem Performa -->
            <div id="performa" class="tab-content" style="padding: 1.5rem;">
                <div class="performa-container">
                    <!-- Performance Actions -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Sistem Poin Performa</h4>
                        <div>
                            <a href="<?= url('HR/input_performance'); ?>" class="btn btn-primary mr-2">
                                <i data-feather="plus-circle"></i> Input Poin Mingguan
                            </a>
                            <a href="<?= url('HR/calculate_performance'); ?>" class="btn btn-outline-primary">
                                <i data-feather="activity"></i> Hitung Rekap Bulanan
                            </a>
                        </div>
                    </div>

                    <!-- Monthly Performance Table -->
                    <div class="table-responsive">
                        <table id="tableMonthlyPerformance" class="table table-hover w-100">
                            <thead class="bg-light">
                                <tr>
                                    <th style="font-weight: 600; color: #4b5563;">Ranking</th>
                                    <th style="font-weight: 600; color: #4b5563;">Karyawan</th>
                                    <th style="font-weight: 600; color: #4b5563;">Jabatan</th>
                                    <th style="font-weight: 600; color: #4b5563;">Total Poin</th>
                                    <th style="font-weight: 600; color: #4b5563;">Persentase</th>
                                    <th style="font-weight: 600; color: #4b5563;">Level</th>
                                    <th style="font-weight: 600; color: #4b5563;">Periode</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $monthly_performance = [];
                                if (\Illuminate\Support\Facades\Schema::hasTable('performance_monthly')) {
                                    $mp = \Illuminate\Support\Facades\DB::table('performance_monthly as pm')
                                        ->leftJoin('karyawan as k', 'pm.kry_kode', '=', 'k.kry_kode')
                                        ->select('pm.*', 'k.kry_nama', 'k.kry_level')
                                        ->get();
                                    $monthly_performance = json_decode(json_encode($mp), true);
                                }
                                if (!empty($monthly_performance)):
                                    foreach ($monthly_performance as $perf):
                                        $level_color = '';
                                        switch ($perf['level']) {
                                            case 'Top Performer': $level_color = '#10b981'; break;
                                            case 'Advanced': $level_color = '#3b82f6'; break;
                                            case 'Intermediate': $level_color = '#f59e0b'; break;
                                            default: $level_color = '#ef4444';
                                        }
                                        ?>
                                        <tr>
                                            <td>
                                                <?php if ($perf['ranking']): ?>
                                                    <span class="badge" style="background-color: #f59e0b; color: white; font-size: 1rem; padding: 0.5rem;">
                                                        #<?= $perf['ranking']; ?>
                                                    </span>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td class="font-weight-bold"><?= htmlspecialchars($perf['kry_nama']); ?></td>
                                            <td class="text-muted"><?= htmlspecialchars($perf['kry_level']); ?></td>
                                            <td class="font-weight-bold"><?= number_format($perf['total_points'], 0); ?></td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar" role="progressbar"
                                                         style="width: <?= min($perf['percentage'], 100); ?>%; background-color: <?= $level_color; ?>">
                                                        <?= number_format($perf['percentage'], 1); ?>%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge" style="background-color: <?= $level_color; ?>; color: white; padding: 0.5rem;">
                                                    <?= htmlspecialchars($perf['level']); ?>
                                                </span>
                                            </td>
                                            <td class="text-muted"><?= htmlspecialchars($perf['periode']); ?></td>
                                        </tr>
                                    <?php endforeach;
                                else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i data-feather="bar-chart-2" style="width: 48px; height: 48px;"></i>
                                            <p class="mt-2">Belum ada data performa bulanan</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<!-- Modal Input/Edit Karyawan -->
<div class="modal" id="modalKaryawan">
    <div class="modal__content modal__content--lg p-5 intro-y box" style="max-height: 85vh; overflow-y: auto;">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium" id="modalTitle">Tambah Karyawan</h2>
            <a role="button" data-dismiss="modal" class="text-gray-500 hover:text-gray-700">
                <i data-feather="x" class="w-5 h-5"></i>
            </a>
        </div>
        <form action="" method="POST" id="karyawanForm">
            @csrf
            <input type="hidden" name="kry_kode" id="kry_kode">

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="font-medium text-sm">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="kry_nama" id="kry_nama" class="form-control w-full mt-1" required
                           placeholder="Masukkan nama lengkap">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="font-medium text-sm">Jabatan <span class="text-red-500">*</span></label>
                    <input type="text" name="kry_level" id="kry_level" class="form-control w-full mt-1" required
                           placeholder="Contoh: Teknisi Senior">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-medium text-sm">Telepon</label>
                    <input type="text" name="kry_telp" id="kry_telp" class="form-control w-full mt-1"
                           placeholder="081234567890">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="font-medium text-sm">Status <span class="text-red-500">*</span></label>
                    <select name="kry_status" id="kry_status" class="form-control w-full mt-1" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                        <option value="Cuti">Cuti</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-medium text-sm">Tanggal Masuk</label>
                    <input type="date" name="kry_join_date" id="kry_join_date" class="form-control w-full mt-1">
                </div>
            </div>


            <div class="mb-3">
                <label class="font-medium text-sm">Alamat</label>
                <textarea name="kry_alamat" id="kry_alamat" class="form-control w-full mt-1" rows="3"
                          placeholder="Masukkan alamat lengkap"></textarea>
            </div>


            <div class="flex justify-end gap-2 pt-3 border-t">
                <a role="button" data-dismiss="modal" class="btn btn-secondary py-2 px-4">Batal</a>
                <button type="submit" class="btn btn-primary py-2 px-4" style="background-color: #3b82f6; color: white; border: none;">
                    <span id="submitText">Simpan Data</span>
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        // Tab functionality
        document.querySelectorAll('.tab-button').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const tabName = this.getAttribute('data-tab');

                document.querySelectorAll('.tab-content').forEach(function(content) {
                    content.classList.remove('active');
                });

                const targetTab = document.getElementById(tabName);
                if (targetTab) {
                    targetTab.classList.add('active');
                }

                document.querySelectorAll('.tab-button').forEach(function(btn) {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
            });
        });
    });

    function showModalKaryawan() {
        const modal = document.getElementById('modalKaryawan');
        if (modal) {
            modal.classList.add('show');
            modal.style.marginTop = '0px';
            modal.style.marginLeft = '0px';
            modal.style.visibility = 'visible';
            modal.style.opacity = '1';
            modal.style.display = 'block';
            document.body.classList.add('overflow-y-hidden');
        }
    }

    function hideModalKaryawan() {
        const modal = document.getElementById('modalKaryawan');
        if (modal) {
            modal.classList.remove('show');
            modal.style.marginTop = '';
            modal.style.marginLeft = '';
            modal.style.visibility = '';
            modal.style.opacity = '';
            modal.style.display = 'none';
            document.body.classList.remove('overflow-y-hidden');
        }
    }

    // Global Click Event Handler (handles buttons, icons, and text clicks)
    document.addEventListener('click', function(e) {
        // 1. Tambah Karyawan Button Click
        const addBtn = e.target.closest('.add-karyawan-btn');
        if (addBtn) {
            e.preventDefault();
            const form = document.getElementById('karyawanForm');
            
            if (document.getElementById('modalTitle')) document.getElementById('modalTitle').innerText = 'Tambah Karyawan';
            if (document.getElementById('formAction')) document.getElementById('formAction').value = 'save';
            if (document.getElementById('submitText')) document.getElementById('submitText').innerText = 'Simpan Data';
            if (form) form.action = '<?= url('HR/save_karyawan'); ?>';

            const kodeInput = document.getElementById('kry_kode');
            if (kodeInput) {
                kodeInput.value = '';
                kodeInput.readOnly = false;
            }
            if (document.getElementById('kry_nama')) document.getElementById('kry_nama').value = '';
            if (document.getElementById('kry_level')) document.getElementById('kry_level').value = '';
            if (document.getElementById('kry_telp')) document.getElementById('kry_telp').value = '';
            if (document.getElementById('kry_alamat')) document.getElementById('kry_alamat').value = '';
            if (document.getElementById('kry_status')) document.getElementById('kry_status').value = 'Aktif';
            if (document.getElementById('kry_join_date')) document.getElementById('kry_join_date').value = '';

            showModalKaryawan();
            return;
        }

        // 2. Edit Karyawan Button Click
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            e.preventDefault();
            const form = document.getElementById('karyawanForm');

            if (document.getElementById('modalTitle')) document.getElementById('modalTitle').innerText = 'Edit Karyawan';
            if (document.getElementById('formAction')) document.getElementById('formAction').value = 'update';
            if (document.getElementById('submitText')) document.getElementById('submitText').innerText = 'Update Data';
            if (form) form.action = '<?= url('HR/update_karyawan'); ?>';

            const ds = editBtn.dataset;
            const kodeInput = document.getElementById('kry_kode');
            if (kodeInput) {
                kodeInput.value = ds.kode || '';
                kodeInput.readOnly = true;
            }
            if (document.getElementById('kry_nama')) document.getElementById('kry_nama').value = ds.nama || '';
            if (document.getElementById('kry_level')) document.getElementById('kry_level').value = ds.level || '';
            if (document.getElementById('kry_telp')) document.getElementById('kry_telp').value = ds.tlp || '';
            if (document.getElementById('kry_alamat')) document.getElementById('kry_alamat').value = ds.alamat || '';
            if (document.getElementById('kry_status')) document.getElementById('kry_status').value = ds.status || 'Aktif';
            if (document.getElementById('kry_join_date')) document.getElementById('kry_join_date').value = ds.tglMasuk || '';

            showModalKaryawan();
            return;
        }

        // 3. Delete Confirmation Button Click
        const deleteBtn = e.target.closest('.onclick-confirm');
        if (deleteBtn) {
            e.preventDefault();
            const href = deleteBtn.getAttribute('href');
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Hapus karyawan ini?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            } else {
                if (confirm('Hapus karyawan ini? Data yang dihapus tidak dapat dikembalikan!')) {
                    window.location.href = href;
                }
            }
            return;
        }

        // 4. Modal Close Button
        const dismissBtn = e.target.closest('[data-dismiss="modal"]');
        if (dismissBtn) {
            e.preventDefault();
            hideModalKaryawan();
            return;
        }

        // 5. Modal Backdrop Click
        const modal = document.getElementById('modalKaryawan');
        if (modal && e.target === modal) {
            hideModalKaryawan();
        }
    });
</script>
@endsection