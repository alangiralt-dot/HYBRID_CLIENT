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
    fetch("{{ config('services.api_serra.url') }}/api/customers/tokens", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ email: email, password: password })
    })
    .then(async response => {
        if (response.status === 201) {
            const responseData = await response.json();
            
            if (responseData.status === 'success' && responseData.data.access_token) {
                // Petició secundària amb fetch per netejar la sessió local de PHP
                fetch("{{ route('orders.clearSession') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                })
                .then(() => {
                    sessionStorage.setItem('access_token', responseData.data.access_token);
                    window.location.href = "{{ url('/comandes/current') }}";
                });
            }
        } else {
            loginError.textContent = 'El correu electrònic o la contrasenya no són correctes.';
            loginError.classList.remove('hidden');
            emailInput.classList.replace('border-gray-200', 'border-red-500');
            passwordInput.classList.replace('border-gray-200', 'border-red-500');
        }
    })
    .catch(error => {
        loginError.textContent = 'S\'ha produït un error de connexió amb el servidor.';
        loginError.classList.remove('hidden');
    });
});
</script>

@endsection
