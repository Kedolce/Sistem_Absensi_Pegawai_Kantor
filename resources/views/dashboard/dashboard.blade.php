<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Absensi</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-linear-to-b from-cyan-600 to-75% to-cyan-800 min-h-screen text-white p-6">

    <!-- Wrapper Utama -->
    <div class="max-w-6xl mx-auto space-y-6">

        <!-- NAVBAR -->
        <nav class="md:backdrop-blur-sm bg-black/10 md:bg-white/10 border border-white/10 p-4 rounded-2xl flex justify-between items-center shadow-2xl sticky top-6 z-50">
            <div class="flex items-center gap-4">
                <h1 class="text-xl font-bold tracking-tight">ABSENSI<span class="text-blue-800 text-sm ml-2">v1.0</span></h1>
            </div>



            <div class="flex items-center gap-6">
                <div class="text-right hidden md:block">
                    <!-- KETIK DISINI: Logika Laravel untuk memanggil NAMA user -->
                    <p class="text-sm font-semibold">{{ Auth::user()->name }}</p>

                    <!-- KETIK DISINI: Logika Laravel untuk memanggil ROLE user -->
                    <p class="text-xs text-white/60 capitalize">{{Auth::user()->role}}</p>
                </div>

                <!-- FORM LOGOUT -->
                <!-- KETIK DISINI: Tentukan Route dan Method untuk Logout -->
                <form action="/logout" method="POST">
                    @csrf

                    <button type="submit" class="bg-red-500 md:bg-transparent md:hover:bg-red-500 border-2 border-red-500 px-4 py-2 rounded-xl text-sm font-medium transition-all duration-500 cursor-pointer">
                        Logout
                    </button>
                </form>
            </div>
        </nav>






        @if(session('error'))
        <div class="bg-red-500/20 text-red-200 p-4 rounded-2xl mb-4 border border-red-500/50">
            {{ session('error') }}
        </div>
        @endif


        <!-- HERO SECTION -->
        <div class=" p-8 flex flex-col justify-center items-center gap-6 text-center">
            <div>
                <!-- KETIK DISINI: Sapa User Berdasarkan Namanya -->
                <h2 class="text-4xl font-extrabold italic mb-2">Halo, {{Auth::user()->name}}!</h2>
                <p class="text-white/70 text-lg">Jangan lupa untuk melakukan absensi tepat waktu hari ini.</p>
            </div>
            <div class="text-center">
                <!-- Tanggal tetap ada -->
                <p class="text-5xl font-light tracking-tighter mb-2">{{ date('d F Y') }}</p>

                <!-- JAM REAL-TIME (Tambahkan ID 'realtime-clock' disini) -->
                <p id="realtime-clock" class="text-3xl font-mono text-blue-400 mb-2">00:00:00</p>

                <p class="text-white/50 uppercase tracking-widest text-sm">{{ date('l') }}</p>
            </div>
        </div>





        @if(Auth::user()->role == 'admin')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-6xl mx-auto mb-8">

            <!-- Kartu Total Hadir -->
            <div class="md:backdrop-blur-md bg-black/10 md:bg-white/10 border border-white/20 p-6 rounded-3xl shadow-xl md:hover:bg-white/20 md:hover:-translate-y-2 transition-all duration-300">
                <p class="text-white/50 text-xs uppercase tracking-widest">Total Hadir</p>
                <h3 class="text-4xl font-bold text-green-400">{{ $totalHadir }} <span class="text-sm font-normal text-white/30">/ {{ $totalPegawai }}</span></h3>
            </div>

            <!-- Kartu Terlambat -->
            <div class="md:backdrop-blur-md bg-black/10 md:bg-white/10 border border-white/20 p-6 rounded-3xl shadow-xl md:hover:bg-white/20 md:hover:-translate-y-2 transition-all duration-300">
                <p class="text-white/50 text-xs uppercase tracking-widest">Terlambat</p>
                <h3 class="text-4xl font-bold text-red-400">{{ $totalTelat }}</h3>
            </div>

            <!-- Kartu Pulang Cepat -->
            <div class="md:backdrop-blur-md bg-black/10 md:bg-white/10 border border-white/20 p-6 rounded-3xl shadow-xl md:hover:bg-white/20 md:hover:-translate-y-2 transition-all duration-300">
                <p class="text-white/50 text-xs uppercase tracking-widest">Pulang Cepat</p>
                <h3 class="text-4xl font-bold text-yellow-400">{{ $totalPulangCepat }}</h3>
            </div>

        </div>
        @endif






        <!-- MAIN CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- KARTU ABSENSI -->
            <div class="md:backdrop-blur-md bg-black/10 md:bg-white/10 border border-white/20 p-6 rounded-3xl shadow-xl md:hover:bg-white/20 md:hover:-translate-y-2 transition-all duration-300">
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <span class="w-2 h-6 bg-blue-400 rounded-full"></span> Status Kehadiran
                </h3>

                <!-- Info Jam Masuk -->
                <div class="bg-white/5 rounded-2xl p-6 text-center border border-white/5 mb-6">
                    <p class="text-white/60 text-sm mb-1">Jam Masuk</p>
                    <p class="text-3xl font-mono">
                        {{ $absenHariIni ? date('H:i', strtotime($absenHariIni->jam_masuk)) : '-- : --' }}
                    </p>
                </div>

                <!-- Logika Tombol Sesuai Kondisi -->
                @if(!$absenHariIni)
                <!-- Form Masuk -->
                <!-- Form Absen Masuk -->
                <form action="/absen" method="POST" enctype="multipart/form-data" id="form-absen">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm mb-2 text-white">
                            Foto Absen Masuk
                        </label>

                        <input type="file"
                            name="foto_masuk"
                            accept="image/*"
                            capture="user"
                            required
                            class="cursor-pointer w-full bg-white/10 border border-white/20 rounded-xl p-10 text-sm">
                    </div>
                    <!-- Input hidden untuk menampung koordinat -->
                    <input type="hidden" name="location" id="location-input">

                    <button type="button" onclick="getLocation()" id="btn-absen" class="w-full bg-white text-indigo-600 font-bold py-4 rounded-2xl shadow-xl hover:bg-indigo-50 transition-all">
                        ABSEN MASUK
                    </button>
                </form>

                <script>
                    function getLocation() {
                        const btn = document.getElementById('btn-absen');
                        const locInput = document.getElementById('location-input');
                        const form = document.getElementById('form-absen');

                        if (navigator.geolocation) {
                            btn.innerText = "Mencari Lokasi...";
                            btn.disabled = true;

                            navigator.geolocation.getCurrentPosition(
                                (position) => {

                                    const lat = position.coords.latitude;
                                    const long = position.coords.longitude;
                                    locInput.value = `${lat},${long}`;
                                    form.submit();
                                },
                                (error) => {
                                    alert("Gagal mengambil lokasi. Pastikan GPS aktif.");
                                    btn.innerText = "ABSEN MASUK";
                                    btn.disabled = false;
                                }, {
                                    enableHighAccuracy: true, // INI KUNCINYA: Memaksa hardware GPS asli
                                    timeout: 10000, // Memberi waktu 10 detik untuk mencari sinyal
                                    maximumAge: 0 // Jangan gunakan lokasi yang tersimpan di cache
                                }
                            );

                        } else {
                            alert("Browser kamu tidak mendukung GPS.");
                            btn.innerText = "ABSEN MASUK";
                            btn.disabled = false;
                        }
                    }
                </script>

                @elseif($absenHariIni->jam_keluar == null)
                <!-- Form Pulang -->
                <form action="/absen-pulang" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm mb-2 text-white/70">
                            Foto Absen Pulang
                        </label>

                        <input type="file"
                            name="foto_keluar"
                            accept="image/*"
                            capture="user"
                            required
                            class="w-full bg-white/10 border border-white/20 rounded-xl p-3 text-sm">
                    </div>
                    {{-- Input Keterangan Baru --}}
                    <div class="mb-4">
                        <label class="block text-xs text-white/50 mb-2 uppercase tracking-widest">Catatan Hari Ini (Opsional)</label>
                        <textarea name="keterangan" rows="2"
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 transition"
                            placeholder="Apa yang kamu kerjakan hari ini?"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 rounded-2xl shadow-xl transition-all cursor-pointer">
                        ABSEN PULANG (CLOCK OUT)
                    </button>
                </form>
                @else
                <!-- Status Selesai -->
                <div class="w-full bg-green-500 text-green-100 border border-green-500/50 font-bold py-8 rounded-3xl text-center">
                    <p class="text-2xl mb-1">🎉</p>
                    ABSENSI HARI INI SELESAI
                </div>
                @endif
            </div>



            <!-- KARTU INFO AKUN -->
            <div class="md:backdrop-blur-md bg-black/10 md:bg-white/10 border border-white/20 p-6 rounded-3xl relative overflow-hidden shadow-xl md:hover:bg-white/20 md:hover:-translate-y-2 transition-all duration-300">
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <span class="w-2 h-6 bg-indigo-400 rounded-full"></span> Informasi Akun
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-white/50 text-xs uppercase tracking-widest">Nomor Induk Pegawai</p>
                        <p class="text-3xl font-medium">{{ Auth::user()->nip }}</p>
                    </div>
                    <div>
                        <p class="text-white/50 text-xs uppercase tracking-widest">Email Terdaftar</p>
                        <p class="text-xl font-medium">{{ Auth::user()->email }}</p>
                    </div>
                    <div>
                        <p class="text-white/50 text-xs uppercase tracking-widest">Status</p>
                        <p class="text-xl font-medium uppercase">{{ Auth::user()->status }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <div class="py-6 mx-auto max-w-6xl mt-20 border-t border-white/20 items-center gap-4">
        <h3 class="text-xl font-bold mb-5">
            Cari Pegawai
        </h3>
        <!-- Input Cari Nama -->
        @if(Auth::user()->role == 'admin')
        <div class="relative max-w-6xl w-full mx-auto shadow-xl">
            <input type="text" id="searchName"
                class="w-full bg-white/20 border border-white/20 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 transition"
                placeholder="Cari nama pegawai...">
        </div>
        @endif
    </div>




    <!-- RIWAYAT TABLE -->
    <div class="max-w-6xl mb-10 md:mb-20 mx-auto md:backdrop-blur-md bg-black/10 md:bg-white/10 border border-white/10 rounded-3xl overflow-hidden shadow-2xl mt-5">
        <div class="p-6 border-b border-white/10">
            <h3 class="text-lg font-bold italic text-white">Riwayat Absensi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-white/5 text-white/50 text-xs uppercase tracking-widest text-center">
                    <tr>
                        {{-- TAMBAHKAN INI: Agar judul kolom sinkron dengan isi --}}
                        @if(Auth::user()->role == 'admin')
                        <th class="px-6 py-4 font-medium text-left">Pegawai</th>
                        @endif

                        <th class="px-6 py-4 font-medium text-left">Tanggal</th>
                        <th class="px-6 py-4 font-medium">Jam Masuk</th>
                        <th class="px-6 py-4 font-medium">Jam Pulang</th>
                        <th class="px-6 py-4 font-medium">Foto</th>
                        <th class="px-6 py-4 font-medium">Lokasi</th>
                        <th class="px-6 py-4 font-medium text-right">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-white/5">
                    @foreach($riwayatAbsen as $row)
                    <tr class="hover:bg-white/5 transition-colors text-center">
                        {{-- 1. Kolom Nama (Hanya Admin) --}}
                        @if(Auth::user()->role == 'admin')
                        <td class="px-6 py-4 text-sm text-left font-bold text-indigo-300">
                            {{ $row->user->name }}
                        </td>
                        @endif

                        {{-- 2. Tanggal --}}
                        <td class="px-6 py-4 text-sm text-left">{{ date('d M Y', strtotime($row->tanggal)) }}</td>

                        {{-- 3. Jam Masuk --}}
                        <td class="px-6 py-4 text-sm font-mono {{ $row->jam_masuk > '08:00:00' ? 'text-red-400 font-bold' : 'text-blue-300' }}">
                            {{ date('H:i', strtotime($row->jam_masuk)) }}
                        </td>

                        {{-- 4. Jam Pulang --}}
                        <td class="px-6 py-4 text-sm font-mono {{ ($row->jam_keluar && $row->jam_keluar < '17:00:00') ? 'text-yellow-400 font-bold' : 'text-green-300' }}">
                            {{ $row->jam_keluar ? date('H:i', strtotime($row->jam_keluar)) : '-- : --' }}
                        </td>

                        {{-- FOTO ABSENSI --}}
                        <td class="px-6 py-4">
                            <div class="flex gap-2 justify-center lg:flex-row flex-col">

                                {{-- Foto Masuk --}}
                                @if($row->foto_masuk)
                                <a href="{{ asset('storage/' . $row->foto_masuk) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $row->foto_masuk) }}"
                                        class="w-12 h-12 object-cover rounded-lg border border-white/20 hover:scale-110 transition">
                                </a>
                                @endif

                                {{-- Foto Keluar --}}
                                @if($row->foto_keluar)
                                <a href="{{ asset('storage/' . $row->foto_keluar) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $row->foto_keluar) }}"
                                        class="w-12 h-12 object-cover rounded-lg border border-white/20 hover:scale-110 transition">
                                </a>
                                @endif

                            </div>
                        </td>

                        {{-- 5. Lokasi GPS --}}
                        <td class="px-6 py-4 text-sm font-medium">
                            @if($row->location)
                            <a href="https://google.com/maps?q={{ $row->location }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-indigo-300 hover:text-indigo-2`00 ">
                                📍 Lihat Posisi
                            </a>
                            @else
                            <span class="text-white/20 italic">No GPS</span>
                            @endif
                        </td>

                        {{-- 6. Status --}}
                        <td class="px-6 py-4 text-right">
                            <div class="flex flex-col items-end">
                                {{-- Badge Status --}}
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase border 
                                    {{ $row->status == 'telat' ? 'bg-red-500/20 text-red-400 border-red-500/50' : '' }}
                                    {{ $row->status == 'pulang_cepat' ? 'bg-yellow-500/20 text-yellow-400 border-yellow-500/50' : '' }}
                                    {{ $row->status == 'hadir' ? 'bg-green-500/20 text-green-400 border-green-500/50' : '' }}">
                                    {{ str_replace('_', ' ', $row->status) }}
                                </span>


                                {{-- Teks Keterangan --}}
                                @if($row->keterangan)
                                <p class="text-[9px] text-white/40 mt-1 italic max-w-37.5 truncate" title="{{ $row->keterangan }}">
                                    "{{ $row->keterangan }}"
                                </p>
                                @endif
                            </div>
                        </td>


                    </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>






    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            const clockElement = document.getElementById('realtime-clock');
            if (clockElement) {
                clockElement.textContent = `${hours}:${minutes}:${seconds}`;
            }
        }
        // Update setiap 1 detik
        setInterval(updateClock, 1000);
        updateClock(); // Jalankan langsung saat halaman terbuka



        document.getElementById('searchName')?.addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                // Kita ambil kolom pertama (Nama Pegawai)
                let nameColumn = row.querySelector('td:nth-child(1)');
                if (nameColumn) {
                    let textValue = nameColumn.textContent || nameColumn.innerText;
                    if (textValue.toLowerCase().indexOf(filter) > -1) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                }
            });
        });
    </script>


</body>

</html>