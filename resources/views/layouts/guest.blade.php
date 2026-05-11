<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Warunggalih POS</title>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Livewire bundles Alpine automatically --}}
    @livewireStyles

    <style>
        * { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        .auth-bg {
            background-color: #111827;
            background-image:
                radial-gradient(ellipse at 20% 50%, rgba(249,115,22,0.18) 0%, transparent 55%),
                radial-gradient(ellipse at 85% 15%, rgba(249,115,22,0.10) 0%, transparent 50%);
        }
    </style>
</head>
<body class="antialiased bg-white">
    <div class="min-h-screen flex">

        {{-- LEFT: Branding --}}
        <div class="hidden lg:flex lg:w-1/2 auth-bg flex-col justify-between p-14 relative overflow-hidden">
            {{-- Logo --}}
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-orange-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-xl">Warunggalih POS</span>
            </div>

            {{-- Headline --}}
            <div class="space-y-7">
                <div class="space-y-4">
                    <span class="inline-block px-3 py-1 bg-orange-500/15 text-orange-400 text-xs font-semibold rounded-full tracking-widest uppercase border border-orange-500/20">
                        Sistem POS Modern F&B
                    </span>
                    <h1 class="text-5xl font-black text-white leading-[1.1]">
                        Kelola Bisnis<br>
                        <span class="text-orange-500">F&B</span> Lebih<br>
                        Mudah.
                    </h1>
                    <p class="text-gray-400 text-base leading-relaxed max-w-sm">
                        Dari kasir hingga dapur, semua tersinkronisasi secara real-time. Transaksi lebih cepat, laporan lebih akurat.
                    </p>
                </div>

                <div class="space-y-3">
                    @foreach([
                        ['🧾', 'Mesin Kasir Digital', 'Transaksi cepat & mudah'],
                        ['🍳', 'Kitchen Display System', 'Order real-time ke dapur'],
                        ['📊', 'Laporan & Analitik', 'Pantau omzet kapan saja'],
                    ] as [$icon, $title, $desc])
                    <div class="flex items-center gap-3.5">
                        <div class="w-9 h-9 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center text-base flex-shrink-0">
                            {{ $icon }}
                        </div>
                        <div>
                            <p class="text-white text-sm font-semibold">{{ $title }}</p>
                            <p class="text-gray-500 text-xs">{{ $desc }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <p class="text-gray-700 text-xs">© {{ date('Y') }} Warunggalih POS</p>

            {{-- Deco --}}
            <div class="absolute -bottom-24 -right-24 w-80 h-80 rounded-full border border-orange-500/8 pointer-events-none"></div>
            <div class="absolute -bottom-12 -right-12 w-52 h-52 rounded-full border border-orange-500/8 pointer-events-none"></div>
        </div>

        {{-- RIGHT: Form --}}
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 sm:px-14 lg:px-20 py-12 bg-white">
            {{-- Mobile logo --}}
            <div class="flex lg:hidden items-center gap-2.5 mb-10">
                <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <span class="font-bold text-gray-900">Warunggalih POS</span>
            </div>

            <div class="w-full max-w-sm mx-auto">
                {{ $slot }}
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
