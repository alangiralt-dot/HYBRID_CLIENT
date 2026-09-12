@extends('layouts.app')

@section('tab_name', 'El meu perfil')

@section('content')
<div class="bg-white rounded-2xl border border-[#e2e8f0] p-8 max-w-2xl mx-auto shadow-sm">

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
</script>
@endsection
