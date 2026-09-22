@extends('layouts.app')

@section('tab_name', 'El meu perfil')

@section('content')
<div class="max-w-2xl mx-auto">
    @include('components.banner')
    
    <div class="bg-white rounded-2xl border border-[#e2e8f0] p-8 shadow-sm">

        <form id="profile-form" class="space-y-5">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="reg-first-name" class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Nom</label>
                    <input type="text" name="first_name" id="reg-first-name" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
                </div>
                <div>
                    <label for="reg-last-name" class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Cognoms</label>
                    <input type="text" name="last_name" id="reg-last-name" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
                </div>
            </div>

            <div>
                <label for="reg-phone" class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Telèfon de contacte</label>
                <input type="text" name="phone" id="reg-phone" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
            </div>

            <div>
                <label for="reg-street" class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Adreça</label>
                <input type="text" name="street" id="reg-street" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="reg-address-number" class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Número</label>
                    <input type="text" name="address_number" id="reg-address-number" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
                </div>
                <div>
                    <label for="reg-address-floor" class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Pis</label>
                    <input type="text" name="address_floor" id="reg-address-floor" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
                </div>
                <div>
                    <label for="reg-door" class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Porta</label>
                    <input type="text" name="door" id="reg-door" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="reg-postal-code" class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Codi Postal</label>
                    <input type="text" name="postal_code" id="reg-postal-code" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
                </div>
                <div>
                    <label for="reg-city-name" class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Ciutat</label>
                    <input type="text" name="city_name" id="reg-city-name" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
                </div>
                <div>
                    <label for="reg-province-name" class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Província</label>
                    <input type="text" name="province_name" id="reg-province-name" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
                </div>
            </div>

            <div id="auth-fields-block" class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-[#bed1dc] mt-4">
                <div>
                    <label for="reg-email" class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Correu electrònic</label>
                    <input type="email" name="email" id="reg-email" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#bed1dc] transition">
                </div>

                <div>
                    <label for="reg-password" class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Contrasenya</label>
                    <input type="password" name="password" id="reg-password" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#bed1dc] transition">
                </div>
            </div>

            <div class="pt-4 flex justify-end space-x-3">
                <button type="button" id="btn-delete-profile" class="hidden bg-[#fffacd] hover:bg-[#fff27e] border border-[#bed1dc] px-4 py-2 rounded-xl text-xs text-black font-medium tracking-wider uppercase shadow-sm transition">
                    Donar-se de baixa
                </button>
                <button type="submit" id="submit-btn" class="bg-[#fffacd] hover:bg-[#fff27e] border border-[#bed1dc] px-4 py-2 rounded-xl text-xs text-black font-medium tracking-wider uppercase shadow-sm transition">
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileForm = document.getElementById('profile-form');
        const submitBtn = document.getElementById('submit-btn');

        // 1. CONTROL DE FLUX DUAL: Comprovem si el fuster ja té sessió iniciada
        const token = sessionStorage.getItem('access_token');
        if (token) {
            submitBtn.textContent = 'Modificar perfil';
            document.getElementById('btn-delete-profile').classList.remove('hidden'); 
            document.getElementById('auth-fields-block').classList.add('hidden');

            populateProfileData(token)
        } else {
            submitBtn.textContent = 'Registrar-se';
        }

        // 2. INTERCEPTEM EL FORMULARI: Afegim 'async' per poder fer anar els 'await'
        profileForm.addEventListener('submit', async function(event) {
            event.preventDefault(); // El fre de mà que atura la recàrrega de Laravel clàssic

            // Recollim els 12 camps de la graella de cop de forma ultra neta
            const formData = new FormData(profileForm);
            const jsonData = Object.fromEntries(formData.entries());

            // Injectem la teva excel·lent variable de configuració del servei
            const apiBase = "{{ config('services.api_serra.url') }}";

            try {
                // Llançem el fetch asíncron i net cap a la serradora central
                const response = await fetch(`${apiBase}/api/customers` + (token ? '/profiles' : ''), {
                    method: (token ? 'PUT' : 'POST'),
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        ...(token && { 'Authorization': `Bearer ${token}` })
                    },
                    body: JSON.stringify(jsonData)
                });

                if (!response.ok) {
                    try {
                        const errorData = await response.json();
                        showSystemAlert(errorData.message || response.status);
                        return;
                    } catch (error) {
                        showSystemAlert(error.message);
                        return;
                    }
                }

                const result = await response.json();

                if (!token && result.status === 'success' && result.data.access_token) {
                    fetch("{{ route('roles.storeRole') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            "is_admin": result.data.is_admin
                        })
                    })
                    .then(() => {
                        sessionStorage.setItem('access_token', result.data.access_token);
                        showSuccessMessage("El teu registre s'ha efectuat correctament.");
                        document.body.style.pointerEvents = 'none';
                        setTimeout(() => {
                            window.location.href = "{{ url('/?clear_cart=1') }}";
                        }, 2000);
                    });
                    
                    return;
                } else {
                    showSuccessMessage("El teu perfil s'ha modificat correctament.");
                    return;
                }

            } catch (error) {
                showSystemAlert(error.message);
                return;
            }
        });

        // 3. PETICIÓ ASÍNCRONA DE BAIXA: Destrucció d'usuari mantenint perfil històric
        const btnDeleteProfile = document.getElementById('btn-delete-profile');
        if (!btnDeleteProfile) return;
        
        btnDeleteProfile.addEventListener('click', async function() {
            const apiBase = "{{ config('services.api_serra.url') }}";

            try {
                const response = await fetch(`${apiBase}/api/customers/users`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    try {
                        const errorData = await response.json();
                        showSystemAlert(errorData.message || response.status);
                        return;
                    } catch (error) {
                        showSystemAlert(error.message);
                        return;
                    }
                }

                const result = await response.json();

                if (result.status === 'success') {
                    sessionStorage.removeItem('access_token');

                    showSuccessMessage(result.message);
                    
                    document.body.style.pointerEvents = 'none';
                    setTimeout(() => {
                        window.location.href = "{{ url('/?clear_session=1') }}";
                    }, 2000);
                }

            } catch (error) {
                showSystemAlert(error.message);
                return;
            }
        });
    });

    async function populateProfileData(token) {
        try {
            const apiBase = "{{ config('services.api_serra.url') }}";
            const response = await fetch(`${apiBase}/api/customers/profiles`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                try {
                    const errorData = await response.json();
                    showSystemAlert(errorData.message || response.status);
                    return;
                } catch (error) {
                    showSystemAlert(error.message);
                    return;
                }
            }

            const data = await response.json();

            document.getElementById('reg-first-name').value    = data.first_name;
            document.getElementById('reg-last-name').value     = data.last_name;
            document.getElementById('reg-phone').value         = data.phone;
            document.getElementById('reg-street').value        = data.street;
            document.getElementById('reg-address-number').value = data.address_number;
            document.getElementById('reg-address-floor').value  = data.address_floor;
            document.getElementById('reg-door').value           = data.door;
            document.getElementById('reg-postal-code').value   = data.postal_code;
            document.getElementById('reg-city-name').value     = data.city;
            document.getElementById('reg-province-name').value = data.province;

        } catch (error) {
            showSystemAlert(error.message);
            return;
        }
    }
</script>
@endsection
