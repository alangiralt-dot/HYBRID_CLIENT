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

@include('templates.simplified_order')

@endsection

@section('scripts') @include('scripts.simplified_order') @endsection