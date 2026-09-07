@extends('layouts.app')

@section('tab_name', 'Login')

@section('content')
<div class="bg-white rounded-2xl border border-[#e2e8f0] p-8 max-w-2xl mx-auto shadow-sm">
    <form id="loginForm" class="space-y-5">    
        
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12">
                <p id="loginError" class="text-xs text-red-500 pl-4 mt-1 hidden"></p>
            </div>
            <div class="col-span-12">
                <label class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Correu electrònic</label>
                <input type="email" name="email" id="emailInput" value="" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#bed1dc] transition"
                >
            </div>

            <div class="col-span-12">
                <label class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Contrasenya</label>
                <input type="password" name="password" id="passwordInput" value="" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#bed1dc] transition">
            </div>
         </div>

        <div class="pt-4 flex justify-end">
            <button type="submit" class="bg-[#fffacd] hover:bg-[#fff27e] border border-[#bed1dc] px-4 py-2 rounded-xl text-xs text-black font-medium tracking-wider uppercase shadow-sm transition">
                Obrir Sessió
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Evitem que la pàgina es recarregui de forma tradicional

    const email = document.getElementById('emailInput').value;
    const password = document.getElementById('passwordInput').value;
    const loginError = document.getElementById('loginError');
    const emailInput = document.getElementById('emailInput');
    const passwordInput = document.getElementById('passwordInput'); 

    // Netejem possibles estats d'error anteriors
    loginError.classList.add('hidden');
    emailInput.classList.replace('border-red-500', 'border-gray-200');
    passwordInput.classList.replace('border-red-500', 'border-gray-200');

    // Preparem la petició asíncrona com al selector de quantitats
    const xhr = new XMLHttpRequest();
    // Utilitzem l'URL complet del teu backend central de l'API_SERRA
    xhr.open('POST', "{{ config('services.api_serra.url') }}/api/customers/tokens", true);

    xhr.setRequestHeader('Content-Type', 'application/json');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 201) { // 201 Created tal com ens ha mostrat Postman
                const response = JSON.parse(xhr.responseText);
                
                if (response.status === 'success' && response.data.access_token) {
                    // Preparem la petició asíncrona local per buidar la sessió de PHP
                    const xhrLocal = new XMLHttpRequest();
                    xhrLocal.open('POST', "{{ route('orders.clearSession') }}", true);
                    xhrLocal.setRequestHeader('Content-Type', 'application/json');
                    // Injectem el token CSRF que Laravel demana per seguretat a les rutes locals POST
                    xhrLocal.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");

                    xhrLocal.onreadystatechange = function () {
                        if (xhrLocal.readyState === 4) {
                            // Un cop la sessió de PHP s'ha buidat, guardem el token al navegador
                            sessionStorage.setItem('access_token', response.data.access_token);
                            // Redirigim finalment cap al carretó de la comanda actual
                            window.location.href = "{{ url('/comandes/current') }}";
                        }
                    };
                    xhrLocal.send(); // Enviem la petició de neteja de fons
                }
            } else {
                // Si la petició falla (credencials incorrectes, 401, etc.)
                loginError.textContent = 'El correu electrònic o la contrasenya no són correctes.';
                loginError.classList.remove('hidden');
                emailInput.classList.replace('border-gray-200', 'border-red-500');
                passwordInput.classList.replace('border-gray-200', 'border-red-500');
            }
        }
    };

    // Enviem les dades en format JSON neta cap a l'endpoint
    xhr.send(JSON.stringify({
        email: email,
        password: password
    }));
});
</script>

@endsection
