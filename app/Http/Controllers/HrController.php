<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Interview;
use App\Models\Karyawan;
use App\Models\Kpi;
// use App\Models\Pencatatan;
use App\Models\LaporanMingguan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HrController extends Controller
{
    // ==========================================
    // OVERVIEW
    // ==========================================
    public function index(Request $request)
    {
        $periode = $request->query('periode', 'hari_ini');

        $start_date = null;
        $end_date = null;
        $today = date('Y-m-d');

        if ($periode == 'hari_ini') {
            $start_date = $end_date = $today;
        } elseif ($periode == 'minggu_ini') {
            $start_date = date('Y-m-d', strtotime('monday this week'));
            $end_date = date('Y-m-d', strtotime('sunday this week'));
        } elseif ($periode == 'bulan_ini') {
            $start_date = date('Y-m-01');
            $end_date = date('Y-m-t');
        } elseif ($periode == 'tanggal') {
            $start_date = $end_date = $request->query('tanggal', $today);
        }

        $absensi_data = Absensi::whereBetween('tanggal', [$start_date, $end_date])->get();
        $total_karyawan = Karyawan::whereNotIn('kry_level', ['Magang / PKL', 'Magang', 'PKL'])->count();
        $total_magang = Karyawan::whereIn('kry_level', ['Magang / PKL', 'Magang', 'PKL'])->count();
        $kpi_count = LaporanMingguan::where('periode', date('Y-m'))->count();

        $stats = [
            'total_karyawan' => $total_karyawan,
            'total_magang' => $total_magang,
            'hadir' => $absensi_data->whereIn('status', ['HADIR'])->count(),
            'izin' => $absensi_data->whereIn('status', ['IZIN', 'SAKIT', 'CUTI'])->count(),
            'telat' => $absensi_data->whereIn('status', ['TELAT'])->count(),
            'alpa' => $absensi_data->whereIn('status', ['ALPA'])->count(),
            'kpi_count' => $kpi_count,
        ];

        $recent_absensi = Absensi::with('karyawan')
            ->whereBetween('tanggal', [$start_date, $end_date])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $recent_kpi = LaporanMingguan::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $now = Carbon::now();
        $upcoming_interviews = Interview::where('status', 'Menunggu')
            ->where('tanggal_waktu', '>=', $now->copy()->subHours(2))
            ->where('tanggal_waktu', '<=', $now->copy()->addHours(24))
            ->orderBy('tanggal_waktu', 'asc')
            ->get();

        return view('hr.overview', [
            'title' => 'HR Dashboard & Overview',
            'selected_periode' => $periode,
            'selected_date' => $start_date,
            'stats' => $stats,
            'recent_absensi' => $recent_absensi,
            'recent_kpi' => $recent_kpi,
            'upcoming_interviews' => $upcoming_interviews,
        ]);
    }

    // ==========================================
    // KARYAWAN
    // ==========================================
    public function karyawan()
    {
        $karyawan_list = Karyawan::whereNotIn('kry_level', ['Magang / PKL', 'Magang', 'PKL'])
            ->orderBy('kry_nama', 'asc')
            ->get();

        $magang_list = Karyawan::whereIn('kry_level', ['Magang / PKL', 'Magang', 'PKL'])
            ->orderBy('kry_nama', 'asc')
            ->get();

        return view('hr.karyawan', [
            'title' => 'Data Karyawan',
            'karyawan_list' => $karyawan_list,
            'magang_list' => $magang_list,
        ]);
    }

    public function save_magang(Request $request)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        $rfid = $request->rfid ? trim($request->rfid) : null;
        if (empty($rfid)) {
            $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $request->nama));
            if (empty($baseUsername)) {
                $baseUsername = 'magang';
            }
            $rfid = $baseUsername.rand(100, 999);
        }

        // Check if RFID / Username already exists in database
        $existing = Karyawan::where('kry_username', $rfid)->first();
        if ($existing) {
            return back()->with('error', "Kode / Kartu RFID '{$rfid}' sudah terdaftar atas nama '{$existing->kry_nama}'. Harap gunakan kartu RFID lain.");
        }

        Karyawan::create([
            'kry_username' => $rfid,
            'kry_pswd' => Hash::make('123456'),
            'kry_nama' => $request->nama,
            'kry_level' => 'Magang / PKL',
            'kry_telp' => $request->tlp,
            'kry_alamat' => $request->alamat,
            'kry_status' => 1,
            'kry_join_date' => date('Y-m-d'),
        ]);

        return back()->with('sukses', 'Peserta Magang / PKL berhasil ditambahkan');
    }

    public function save_karyawan(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'level' => 'required',
        ]);

        $username = $request->username;
        if (empty($username)) {
            $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $request->nama));
            if (empty($baseUsername)) {
                $baseUsername = 'karyawan';
            }
            $username = $baseUsername.rand(100, 999);
        }

        $existing = Karyawan::where('kry_username', $username)->first();
        if ($existing) {
            return back()->with('error', "Username / RFID '{$username}' sudah terdaftar atas nama '{$existing->kry_nama}'. Harap gunakan RFID/Username lain.");
        }

        $password = $request->pswd ? Hash::make($request->pswd) : Hash::make('123456');

        // Pertahanan: Konversi otomatis teks ke angka
        $status_input = $request->status;
        $status_angka = 1; // Default 1 (Aktif)
        if ($status_input === 'Tidak Aktif' || $status_input == '0') {
            $status_angka = 0;
        } elseif ($status_input === 'Cuti' || $status_input == '2') {
            $status_angka = 2;
        }

        Karyawan::create([
            'kry_nik' => $request->nik,
            'kry_username' => $username,
            'kry_pswd' => $password,
            'kry_nama' => $request->nama,
            'kry_level' => $request->level,
            'kry_telp' => $request->tlp,
            'kry_alamat' => $request->alamat,
            'kry_status' => $status_angka, // Gunakan variabel yang sudah dikonversi
            'kry_join_date' => $request->tgl_masuk ?? date('Y-m-d'),
        ]);

        return back()->with('sukses', 'Karyawan berhasil ditambahkan');
    }

    public function update_karyawan(Request $request)
    {
        $request->validate([
            'kode' => 'required',
            'nama' => 'required',
        ]);

        if ($request->filled('username')) {
            $existing = Karyawan::where('kry_username', $request->username)
                ->where('kry_kode', '!=', $request->kode)
                ->first();

            if ($existing) {
                return back()->with('error', "Username / RFID '{$request->username}' sudah digunakan oleh '{$existing->kry_nama}'.");
            }
        }

        // Pertahanan: Konversi otomatis teks ke angka
        $status_input = $request->status;
        $status_angka = 1; // Default 1 (Aktif)
        if ($status_input === 'Tidak Aktif' || $status_input == '0') {
            $status_angka = 0;
        } elseif ($status_input === 'Cuti' || $status_input == '2') {
            $status_angka = 2;
        }

        $karyawan = Karyawan::where('kry_kode', $request->kode)->firstOrFail();

        $updateData = [
            'kry_nik' => $request->nik,
            'kry_nama' => $request->nama,
            'kry_telp' => $request->tlp,
            'kry_alamat' => $request->alamat,
            'kry_status' => $status_angka, // Gunakan variabel yang sudah dikonversi
            'kry_join_date' => $request->tgl_masuk ?? $karyawan->kry_join_date,
        ];

        if ($request->filled('username')) {
            $updateData['kry_username'] = $request->username;
        }

        if ($request->has('level') && ! empty($request->level)) {
            $updateData['kry_level'] = $request->level;
        }

        $karyawan->update($updateData);

        return back()->with('sukses', 'Data karyawan berhasil diperbarui');
    }

    public function delete_karyawan(string $kode)
    {
        Karyawan::where('kry_kode', $kode)->delete();

        return back()->with('sukses', 'Karyawan berhasil dihapus');
    }

    public function export_pdf()
    {
        $karyawan_list = Karyawan::orderBy('kry_kode', 'asc')->get();

        $pdf = Pdf::loadView('hr.export_karyawan_pdf', ['karyawan_list' => $karyawan_list])
            ->setPaper('a4', 'landscape');

        return $pdf->download('data_karyawan_'.date('Y-m-d').'.pdf');
    }

    // ==========================================
    // ABSENSI
    // ==========================================
    public function absensi(Request $request)
    {
        $tanggal = $request->query('tanggal', date('Y-m-d'));

        $absensi_list = Absensi::with('karyawan')
            ->where('tanggal', $tanggal)
            ->orderBy('jam_masuk', 'asc')
            ->get();

        $karyawan_list = Karyawan::orderBy('kry_nama', 'asc')->get();

        return view('hr.absensi', [
            'title' => 'Absensi Karyawan',
            'selected_date' => $tanggal,
            'absensi_list' => $absensi_list,
            'karyawan_list' => $karyawan_list,
        ]);
    }

    public function save_absensi(Request $request)
    {
        // Handle RFID / USB Card Reader Scan
        if ($request->filled('rfid_code')) {
            $rfid = trim($request->rfid_code);
            $rfidClean = ltrim($rfid, '0');

            $karyawan = Karyawan::where('kry_kode', $rfid)
                ->orWhere('kry_kode', $rfidClean)
                ->orWhere('kry_username', $rfid)
                ->orWhere('kry_username', $rfidClean)
                ->orWhere('kry_nama', 'LIKE', "%{$rfid}%")
                ->first();

            if (! $karyawan) {
                return back()->with('error', "Karyawan dengan Kode / Kartu ID '{$rfid}' tidak ditemukan.");
            }

            $tanggal = $request->input('tanggal', date('Y-m-d'));
            $nowTime = date('H:i:s');
            $scanMode = $request->input('scan_mode', 'auto');

            session(['active_scan_mode' => $scanMode]);

            $absensi = Absensi::firstOrNew([
                'tanggal' => $tanggal,
                'id_karyawan' => $karyawan->kry_kode,
            ]);

            $absensi->nama_karyawan = $karyawan->kry_nama;
            $absensi->posisi = $karyawan->kry_level;
            $absensi->status = $absensi->status ?? 'HADIR';

            $pesan = '';

            if ($scanMode === 'masuk') {
                if (! empty($absensi->jam_masuk)) {
                    return back()->with('error', "{$karyawan->kry_nama} sudah melakukan absensi Jam Masuk.");
                }
                $absensi->jam_masuk = $nowTime;
                $pesan = "Jam Masuk ({$nowTime}) berhasil dicatat untuk {$karyawan->kry_nama}";
            } elseif ($scanMode === 'istirahat') {
                if (! empty($absensi->jam_istirahat)) {
                    return back()->with('error', "{$karyawan->kry_nama} sudah melakukan absensi Jam Istirahat.");
                }
                $absensi->jam_istirahat = $nowTime;
                $pesan = "Jam Istirahat ({$nowTime}) berhasil dicatat untuk {$karyawan->kry_nama}";
            } elseif ($scanMode === 'kembali_istirahat') {
                if (! empty($absensi->jam_kembali_istirahat)) {
                    return back()->with('error', "{$karyawan->kry_nama} sudah melakukan absensi Kembali Istirahat.");
                }
                $absensi->jam_kembali_istirahat = $nowTime;
                $pesan = "Jam Kembali Istirahat ({$nowTime}) berhasil dicatat untuk {$karyawan->kry_nama}";
            } elseif ($scanMode === 'pulang') {
                if (! empty($absensi->jam_pulang)) {
                    return back()->with('error', "{$karyawan->kry_nama} sudah melakukan absensi Jam Pulang.");
                }
                $absensi->jam_pulang = $nowTime;
                $pesan = "Jam Pulang ({$nowTime}) berhasil dicatat untuk {$karyawan->kry_nama}";
            } else {
                // Auto Sequential Detection
                if (empty($absensi->jam_masuk)) {
                    $absensi->jam_masuk = $nowTime;
                    $pesan = "Jam Masuk ({$nowTime}) berhasil dicatat untuk {$karyawan->kry_nama}";
                } elseif (empty($absensi->jam_istirahat)) {
                    $absensi->jam_istirahat = $nowTime;
                    $pesan = "Jam Istirahat ({$nowTime}) berhasil dicatat untuk {$karyawan->kry_nama}";
                } elseif (empty($absensi->jam_kembali_istirahat)) {
                    $absensi->jam_kembali_istirahat = $nowTime;
                    $pesan = "Jam Kembali Istirahat ({$nowTime}) berhasil dicatat untuk {$karyawan->kry_nama}";
                } elseif (empty($absensi->jam_pulang)) {
                    $absensi->jam_pulang = $nowTime;
                    $pesan = "Jam Pulang ({$nowTime}) berhasil dicatat untuk {$karyawan->kry_nama}";
                } else {
                    return back()->with('error', "{$karyawan->kry_nama} sudah menyelesaikan seluruh absensi (masuk sampai pulang) hari ini.");
                }
            }

            $absensi->save();

            return back()->with('sukses', $pesan);
        }

        // Manual form submit or Edit Modal submit
        $request->validate([
            'tanggal' => 'required|date',
            'id_karyawan' => 'required',
        ]);

        $karyawan = Karyawan::where('kry_kode', $request->id_karyawan)->firstOrFail();

        $absensi = Absensi::where('tanggal', $request->tanggal)
            ->where('id_karyawan', $request->id_karyawan)
            ->first();

        // Jika ini bukan dari modal edit, dan data absensi sudah ada, tolak input manual baru
        if (! $request->has('is_edit') && $absensi) {
            return back()->with('error', "Karyawan {$karyawan->kry_nama} sudah diabsen pada tanggal ini. Gunakan fitur Edit pada tabel jika ingin mengubah datanya.");
        }

        if (! $absensi) {
            $absensi = new Absensi;
            $absensi->tanggal = $request->tanggal;
            $absensi->id_karyawan = $request->id_karyawan;
        }

        $absensi->nama_karyawan = $karyawan->kry_nama;
        $absensi->posisi = $karyawan->kry_level;
        $absensi->status = $request->status ?? $absensi->status ?? 'HADIR';

        // Update timestamps individually or all at once
        if ($request->has('jam_masuk')) {
            $absensi->jam_masuk = $request->jam_masuk ?: null;
        }
        if ($request->has('jam_istirahat')) {
            $absensi->jam_istirahat = $request->jam_istirahat ?: null;
        }
        if ($request->has('jam_kembali_istirahat')) {
            $absensi->jam_kembali_istirahat = $request->jam_kembali_istirahat ?: null;
        }
        if ($request->has('jam_pulang')) {
            $absensi->jam_pulang = $request->jam_pulang ?: null;
        }
        if ($request->has('keterangan')) {
            $absensi->keterangan = $request->keterangan;
        }

        $absensi->save();

        return back()->with('sukses', "Data Absensi untuk {$karyawan->kry_nama} berhasil disimpan");
    }

    public function delete_absensi($id)
    {
        Absensi::findOrFail($id)->delete();

        return back()->with('sukses', 'Absensi dihapus');
    }

    // ==========================================
    // KPI (LAPORAN PERFORMANCE MINGGUAN)
    // ==========================================
    public function kpi(Request $request)
    {
        $periode = $request->query('periode', date('Y-m'));

        $laporan_list = LaporanMingguan::with('karyawan')->where('periode', $periode)->get();
        $karyawan_list = Karyawan::orderBy('kry_nama', 'asc')->get();

        return view('hr.laporan_mingguan', [
            'title' => 'KPI Karyawan',
            'selected_periode' => $periode,
            'laporan_list' => $laporan_list,
            'karyawan_list' => $karyawan_list,
        ]);
    }

    public function save_kpi(Request $request)
    {
        $karyawan = Karyawan::where('kry_kode', $request->id_karyawan)->first();

        if (! $karyawan) {
            return back()->with('gagal', 'Data karyawan tidak ditemukan');
        }

        LaporanMingguan::updateOrCreate(
            [
                'id_karyawan' => $request->id_karyawan,
                'periode' => $request->periode ?? date('Y-m'),
            ],
            [
                'nama_karyawan' => $karyawan->kry_nama,
                'posisi' => $karyawan->kry_level,
                'target_mingguan' => $request->target_mingguan,
                'tugas_dilakukan' => $request->tugas_dilakukan,
                'hasil' => $request->hasil,
                'kendala' => $request->kendala,
                'solusi' => $request->solusi,
            ]
        );

        return back()->with('sukses', 'KPI berhasil disimpan');
    }

    public function delete_kpi($id)
    {
        $laporan = LaporanMingguan::find($id);
        if ($laporan) {
            $laporan->delete();
        }

        return back()->with('sukses', 'KPI dihapus');
    }

    public function template_kpi()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_import_kpi.csv"',
        ];

        $columns = ['NAMA KARYAWAN', 'PERIODE', 'TARGET MINGGUAN', 'TUGAS DILAKUKAN', 'HASIL', 'KENDALA', 'SOLUSI'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            // Sample Rows
            fputcsv($file, ['Budi Santoso', date('Y-m'), 'Pencapaian Target Omset 100%', 'Followup customer & penawaran', 'Target tercapai 100%', 'Server terdistribusi', 'Menggunakan backup lokal']);
            fputcsv($file, ['Siti Rahmawati', date('Y-m'), 'Rekap Laporan Keuangan Harian', 'Input data transaksi', '98% Laporan selesai', 'Koneksi lambat', 'Gunakan jaringan sekunder']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import_kpi(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:csv,txt,xlsx,xls',
        ]);

        $file = $request->file('file_excel');
        $path = $file->getRealPath();
        $ext = strtolower($file->getClientOriginalExtension());

        $rows = [];

        if (in_array($ext, ['csv', 'txt'])) {
            if (($handle = fopen($path, 'r')) !== false) {
                $firstLine = fgets($handle);
                rewind($handle);
                $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ((strpos($firstLine, "\t") !== false) ? "\t" : ',');

                while (($data = fgetcsv($handle, 2000, $delimiter)) !== false) {
                    if (array_filter($data)) {
                        $rows[] = array_map('trim', $data);
                    }
                }
                fclose($handle);
            }
        } elseif ($ext === 'xlsx' || $ext === 'xls') {
            $zip = new \ZipArchive;
            if ($zip->open($path) === true) {
                $sharedStrings = [];
                if (($sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml')) !== false) {
                    $xml = simplexml_load_string($sharedStringsXml);
                    if ($xml && isset($xml->si)) {
                        foreach ($xml->si as $val) {
                            $text = '';
                            if (isset($val->t)) {
                                $text = (string) $val->t;
                            } elseif (isset($val->r)) {
                                foreach ($val->r as $run) {
                                    $text .= (string) $run->t;
                                }
                            }
                            $sharedStrings[] = $text;
                        }
                    }
                }

                if (($sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml')) !== false) {
                    $xml = simplexml_load_string($sheetXml);
                    if ($xml && isset($xml->sheetData->row)) {
                        foreach ($xml->sheetData->row as $rowXml) {
                            $rowCells = [];
                            foreach ($rowXml->c as $cell) {
                                $cellType = (string) $cell['t'];
                                $val = (string) $cell->v;
                                if ($cellType === 's' && isset($sharedStrings[(int) $val])) {
                                    $val = $sharedStrings[(int) $val];
                                }
                                $rowCells[] = trim($val);
                            }
                            if (array_filter($rowCells)) {
                                $rows[] = $rowCells;
                            }
                        }
                    }
                }
                $zip->close();
            }
        }

        if (empty($rows)) {
            return back()->with('error', 'Gagal membaca isi file Excel / CSV atau file kosong.');
        }

        $importedCount = 0;
        $defaultPeriode = $request->input('periode', date('Y-m'));

        foreach ($rows as $index => $row) {
            if ($index === 0 && (str_contains(strtolower($row[0] ?? ''), 'nama') || str_contains(strtolower($row[0] ?? ''), 'karyawan'))) {
                continue;
            }

            $namaKaryawan = $row[0] ?? null;
            if (! $namaKaryawan) {
                continue;
            }

            $karyawan = Karyawan::where('kry_nama', 'LIKE', "%{$namaKaryawan}%")
                ->orWhere('kry_kode', $namaKaryawan)
                ->orWhere('kry_username', $namaKaryawan)
                ->first();

            if (! $karyawan) {
                continue;
            }

            $periode = ! empty($row[1]) ? trim($row[1]) : $defaultPeriode;
            $targetMingguan = $row[2] ?? null;
            $tugasDilakukan = $row[3] ?? null;
            $hasil = $row[4] ?? null;
            $kendala = $row[5] ?? null;
            $solusi = $row[6] ?? null;

            LaporanMingguan::updateOrCreate(
                [
                    'id_karyawan' => $karyawan->kry_kode,
                    'periode' => $periode,
                ],
                [
                    'nama_karyawan' => $karyawan->kry_nama,
                    'posisi' => $karyawan->kry_level,
                    'target_mingguan' => $targetMingguan,
                    'tugas_dilakukan' => $tugasDilakukan,
                    'hasil' => $hasil,
                    'kendala' => $kendala,
                    'solusi' => $solusi,
                ]
            );

            $importedCount++;
        }

        if ($importedCount === 0) {
            return back()->with('error', 'Tidak ada data karyawan yang cocok untuk diimpor. Pastikan Nama Karyawan sesuai dengan data di sistem.');
        }

        return back()->with('sukses', "Berhasil mengimpor {$importedCount} data KPI Karyawan dari file Excel / CSV!");
    }

    public function laporan_mingguan(Request $request)
    {
        return $this->kpi($request);
    }

    public function save_laporan_mingguan(Request $request)
    {
        return $this->save_kpi($request);
    }

    public function delete_laporan_mingguan($id)
    {
        return $this->delete_kpi($id);
    }

    // ==========================================
    // PENCATATAN BARANG MASUK
    // ==========================================
    // public function pencatatan(Request $request)
    // {
    //     $tanggal = $request->query('tanggal', date('Y-m-d'));

    //     $pencatatan_list = Pencatatan::where('tanggal', $tanggal)->get()->groupBy('batch_id');

    //     return view('hr.pencatatan', [
    //         'title' => 'Pencatatan Barang',
    //         'selected_date' => $tanggal,
    //         'pencatatan_list' => $pencatatan_list,
    //     ]);
    // }

    // public function save_pencatatan(Request $request)
    // {
    //     $batch_id = 'BATCH_'.time().'_'.rand(1000, 9999);
    //     $tanggal = $request->input('tanggal');

    //     $nama_barangs = $request->input('nama_barang', []);
    //     $qtys = $request->input('qty', []);
    //     $harga_satuans = $request->input('harga_satuan', []);
    //     $kategori_global = $request->input('kategori_global');

    //     // Handle File Upload
    //     $gambar_path = null;
    //     if ($request->hasFile('gambar')) {
    //         $file = $request->file('gambar');
    //         $filename = time().'_'.$file->getClientOriginalName();
    //         $file->move(public_path('uploads/barang'), $filename);
    //         $gambar_path = 'uploads/barang/'.$filename;
    //     }

    //     DB::beginTransaction();
    //     try {
    //         foreach ($nama_barangs as $index => $nama) {
    //             if (empty($nama)) {
    //                 continue;
    //             }
    //             $qty = $qtys[$index] ?? 1;
    //             $harga = $harga_satuans[$index] ?? 0;
    //             $total = $qty * $harga;

    //             Pencatatan::create([
    //                 'batch_id' => $batch_id,
    //                 'nama_barang' => $nama,
    //                 'qty' => $qty,
    //                 'harga_satuan' => $harga,
    //                 'total' => $total,
    //                 'tanggal' => $tanggal,
    //                 'gambar' => $gambar_path,
    //                 'kategori_global' => $kategori_global,
    //             ]);
    //         }
    //         DB::commit();

    //         return back()->with('sukses', 'Pencatatan berhasil disimpan');
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return back()->with('gagal', 'Terjadi kesalahan saat menyimpan data');
    //     }
    // }

    // public function delete_pencatatan($id)
    // {
    //     Pencatatan::where('batch_id', $id)->delete();

    //     return back()->with('sukses', 'Batch Pencatatan dihapus');
    // }
    public function rekap(Request $request)
    {
        $tanggal = $request->query('tanggal', date('Y-m-d'));
        $periode = $request->query('periode', date('Y-m'));

        // Ambil semua data karyawan
        $karyawan_list = Karyawan::orderBy('kry_nama', 'asc')->get();

        // Absensi harian berdasarkan tanggal (keyed by id_karyawan)
        $absensi_harian = Absensi::where('tanggal', $tanggal)->get()->keyBy('id_karyawan');

        // Semua riwayat absensi pada tanggal tersebut
        $all_absensi = Absensi::with('karyawan')
            ->where('tanggal', $tanggal)
            ->orderBy('created_at', 'desc')
            ->get();

        // KPI (Laporan Performance) mingguan berdasarkan periode (keyed by id_karyawan)
        $kpi_periode = LaporanMingguan::where('periode', $periode)->get()->keyBy('id_karyawan');

        // Semua riwayat KPI pada periode tersebut
        $all_kpi = LaporanMingguan::with('karyawan')
            ->where('periode', $periode)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('hr.rekap', [
            'title' => 'Rekap HR',
            'karyawan_list' => $karyawan_list,
            'selected_date' => $tanggal,
            'selected_periode' => $periode,
            'absensi_harian' => $absensi_harian,
            'all_absensi' => $all_absensi,
            'kpi_periode' => $kpi_periode,
            'all_kpi' => $all_kpi,
        ]);
    }

    public function certificate_generator()
    {
        $karyawan_list = Karyawan::orderBy('kry_nama', 'asc')->get();

        return view('hr.certificate_generator', [
            'title' => 'Generator Sertifikat',
            'karyawan_list' => $karyawan_list,
        ]);
    }

    public function calculate_performance(Request $request)
    {
        return view('hr.calculate_performance', [
            'title' => 'Hitung Rekap Performa Bulanan',
        ]);
    }

    // ==========================================
    // SISTEM POIN PERFORMA
    // ==========================================
    public function input_performance(Request $request)
    {
        // Mengambil data karyawan untuk pilihan dropdown
        $karyawan_list = Karyawan::orderBy('kry_nama', 'asc')->get();

        return view('hr.input_performance', [
            'title' => 'Input Poin Performa Mingguan',
            'karyawan_list' => $karyawan_list,
        ]);
    }

    public function save_performance(Request $request)
    {
        // Di sini nantinya Anda bisa menambahkan logika validasi dan insert database
        // Contoh dasar kembalian sukses:
        return back()->with('sukses', 'Poin performa mingguan berhasil disimpan');
    }

    public function Interview(Request $request)
    {
        $query = Interview::orderBy('tanggal_waktu', 'desc');

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_kandidat', 'like', "%{$search}%")
                    ->orWhere('posisi', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('catatan', 'like', "%{$search}%");
            });
        }

        $interview_list = $query->get();

        $now = Carbon::now();
        $upcoming_interviews = Interview::where('status', 'Menunggu')
            ->where('tanggal_waktu', '>=', $now->copy()->subHours(2))
            ->where('tanggal_waktu', '<=', $now->copy()->addHours(24))
            ->orderBy('tanggal_waktu', 'asc')
            ->get();

        return view('hr.interview', [
            'title' => 'Jadwal & Hasil Interview',
            'interview_list' => $interview_list,
            'upcoming_interviews' => $upcoming_interviews,
        ]);
    }

    public function save_interview(Request $request)
    {
        // 1. Validasi data agar lebih aman
        $validatedData = $request->validate([
            'nama_kandidat' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'tanggal_waktu' => 'required|date',
            'catatan' => 'nullable|string', // catatan bersifat opsional
        ]);

        try {
            // 2. Simpan data ke database
            Interview::create($validatedData);

            // 3. Redirect kembali dengan pesan sukses
            return back()->with('sukses', 'Jadwal interview berhasil ditambahkan.');

        } catch (\Exception $e) {
            // Jika terjadi error database, kembalikan user beserta input sebelumnya
            return back()->with('error', 'Gagal menambahkan jadwal: '.$e->getMessage())->withInput();
        }
    }
}
