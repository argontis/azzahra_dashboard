<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Arsip;
use App\Models\Karyawan;
use App\Models\Kpi;
use App\Models\LaporanMingguan;
use App\Models\Pencatatan;
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

        $stats = [
            'hadir' => $absensi_data->whereIn('status', ['HADIR'])->count(),
            'izin' => $absensi_data->whereIn('status', ['IZIN', 'SAKIT', 'CUTI'])->count(),
            'telat' => $absensi_data->whereIn('status', ['TELAT'])->count(),
            'alpa' => $absensi_data->whereIn('status', ['ALPA'])->count(),
        ];

        return view('hr.overview', [
            'title' => 'HR Dashboard',
            'selected_periode' => $periode,
            'stats' => $stats,
        ]);
    }

    // ==========================================
    // KARYAWAN
    // ==========================================
    public function karyawan()
    {
        $karyawan_list = Karyawan::orderBy('kry_nama', 'asc')->get();

        return view('hr.karyawan', [
            'title' => 'Data Karyawan',
            'karyawan_list' => $karyawan_list,
        ]);
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
            'kry_nama' => $request->nama,
            'kry_telp' => $request->tlp,
            'kry_alamat' => $request->alamat,
            'kry_status' => $status_angka, // Gunakan variabel yang sudah dikonversi
            'kry_join_date' => $request->tgl_masuk,
        ];
        
        if ($request->has('level') && !empty($request->level)) {
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

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('hr.export_karyawan_pdf', ['karyawan_list' => $karyawan_list])
            ->setPaper('a4', 'landscape');

        return $pdf->download('data_karyawan_' . date('Y-m-d') . '.pdf');
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
        $request->validate([
            'tanggal' => 'required|date',
            'id_karyawan' => 'required',
            'status' => 'required',
        ]);

        $karyawan = Karyawan::where('kry_kode', $request->id_karyawan)->firstOrFail();

        Absensi::updateOrCreate(
            [
                'tanggal' => $request->tanggal,
                'id_karyawan' => $request->id_karyawan,
            ],
            [
                'nama_karyawan' => $karyawan->kry_nama,
                'posisi' => $karyawan->kry_level,
                'status' => $request->status,
                'jam_masuk' => $request->jam_masuk,
                'jam_pulang' => $request->jam_pulang,
                'keterangan' => $request->keterangan,
            ]
        );

        return back()->with('sukses', 'Absensi berhasil disimpan');
    }

    public function delete_absensi($id)
    {
        Absensi::findOrFail($id)->delete();

        return back()->with('sukses', 'Absensi dihapus');
    }

    // ==========================================
    // KPI
    // ==========================================
    public function kpi(Request $request)
    {
        $periode = $request->query('periode', date('Y-m-d'));

        $kpi_list = Kpi::with('karyawan')
            ->where('periode', $periode)
            ->get();

        $karyawan_list = Karyawan::orderBy('kry_nama', 'asc')->get();

        return view('hr.kpi', [
            'title' => 'KPI Karyawan',
            'selected_periode' => $periode,
            'kpi_list' => $kpi_list,
            'karyawan_list' => $karyawan_list,
        ]);
    }

    public function save_kpi(Request $request)
    {
        $request->validate([
            'id_karyawan' => 'required',
            'periode' => 'required',
            'kedisiplinan' => 'required|numeric|min:0|max:5',
            'kualitas_kerja' => 'required|numeric|min:0|max:5',
            'produktivitas' => 'required|numeric|min:0|max:5',
            'kerja_tim' => 'required|numeric|min:0|max:5',
        ]);

        $karyawan = Karyawan::where('kry_kode', $request->id_karyawan)->firstOrFail();

        $dis = $request->kedisiplinan;
        $kua = $request->kualitas_kerja;
        $prod = $request->produktivitas;
        $team = $request->kerja_tim;
        $total = $dis + $kua + $prod + $team;
        $avg = $total / 4;

        $cat = 'Kurang';
        if ($avg >= 4.5) {
            $cat = 'Sangat Baik';
        } elseif ($avg >= 3.5) {
            $cat = 'Baik';
        } elseif ($avg >= 2.5) {
            $cat = 'Cukup';
        }

        Kpi::updateOrCreate(
            [
                'id_karyawan' => $request->id_karyawan,
                'periode' => $request->periode,
                'siklus' => 'harian',
            ],
            [
                'nama_karyawan' => $karyawan->kry_nama,
                'posisi' => $karyawan->kry_level,
                'status_kerja' => 'Karyawan',
                'kedisiplinan' => $dis,
                'kualitas_kerja' => $kua,
                'produktivitas' => $prod,
                'kerja_tim' => $team,
                'total' => $total,
                'rata_rata' => $avg,
                'kategori' => $cat,
                'catatan' => $request->catatan,
            ]
        );

        return back()->with('sukses', 'Data KPI berhasil disimpan');
    }

    public function delete_kpi($id)
    {
        Kpi::findOrFail($id)->delete();

        return back()->with('sukses', 'KPI dihapus');
    }

    // ==========================================
    // ARSIP
    // ==========================================
    public function arsip()
    {
        $arsip_dreame = Arsip::where('tipe', 'Dreame')->orderBy('tanggal', 'desc')->get();
        $arsip_laptop = Arsip::where('tipe', 'Laptop')->orderBy('tanggal', 'desc')->get();

        return view('hr.arsip', [
            'title' => 'Arsip Dokumen',
            'arsip_dreame' => $arsip_dreame,
            'arsip_laptop' => $arsip_laptop,
        ]);
    }

    public function save_arsip(Request $request)
    {
        $request->validate([
            'tipe' => 'required',
            'nama' => 'required',
            'tanggal' => 'required|date',
        ]);

        Arsip::create($request->all());

        return back()->with('sukses', 'Arsip berhasil ditambahkan');
    }

    public function delete_arsip($id)
    {
        Arsip::findOrFail($id)->delete();

        return back()->with('sukses', 'Arsip dihapus');
    }

    // ==========================================
    // LAPORAN MINGGUAN
    // ==========================================
    public function laporan_mingguan(Request $request)
    {
        $periode = $request->query('periode', date('Y').'-W'.date('W'));

        $laporan_list = LaporanMingguan::with('karyawan')->where('periode', $periode)->get();
        $karyawan_list = Karyawan::orderBy('kry_nama', 'asc')->get();

        return view('hr.laporan_mingguan', [
            'title' => 'Laporan Mingguan',
            'selected_periode' => $periode,
            'laporan_list' => $laporan_list,
            'karyawan_list' => $karyawan_list,
        ]);
    }

    public function save_laporan_mingguan(Request $request)
    {
        $karyawan = Karyawan::where('kry_kode', $request->id_karyawan)->firstOrFail();

        LaporanMingguan::updateOrCreate(
            [
                'id_karyawan' => $request->id_karyawan,
                'periode' => $request->periode,
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

        return back()->with('sukses', 'Laporan Mingguan berhasil disimpan');
    }

    public function delete_laporan_mingguan($id)
    {
        LaporanMingguan::findOrFail($id)->delete();

        return back()->with('sukses', 'Laporan Mingguan dihapus');
    }

    // ==========================================
    // PENCATATAN BARANG MASUK
    // ==========================================
    public function pencatatan(Request $request)
    {
        $tanggal = $request->query('tanggal', date('Y-m-d'));

        $pencatatan_list = Pencatatan::where('tanggal', $tanggal)->get()->groupBy('batch_id');

        return view('hr.pencatatan', [
            'title' => 'Pencatatan Barang',
            'selected_date' => $tanggal,
            'pencatatan_list' => $pencatatan_list,
        ]);
    }

    public function save_pencatatan(Request $request)
    {
        $batch_id = 'BATCH_'.time().'_'.rand(1000, 9999);
        $tanggal = $request->input('tanggal');

        $nama_barangs = $request->input('nama_barang', []);
        $qtys = $request->input('qty', []);
        $harga_satuans = $request->input('harga_satuan', []);
        $kategori_global = $request->input('kategori_global');

        // Handle File Upload
        $gambar_path = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/barang'), $filename);
            $gambar_path = 'uploads/barang/'.$filename;
        }

        DB::beginTransaction();
        try {
            foreach ($nama_barangs as $index => $nama) {
                if (empty($nama)) {
                    continue;
                }
                $qty = $qtys[$index] ?? 1;
                $harga = $harga_satuans[$index] ?? 0;
                $total = $qty * $harga;

                Pencatatan::create([
                    'batch_id' => $batch_id,
                    'nama_barang' => $nama,
                    'qty' => $qty,
                    'harga_satuan' => $harga,
                    'total' => $total,
                    'tanggal' => $tanggal,
                    'gambar' => $gambar_path,
                    'kategori_global' => $kategori_global,
                ]);
            }
            DB::commit();

            return back()->with('sukses', 'Pencatatan berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('gagal', 'Terjadi kesalahan saat menyimpan data');
        }
    }

    public function delete_pencatatan($id)
    {
        Pencatatan::where('batch_id', $id)->delete();

        return back()->with('sukses', 'Batch Pencatatan dihapus');
    }
    public function rekap()
    {
        // Anda bisa menambahkan logika untuk mengambil data rekap dari database di sini nantinya

        return view('hr.rekap', [
            'title' => 'Rekap HR',
        ]);
    }
    public function certificate_generator()
    {
        // Logika untuk generator sertifikat bisa ditambahkan di sini

        return view('hr.certificate_generator', [
            'title' => 'Generator Sertifikat',
        ]);
    }
    
    public function calculate_performance(Request $request)
    {
        return view('hr.calculate_performance', [
            'title' => 'Hitung Rekap Performa Bulanan'
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
            'karyawan_list' => $karyawan_list
        ]);
    }

    public function save_performance(Request $request)
    {
        // Di sini nantinya Anda bisa menambahkan logika validasi dan insert database
        // Contoh dasar kembalian sukses:
        return back()->with('sukses', 'Poin performa mingguan berhasil disimpan');
    }
    public function interview(Request $request)
    {
        // Nantinya Anda bisa mengambil data dari database, contoh: $interview_list = Interview::all();
        // Untuk sementara kita gunakan array kosong agar tampilannya rapi
        $interview_list = []; 

        return view('hr.interview', [
            'title' => 'Jadwal & Hasil Interview',
            'interview_list' => $interview_list
        ]);
    }
    public function save_interview(Request $request)
    {
        $request->validate([
            'nama_kandidat' => 'required',
            'posisi' => 'required',
            'tanggal_waktu' => 'required',
        ]);

        // Catatan: Jika Anda sudah membuat Model & Database untuk Interview, 
        // Anda bisa menyimpannya menggunakan: Interview::create($request->all());

        return back()->with('sukses', 'Jadwal interview berhasil ditambahkan');
    }
}
