<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Warunggalih POS</title>
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            50:  '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        }
                    }
                }
            }
        }
    </script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Marked.js for Markdown parsing (AI response) --}}
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    {{-- Livewire bundles Alpine automatically --}}
    @livewireStyles

    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background-color: #FDF8F4; }
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

        /* Sidebar */
        .sidebar {
            background: linear-gradient(180deg, #1a0a00 0%, #2d1500 40%, #1f0e00 100%);
            width: 260px;
            flex-shrink: 0;
        }
        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            color: rgba(255,255,255,0.55);
            margin: 0 0.5rem;
        }
        .sidebar-item:hover {
            color: rgba(255,255,255,0.9);
            background: rgba(255,255,255,0.07);
        }
        .sidebar-item.active {
            color: #fff;
            background: linear-gradient(135deg, #ea580c 0%, #f97316 100%);
            box-shadow: 0 4px 15px rgba(249,115,22,0.4);
        }
        .sidebar-item.active svg { color: #fff; }

        /* Scrollbar custom */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(249,115,22,0.3); border-radius: 4px; }

        /* Prose AI */
        .ai-prose h1, .ai-prose h2, .ai-prose h3 { font-weight: 700; margin-bottom: 0.5rem; color: #1a0a00; }
        .ai-prose p { margin-bottom: 0.75rem; line-height: 1.7; }
        .ai-prose ul { list-style-type: disc; padding-left: 1.25rem; margin-bottom: 0.75rem; }
        .ai-prose li { margin-bottom: 0.25rem; }
        .ai-prose strong { color: #ea580c; }
        .ai-prose code { background: #fff7ed; color: #c2410c; padding: 0 0.25rem; border-radius: 0.25rem; }
    </style>
</head>
<body class="antialiased text-gray-900 bg-[#FDF8F4]">
    <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">

        {{-- Sidebar Overlay (Mobile) --}}
        <div x-show="sidebarOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-black/60 z-30 lg:hidden"
            x-cloak></div>

        {{-- SIDEBAR --}}
        <aside class="sidebar fixed top-0 left-0 h-full z-40 flex-col flex transition-transform duration-300 lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
            <livewire:layout.navigation />
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-w-0 lg:ml-[260px]">

            {{-- Top Header --}}
            <header class="sticky top-0 z-20 bg-[#FDF8F4]/90 backdrop-blur-md border-b border-orange-100/60 px-5 py-3 flex items-center justify-between">
                {{-- Left: Mobile Hamburger + Breadcrumb --}}
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden w-9 h-9 flex items-center justify-center rounded-xl text-gray-500 hover:bg-orange-50 hover:text-orange-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div>
                        <p class="text-xs text-orange-400 font-medium">{{ now()->format('l, d F Y') }}</p>
                        <p class="text-sm font-bold text-gray-800 leading-tight">Selamat datang, {{ auth()->user()->name }} 👋</p>
                    </div>
                </div>

                {{-- Right: Role Badge + User --}}
                <div class="flex items-center gap-3">
                    @if(auth()->user()->is_admin)
                        <span class="hidden sm:inline-flex items-center gap-1.5 bg-purple-100 text-purple-700 text-xs font-bold px-3 py-1.5 rounded-full">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.664 1.319a.75.75 0 01.672 0 41.059 41.059 0 018.198 5.424.75.75 0 01-.254 1.285 31.372 31.372 0 00-7.86 3.83.75.75 0 01-.84 0 31.508 31.508 0 00-2.08-1.287V9.2c0-.236.069-.45.19-.63A31.04 31.04 0 001.254 8.028a.75.75 0 01-.254-1.285 41.059 41.059 0 018.664-5.424z" clip-rule="evenodd" /></svg>
                            Admin
                        </span>
                    @else
                        <span class="hidden sm:inline-flex items-center gap-1.5 bg-orange-100 text-orange-700 text-xs font-bold px-3 py-1.5 rounded-full">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                            Kasir
                        </span>
                    @endif

                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white text-xs font-black shadow-lg shadow-orange-200">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 p-5 lg:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
