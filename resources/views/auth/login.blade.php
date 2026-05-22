<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite('resources/css/app.css')
</head>



<body class="bg-linear-to-br from-teal-600 from-10% via-cyan-800 via-30% to-teal-800 flex flex-col items-center justify-center min-h-screen text-slate-200">

    <div class="max-w-6xl mx-auto mb-10">
        <h1 class="text-center font-bold text-3xl md:text-5xl text-white">ABSENSI KANTOR <span class="text-yellow-300">BALINESE</span></h1>
    </div>

    <div class="md:backdrop-blur-md bg-white/10 md:bg-white/10 border border-white/20 p-8 rounded-2xl shadow-2xl w-[90%] max-w-lg sm:mx-auto">

        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">Selamat Datang</h1>
            <p class="text-white/60 mt-2">Silakan login dengan NIP dan Password</p>
        </div>

        <!-- Alert Error -->
        @if(session('error'))
        <div class="border border-red-400 text-red-400 p-3 rounded-lg mb-6 text-sm text-center font-bold">
            {{ session('error') }}
        </div>
        @endif





        <!-- Form Login -->
        <form action="/login" method="POST" class="space-y-6">
            @csrf

            <!-- Input NIP -->
            <div>
                <label for="nip" class="block text-sm font-medium mb-2">NIP</label>
                <input type="text" name="nip" id="nip" autocomplete="off"
                    class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder:text-white/60 focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent transition-all duration-300"
                    placeholder="Masukkan NIP Anda" required>
            </div>

            <!-- Input Password -->
            <div>
                <label for="password" class="block text-sm font-medium mb-2">Password</label>
                <input type="password" name="password" id="password"
                    class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder:text-white/60 focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent transition-all duration-300"
                    placeholder="••••••••" required>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class=" w-full cursor-pointer border-2 border-white/20 text-white font-semibold py-3 rounded-xl hover:shadow-xl transform active:scale-[0.98] md:hover:bg-white/10 transition-all duration-400">
                Masuk Sekarang
            </button>



            

            <div class="mt-6 text-center">
                <p class="text-sm text-white/90">
                    Lupa password?
                    <a href="https://wa.me/62xxxxxxxxx?text=HALO%20ADMIN%20SAYA%20LUPA%20PASSWORD%0ANAMA : %0ANIP : " target="_blank"
                        class="text-blue-300 md:text-white underline md:hover:text-blue-400 font-medium transition-colors">
                        Hubungi Admin
                    </a>
                </p>
            </div>
        </form>
    </div>
</body>
</html>