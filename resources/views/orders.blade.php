@extends('layouts.app')

@section('tab_name', 'Les meves comandes')

@section('content')
<div>
    @include('components.banner')
    
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