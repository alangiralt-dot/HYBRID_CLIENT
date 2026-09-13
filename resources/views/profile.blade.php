@extends('layouts.app')

@section('tab_name', 'El meu perfil')

@section('content')
<div class="space-y-6">
    <div id="banner-container">
        <div id="error-banner" class="hidden bg-red-50 border border-red-200 rounded-2xl p-4 max-w-2xl mx-auto flex items-start gap-3">
            <div class="text-red-500 mt-0.5">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h4 class="text-sm font-medium text-red-800">Avís del sistema</h4>
                <p id="error-message" class="text-xs text-red-700 mt-1 font-normal">Error message</p>
            </div>
        </div>
        <div id="success-banner" class="hidden bg-green-50 border border-green-200 rounded-2xl p-4 max-w-2xl mx-auto flex items-start gap-3">
            <div class="text-green-500 mt-0.5">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h4 class="text-sm font-medium text-green-800">Avís del sistema</h4>
                <p id="success-message" class="text-xs text-green-700 mt-1 font-normal">Succes message</p>
            </div>
        </div>
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

        <div class="pt-4 flex justify-end space-x-3">
            <button type="button" id="btn-delete-profile" class="hidden bg-[#fffacd] hover:bg-[#fff27e] border border-[#bed1dc] px-4 py-2 rounded-xl text-xs text-black font-medium tracking-wider uppercase shadow-sm transition">
                Donar-se de baixa
            </button>
            <button type="submit" id="submit-btn" class="bg-[#fffacd] hover:bg-[#fff27e] border border-[#bed1dc] px-4 py-2 rounded-xl text-xs text-black font-medium tracking-wider uppercase shadow-sm transition">
            </button>
        </div>
    </form>
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
            populateProfileData(token)
        } else {
            submitBtn.textContent = 'Registrar-se';
        }

        // 2. INTERCEPTEM EL FORMULARI: JavaScript pren el comandament absolut
        profileForm.addEventListener('submit', function(event) {
            event.preventDefault(); // 👈 El fre de mà que atura la recàrrega de Laravel clàssic

            // Recollim els 12 camps de la graella de cop de forma ultra neta
            const formData = new FormData(profileForm);
            const jsonData = Object.fromEntries(formData.entries());

            console.log('Dades llestes per enviar a la serradora:', jsonData);
            
            // Aquí llançarem el fetch asíncron cap a POST /api/customers...
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
