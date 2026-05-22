<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    // 2. TAMBAHKAN FUNGSI INDEX (Pusat Data Dashboard)
    public function index()
    {
        $userId = Auth::id();
        $today = Carbon::today()->format('Y-m-d'); // YYYY-MM-DD

        if (Auth::user()->role == 'admin') {
            $riwayatAbsen = Absensi::with('user')->latest()->get();

            // whereDate agar lebih akurat mencari tanggal hari ini
            $totalHadir = Absensi::whereDate('tanggal', $today)->count();

            // hitung berdasarkan JAM agar tidak tertimpa status lain
            $totalTelat = Absensi::whereDate('tanggal', $today)
                ->where('jam_masuk', '>', '08:00:00')
                ->count();

            // Hitung yang sudah pulang DAN jam pulangnya sebelum jam 17:00
            $totalPulangCepat = Absensi::whereDate('tanggal', $today)
                ->whereNotNull('jam_keluar')
                ->where('jam_keluar', '<', '17:00:00')
                ->count();

            $totalPegawai = User::where('role', 'pegawai')->count();
        } else {
            $riwayatAbsen = Absensi::where('user_id', $userId)->latest()->get();
            // Pegawai tidak perlu statistik ini
            $totalHadir = $totalTelat = $totalPulangCepat = $totalPegawai = null;
        }

        $absenHariIni = Absensi::where('user_id', $userId)->where('tanggal', $today)->first();

        // Kirim semua variabel statistik ke Blade (Dashboard)
        return view('dashboard.dashboard', compact(
            'absenHariIni',
            'riwayatAbsen',
            'totalHadir',
            'totalTelat',
            'totalPulangCepat',
            'totalPegawai'
        ));
    }


    public function store(Request $request)
    {
        $request->validate([
            'foto_masuk' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $officeLat = -5.3615;
        $officeLong = 105.2415;

        if (!$request->location) {
            return back()->with('error', 'Lokasi tidak terdeteksi!');
        }

        $userLoc = explode(',', $request->location);
        $userLat = $userLoc[0];
        $userLong = $userLoc[1];

        $dist = sin(deg2rad($userLat)) * sin(deg2rad($officeLat)) +  cos(deg2rad($userLat)) * cos(deg2rad($officeLat)) * cos(deg2rad($officeLong - $userLong));
        $distanceInMeters = acos($dist) * 6371000;


        if ($distanceInMeters > 3000) {
            return back()->with('error', 'Di luar radius kantor! Jarak: ' . round($distanceInMeters) . ' meter');
        }

        $userID = Auth::id();
        $tanggalHariIni = Carbon::now()->format('Y-m-d');
        $jamSekarang = Carbon::now()->format('H:i:s');

        if (Absensi::where('user_id', $userID)->where('tanggal', $tanggalHariIni)->exists()) {
            return back()->with('error', 'Anda sudah melakukan absensi hari ini.');
        }

        // 3. LOGIKA OTOMATIS TELAT
        $jamMasuk = Carbon::now()->format('H:i:s');
        $status = ($jamMasuk > '08:00:00') ? 'telat' : 'hadir';
        $keterangan = ($status == 'telat') ? "Terlambat masuk" : "Tepat waktu";

        $fotoMasukPath = null;

        if ($request->hasFile('foto_masuk')) {
            $fotoMasukPath = $request->file('foto_masuk')
                ->store('foto_absensi', 'public');
        }

        Absensi::create([
            'user_id' => $userID,
            'tanggal' => $tanggalHariIni,
            'jam_masuk' => $jamSekarang,
            'foto_masuk' => $fotoMasukPath,
            'location' => $request->location,
            'status' => $status,
            'keterangan' => $keterangan,
        ]);

        return back()->with('success', "Absensi berhasil!");
    }

    public function update(Request $request)
    {
        $request->validate([
            'foto_keluar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $userId = Auth::id();
        $today = Carbon::now()->format('Y-m-d');
        $jamPulang = Carbon::now()->format('H:i:s');
        $batasPulang = '17:00:00';

        $absen = Absensi::where('user_id', $userId)
            ->where('tanggal', $today)
            ->first();

        if ($absen && $absen->jam_keluar == null) {

            $statusBaru = $absen->status; // Ambil status saat masuk (hadir atau telat)

            // LOGIKA BARU:
            // Jika pulang cepat DAN tadi masuknya tepat waktu ('hadir'), maka jadi 'pulang_cepat'
            // Tapi jika tadi sudah 'telat', biarkan tetap 'telat' agar statistik telat tidak hilang.
            if ($jamPulang < $batasPulang && $absen->status == 'hadir') {
                $statusBaru = 'pulang_cepat';
            }

            $fotoKeluarPath = $absen->foto_keluar;

            if ($request->hasFile('foto_keluar')) {
                $fotoKeluarPath = $request->file('foto_keluar')
                    ->store('foto_absensi', 'public');
            }

            $absen->update([
                'jam_keluar' => $jamPulang,
                'status' => $statusBaru,
                'foto_keluar' => $fotoKeluarPath,
                'keterangan' => $absen->keterangan . " | Catatan Pulang: " . ($request->keterangan ?? '-')
            ]);

            return back()->with('success', 'Berhasil Absen Pulang!');
        }
        return back()->with('error', 'Gagal absen pulang.');
    }
}




