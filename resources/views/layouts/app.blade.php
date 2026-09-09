<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SERRA - Timber Management</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="preconnect" href="https://googleapis.com">
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#f0f2f5] text-[#1e293b] antialiased h-screen flex flex-col">
    <header class="h-16 bg-white border-b border-[#e2e8f0] flex items-center px-6 z-10 shrink-0 justify-between">
        <div class="w-56 flex items-center space-x-3 shrink-0">
            <svg class="h-7 w-7 text-red-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 2 7 12 12 22 7" fill="none"/>
                <path d="M2 12l10 5 10-5" />
                <path d="M2 17l10 5 10-5" />
            </svg>
            <span class="font-bold text-lg tracking-tight text-[#0f172a]">SERRA<span class="text-xs font-semibold text-gray-400 align-super ml-0.5">™</span></span>
        </div>
        <div class="flex-1 flex items-center justify-between pl-6">
            <div class="flex items-center space-x-2">
                <button class="p-1.5 hover:bg-gray-100 rounded-lg text-gray-400 hover:text-gray-600 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <h1 class="font-semibold text-[#0f172a] text-base">@yield('tab_name', 'Dashboard')</h1>
            </div>
            <div class="flex items-center px-2">
                @yield('confirm_order')
                <a href="{{ route('orders.showOrderDetails', 'current') }}"
                   class="p-1 bg-transparent text-gray-400 hover:text-red-600 transition" 
                >
                    <svg class="h-[27px] w-[27px]" xmlns="http://w3.org" xmlns:xlink="http://w3.org" version="1.1" viewBox="0 0 256 256" xml:space="preserve">
                    <g style="stroke: currentColor; stroke-width: 1.5; stroke-linecap: round; stroke-linejoin: round; stroke-miterlimit: 10; fill: currentColor; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                        {{-- Cos i línies estructurals del carret --}}
                        <path d="M 74.396 66.93 h -47.12 c -3.491 0 -5.549 -2.665 -5.777 -5.299 c -0.178 -2.057 0.741 -4.326 2.792 -5.506 L 16.745 22.34 c -0.132 -0.592 0.012 -1.213 0.392 -1.687 c 0.379 -0.474 0.954 -0.75 1.561 -0.75 H 88 c 0.647 0 1.256 0.314 1.631 0.842 c 0.375 0.528 0.471 1.206 0.258 1.817 l -7.983 22.876 c -0.991 2.838 -3.446 4.921 -6.406 5.438 l -48.522 8.48 c -0.006 0.001 -0.012 0.002 -0.019 0.003 c -1.499 0.267 -1.507 1.541 -1.473 1.926 c 0.033 0.386 0.261 1.644 1.792 1.644 h 47.12 c 1.104 0 2 0.896 2 2 S 75.501 66.93 74.396 66.93 z M 21.193 23.904 l 6.966 31.186 l 46.652 -8.152 c 1.533 -0.268 2.805 -1.347 3.318 -2.817 l 7.055 -20.216 H 21.193 z" stroke-linecap="round"/>
                        {{-- Roda del darrere --}}
                        <path d="M 27.846 83.111 c -3.615 0 -6.555 -2.94 -6.555 -6.555 c 0 -3.615 2.94 -6.556 6.555 -6.556 s 6.556 2.94 6.556 6.556 C 34.401 80.171 31.46 83.111 27.846 83.111 z M 27.846 74.001 c -1.409 0 -2.555 1.146 -2.555 2.556 c 0 1.408 1.146 2.555 2.555 2.555 c 1.409 0 2.556 -1.146 2.556 -2.555 C 30.401 75.147 29.255 74.001 27.846 74.001 z" stroke-linecap="round"/>
                        {{-- Roda del davant --}}
                        <path d="M 68.845 83.111 c -3.615 0 -6.556 -2.94 -6.556 -6.555 c 0 -3.615 2.94 -6.556 6.556 -6.556 s 6.556 2.94 6.556 6.556 C 75.4 80.171 72.46 83.111 68.845 83.111 z M 68.845 74.001 c -1.409 0 -2.556 1.146 -2.556 2.556 c 0 1.408 1.146 2.555 2.556 2.555 s 2.556 -1.146 2.556 -2.555 C 71.4 75.147 70.254 74.001 68.845 74.001 z" stroke-linecap="round"/>
                        {{-- Mànec superior de guia --}}
                        <path d="M 18.695 23.904 c -0.916 0 -1.742 -0.633 -1.95 -1.564 l -1.407 -6.301 c -0.677 -3.033 -3.321 -5.151 -6.428 -5.151 H 2 c -1.104 0 -2 -0.896 -2 -2 s 0.896 -2 2 -2 h 6.909 c 4.995 0 9.244 3.404 10.333 8.279 l 1.407 6.301 c 0.241 1.078 -0.438 2.147 -1.516 2.388 C 18.986 23.889 18.839 23.904 18.695 23.904 z" stroke-linecap="round"/>
                    </g>
                    </svg>
                </a>
            </div>
        </div>

    </header>
    <div class="flex flex-1 overflow-hidden">
        <aside class="w-64 bg-white border-r border-[#e2e8f0] flex flex-col justify-between p-5 shrink-0">
            <div class="space-y-6">
                <div id="navElMeuEspai" class="hidden text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3">El meu espai</div>
                <nav class="space-y-1.5">
                    <button id="timber-btn" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-xl transition {{ !request()->is('comandes*') && !request()->is('el-meu-perfil') && !request()->is('login') ? 'text-gray-900 bg-gray-50' : 'text-gray-700 bg-white hover:bg-gray-50' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                            <span>Fustes mecanitzades</span>
                        </div>
                        <svg class="h-3 w-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div id="timber-menu" class="hidden pl-4 space-y-1 mt-1 border-l-2 border-gray-100 ml-5">
                        
                        <div class="space-y-1">
                            <button id="exterior-btn" class="w-full flex items-center justify-between px-3 py-1.5 text-xs font-medium text-gray-700 hover:text-gray-900 transition">
                                <span>Fusta exterior</span>
                                <svg class="h-2.5 w-2.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div id="exterior-menu" class="hidden pl-3 space-y-1 ml-2 border-l border-gray-200">
                                <a href="{{ url('/bigues-fusta-laminades-autoclau') }}" class="block px-3 py-1 text-xs text-gray-500 hover:text-red-600 transition">
                                    Bigues laminades autoclau
                                </a>
                                <a href="{{ url('/llistons-fusta-autoclau-marro') }}" class="block px-3 py-1 text-xs text-gray-500 hover:text-red-600 transition">
                                    Llistons autoclau marró
                                </a>
                                <a href="{{ url('/llistons-fusta-autoclau-verd') }}" class="block px-3 py-1 text-xs text-gray-500 hover:text-red-600 transition">
                                    Llistons autoclau verd
                                </a>
                                <a href="{{ url('/travesses-fusta-jardi') }}" class="block px-3 py-1 text-xs text-gray-500 hover:text-red-600 transition">
                                    Travesses per a jardí
                                </a>
                            </div>
                        </div>

                        <a href="{{ url('/llistons-de-fusta') }}" class="block px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-red-600 transition">
                            Llistons de fusta
                        </a>
                        
                        <a href="{{ url('/llistons-tropicals') }}" class="block px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-red-600 transition">
                            Llistons tropicals
                        </a>

                        <a href="{{ url('/motllures-de-fusta-pi-gallec') }}" class="block px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-red-600 transition">
                            Motllures Pi Gallec
                        </a>

                        <a href="{{ url('/pals-rodons-de-fusta-a-l-autoclau') }}" class="block px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-red-600 transition">
                            Pals rodons autoclau
                        </a>

                        <a href="{{ url('/perfils-laminats-finestra') }}" class="block px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-red-600 transition">
                            Perfils laminats finestra
                        </a>

                        <a href="{{ url('/fusta-vella-i-fusta-envellida') }}" class="block px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-red-600 transition">
                            Fusta vella i envellida
                        </a>
                    </div>

                    <a id="navComandes" href="{{ url('/comandes') }}" class="w-full flex items-center space-x-3 px-3 py-2.5 text-sm rounded-xl transition font-medium {{ request()->is('comandes*') ? 'text-gray-900 bg-gray-50' : 'text-gray-700 bg-white hover:bg-gray-50' }}">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span>Comandes</span>
                    </a>
                </nav>


            </div>
            <div class="border-t border-[#bed1dc] pt-5 space-y-1.5">
                <a href="{{-- url('/el-meu-perfil') --}}" class="flex items-center space-x-3 px-3 py-2 text-sm font-medium rounded-xl transition {{ request()->is('el-meu-perfil') ? 'text-gray-900 bg-gray-50' : 'text-gray-700 bg-white hover:bg-gray-50' }}">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>El meu perfil</span>
                </a>

                <a href="#" id="navLogout" class="flex items-center space-x-3 px-3 py-2 text-sm font-medium text-gray-500 hover:text-red-600 rounded-xl transition hidden">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Logout</span>
                </a>
                
                <a href="{{ route('login') }}" id="navLogin" class="flex items-center space-x-3 px-3 py-2 text-sm font-medium {{ request()->is('login') ? 'text-gray-900 bg-gray-50' : 'text-gray-700 bg-white hover:bg-gray-50' }} rounded-xl transition hidden">
                    <svg class="h-4 w-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Login</span>
                </a>
                
            </div>
        </aside>
        <main class="flex-1 overflow-y-auto p-8 bg-[#f8fafc]">
            <div class="max-w-[1600px] mx-auto">
                @yield('content')
            </div>
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. DESPLEGABLES DEL MENÚ LATERAL (Fustes)
            // Timber category toggle (Level 1 to Level 2)
            const timberBtn = document.getElementById('timber-btn');
            const timberMenu = document.getElementById('timber-menu');
            const navComandes = document.getElementById('navComandes');
            
            // Exterior timber subcategory toggle (Level 2 to Level 3)
            const exteriorBtn = document.getElementById('exterior-btn');
            const exteriorMenu = document.getElementById('exterior-menu');
            
            const navElMeuEspai = document.getElementById('navElMeuEspai');

            if (timberBtn && timberMenu) {
                timberBtn.addEventListener('click', function (event) {
                    event.preventDefault();
                    timberMenu.classList.toggle('hidden');
                });
            }

            if (exteriorBtn && exteriorMenu) {
                exteriorBtn.addEventListener('click', function (event) {
                    event.preventDefault();
                    exteriorMenu.classList.toggle('hidden');
                });
            }
            // 2. CONTROL DE VISIBILITAT DE LA SESSIÓ (Login / Logout)
            const token = sessionStorage.getItem('access_token');
            const navLogin = document.getElementById('navLogin');
            const navLogout = document.getElementById('navLogout');

            // Control dinàmic de la visibilitat dels enllaços
            if (token) {
                navLogout.classList.remove('hidden'); // Mostrem Logout si està loguejat
                navElMeuEspai.classList.remove('hidden');
                navComandes.classList.remove('hidden');
                navLogin.classList.add('hidden');
                navComandes.addEventListener('submit', function() {
                    document.getElementById('tokenComandesInput').value = sessionStorage.getItem('access_token');
                });

            } else {
                navLogout.classList.add('hidden');
                navElMeuEspai.classList.add('hidden');
                navComandes.classList.add('hidden');
                navLogin.classList.remove('hidden');  // Mostrem Login si és un convidat
            }

            // 3. ACCIÓ ASÍNCRONA DE LOGOUT
            // Comportament del clic del botó de Logout asíncron
            navLogout.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Preparem la petició asíncrona local per buidar la sessió de PHP
                const xhrLocal = new XMLHttpRequest();
                xhrLocal.open('POST', "{{ route('orders.clearSession') }}", true);
                xhrLocal.setRequestHeader('Content-Type', 'application/json');
                // Injectem el token CSRF de seguretat de forma directa amb Blade
                xhrLocal.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");

                xhrLocal.onreadystatechange = function () {
                    if (xhrLocal.readyState === 4) {
                        // Un cop la sessió de PHP està buida, netegem el navegador i expulsem
                        sessionStorage.removeItem('access_token');
                        window.location.href = "{{ url('/') }}";
                    }
                };
                xhrLocal.send(); // Enviem la petició de neteja de fons
            });

        });
    </script>

    @yield('scripts')
</body>
</html>