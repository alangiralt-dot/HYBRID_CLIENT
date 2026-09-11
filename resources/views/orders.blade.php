@extends('layouts.app')

@section('tab_name', 'Les meves comandes')

@section('content')
<div class="space-y-6">
    <div id="error-banner" class="hidden bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3">
        <div class="text-red-500 mt-0.5">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <div>
            <h4 class="text-sm font-medium text-red-800">Avís del sistema</h4>
            <p id="error-message" class="text-xs text-red-700 mt-1 font-normal"></p>
        </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden p-6 space-y-6">
        <div class="space-y-4">
            <div class="grid grid-cols-12 gap-4 px-2 pb-2 text-[15px] font-semibold text-gray-500 uppercase tracking-wider border-b border-[#bed1dc]">
                <div class="col-span-2">Codi</div>
                <div class="col-span-2">Estat</div>
                <div class="col-span-2">Data</div>
                <div class="col-span-2">Disponibilitat</div>
                <div class="col-span-2 text-right pr-2">Import</div>
                <div class="col-span-2 text-center">Accions</div>
            </div>
            <div id="orders-list" class="divide-y divide-[#bed1dc] !mt-0"></div>
        </div>
    </div>
</div>
<template id="orders-list-item">
    <a href="#" class="order-link py-3 grid grid-cols-12 items-center gap-4 hover:bg-gray-50 px-2 transition text-[13px] text-black font-normal">
        <div class="order-code col-span-2 text-black font-normal tracking-wide whitespace-nowrap"></div>
        <div class="col-span-2 flex items-center gap-2 text-black font-normal">
            <span class="order-status"></span>
        </div>
        <div class="order-date col-span-2 text-black tracking-wide uppercase whitespace-nowrap font-normal"></div>
        <div class="col-span-2 flex items-center gap-2 text-black font-normal">
            <span class="order-availability truncate"></span>
        </div>
        <div class="col-span-2 text-right pr-2 font-bold text-black">
            <span class="order-total"></span>
        </div>
        <div class="col-span-2 text-center text-[13px] text-blue-600 mt-1 font-normal tracking-wider cursor-pointer">detalls</div>
    </a>
</template>
@endsection

@section('scripts')
<script>
    const API_SERRA_BASE_URL = "{{ config('services.api_serra.url') }}";

    document.addEventListener('DOMContentLoaded', function () {
        fetchAndRenderOrders();
    });

    async function fetchAndRenderOrders() {
        const ordersContainer = document.getElementById('orders-list');
        if (!ordersContainer) return;

        const token = sessionStorage.getItem('access_token');
        if (!token) {
            showSystemAlert("Sessió no vàlida o expirada. Si us plau, torna a iniciar sessió per consultar les teves comandes.");
            return;
        }
        
        const apiUrl = `${API_SERRA_BASE_URL}/api/orders`;
        const response = await fetch(apiUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
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

        const confirmedOrders = await response.json();
        if (confirmedOrders.length === 0) {
            ordersContainer.innerHTML = `
                <div class="text-center py-8 text-gray-500 font-normal text-[13px]">
                    Encara no has confirmat cap comanda.
                </div>
            `;
            return;
        }

        try {
            const mainFragment = document.createDocumentFragment();
            
            const rowTemplate = document.getElementById('orders-list-item');

            confirmedOrders.forEach(order => {
                const clone = rowTemplate.content.cloneNode(true);

                const orderLink = clone.querySelector('.order-link');
                orderLink.href = `{{ url('/comandes') }}/${order.id}`;

                clone.querySelector('.order-code').textContent = order.code;
                clone.querySelector('.order-status').textContent = order.status;
                clone.querySelector('.order-date').textContent = order.date;
                clone.querySelector('.order-availability').textContent = order.order_availability;

                const totalFormatted = order.total_amount.toLocaleString('ca-ES', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                clone.querySelector('.order-total').textContent = `${totalFormatted} €`;

                mainFragment.appendChild(clone);
            });

            ordersContainer.innerHTML = '';
            ordersContainer.appendChild(mainFragment);

        } catch (error) {
            showSystemAlert(error.message);
            return;
        }
    }
</script>
@endsection