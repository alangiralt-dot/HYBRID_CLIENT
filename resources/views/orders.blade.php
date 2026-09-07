@extends('layouts.app')

@section('tab_name', 'Les meves comandes')

@section('content')
<div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden p-6 space-y-6">

    @if(!empty($error_message))
        <div class="bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3">
            <div class="text-red-500 mt-0.5">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h4 class="text-sm font-medium text-red-800">Avís del sistema</h4>
                <p class="text-xs text-red-700 mt-1 font-normal">
                    {{ $error_message }} 
                </p>
            </div>
        </div>
    @endif

    <div class="space-y-4">
        
        <div class="grid grid-cols-12 gap-4 px-2 pb-2 text-[15px] font-semibold text-gray-500 uppercase tracking-wider border-b border-[#bed1dc]">
            <div class="col-span-2">Codi</div>
            <div class="col-span-2">Estat</div>
            <div class="col-span-2">Data</div>
            <div class="col-span-2">Disponibilitat</div>
            <div class="col-span-2 text-right pr-2">Import</div>
            <div class="col-span-2 text-center">Accions</div>
        </div>

        <div class="divide-y divide-[#bed1dc] !mt-0">
           
            @foreach($confirmedOrders as $order)
            <a href="{{ route('orders.showOrderDetails', $order->id) }}" 
               class="py-3 grid grid-cols-12 items-center gap-4 hover:bg-gray-50 px-2 transition text-[13px] text-black font-normal">
                
                <div class="col-span-2 text-black font-normal tracking-wide whitespace-nowrap">
                    {{ $order->code }}
                </div>
                
                <div class="col-span-2 flex items-center gap-2 text-black font-normal">
                    <span>{{ $order->status }}</span>
                </div>
                
                <div class="col-span-2 text-black tracking-wide uppercase whitespace-nowrap font-normal">
                    {{ $order->date }}
                </div>
                
                <div class="col-span-2 flex items-center gap-2 text-black font-normal">
                    <span class="truncate">{{ $order->order_availability }}</span>
                </div>
                
                <div class="col-span-2 text-right pr-2 font-bold text-black">
                    <span>{{ number_format($order->total_amount, 2, ',', '.') }} €</span>
                </div>
                
                <div class="col-span-2 text-center text-[13px] text-blue-600 mt-1 font-normal tracking-wider cursor-pointer">
                    detalls
                </div>
            </a>
            @endforeach

        </div>
    </div>
</div>
@endsection
