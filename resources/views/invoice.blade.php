@extends('layouts.app')

@section('confirm_order')
    @if($isCurrent && !$products->isEmpty())
        <form action="{{ route('orders.confirm') }}" method="POST" class="px-2 border-[#bed1dc]">
            @csrf
            <button type="submit" class="bg-[#fffacd] hover:bg-[#fff27e] border border-[#bed1dc] px-4 py-2 rounded-xl text-xs text-black font-medium tracking-wider uppercase shadow-sm transition">
                Confirmar Comanda
            </button>
        </form>
    @endif
@endsection

@section('tab_name', $isCurrent ? 'Comanda en curs' : 'Detall de la comanda')

@section('content')
<div class="space-y-6">

    @if(!empty($conflicting_references))
        <div class="bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3">
            <div class="text-red-500 mt-0.5">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h4 class="text-sm font-medium text-red-800">Atenció: Referències no disponibles</h4>
                <p class="text-xs text-red-700 mt-1 font-normal">
                    No s'ha pogut processar correctament la tarifa o mida dels següents productes: 
                    <span class="font-bold">{{ implode(', ', $conflicting_references) }}</span>. 
                    S'han retirat de la comanda en curs.
                </p>
            </div>
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden p-6 space-y-4">
        
        <div class="grid grid-cols-12 gap-4 px-2 pb-2 text-[15px] font-semibold text-gray-500 uppercase tracking-wider border-b border-[#bed1dc]">
            <div class="col-span-6">Descripció</div>
            <div class="col-span-2 text-center">Quantitat</div>
            <div class="col-span-2">Preu</div>
            <div class="col-span-2 text-right">Subtotal</div>
        </div>

        <div class="divide-y divide-[#bed1dc] !mt-0">
            @forelse($products as $product)
                <div class="py-3 font-normal text-black text-[13px] transition hover:bg-gray-50 px-2 space-y-1">
                    
                    <div class="grid grid-cols-12 gap-4 items-center">
                        <div class="col-span-6 uppercase text-black font-normal break-words">
                            {{ $product->fatherProduct->name ?? 'Material Industrial' }}
                        </div>
                        
                        <div class="col-span-6 text-right font-normal flex justify-end">
                            @if($isCurrent)
                                <button type="button" 
                                        onclick="removeInvoiceItem({{ $product->id }})" 
                                        class="p-1 bg-transparent text-gray-400 hover:text-red-600 transition" 
                                        title="Eliminar producte">
                                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>


                    <div class="grid grid-cols-12 gap-4 items-center">
                        
                        <div class="col-span-2 text-xs text-black tracking-wide">
                            {{ $product->reference }}
                        </div>

                        <div class="col-span-2 text-right font-normal tracking-wide text-black whitespace-nowrap">
                            @if((int)$product->height === -1)
                                Ø {{ (int)$product->width }} × {{ (int)$product->length }}
                            @else
                                {{ (int)$product->width }} × {{ (int)$product->height }} × {{ (int)$product->length }}
                            @endif
                        </div>
                        <div class="col-span-2 text-right font-normal"></div>
                        <div class="col-span-2 text-center font-normal flex justify-center">
                            @if($isCurrent)
                                <div class="flex items-center border border-[#bed1dc] rounded-lg overflow-hidden bg-white shadow-3xs">
                                    <button type="button" onclick="updateInvoiceSession({{ $product->id }}, -{{ $product->pack ?? 1 }}, this.parentNode.querySelector('input').value)" class="px-2 py-1 bg-[#fffacd] text-black hover:bg-[#fff27e] transition border-r border-[#bed1dc] select-none text-[14px]">-</button>
                                   
                                    <input type="number" name="quantity[{{ $product->id }}]" value="{{ $quantities[$product->id]['quantity'] }}" min="{{ $product->pack ?? 1 }}" step="{{ $product->pack ?? 1 }}" class="w-10 text-center text-[12px] bg-white text-black font-normal focus:outline-none [appearance:textfield] [&amp;::-webkit-outer-spin-button]:appearance-none [&amp;::-webkit-inner-spin-button]:appearance-none">
                                    
                                    <button type="button" onclick="updateInvoiceSession({{ $product->id }}, {{ $product->pack ?? 1 }}, this.parentNode.querySelector('input').value)" class="px-2 py-1 bg-[#fffacd] text-black hover:bg-[#fff27e] transition border-l border-[#bed1dc] select-none text-[14px]">+</button>
                                </div>
                            @else
                                {{ $product->pivot->quantity }}
                            @endif
                        </div>


                        <div class="col-span-2 tracking-wide whitespace-nowrap">
                            {{ number_format($isCurrent ? $product->current_unit_price : $product->pivot->sale_unit_price, 2, ',', '.') }} {{ $product->unit->unit }}
                        </div>

                        <div class="col-span-2 text-right font-bold text-black tracking-wide">
                            {{ number_format($isCurrent ? $quantities[$product->id]['subtotal'] : $product->pivot->subtotal, 2, ',', '.') }} €
                        </div>

                    </div>

                </div>
            @empty
                <div class="py-12 text-center text-gray-400 font-normal">
                    No hi ha cap producte carregat en aquesta comanda.
                </div>
            @endforelse
        </div>


    </div>
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden py-6 px-8 space-y-4">

        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-4 space-y-2 text-[13px] font-normal">
                {{-- Tres files de traçabilitat inferiors alineades amb el bloc comptable --}}
                <div class="flex justify-between text-black">
                    <span class="font-semibold text-gray-500 uppercase tracking-wider">Codi</span>
                    <span class="font-normal text-black tracking-wide">{{ $code }}</span>
                </div>
                <div class="flex justify-between text-black">
                    <span class="font-semibold text-gray-500 uppercase tracking-wider">Estat</span>
                    <span class="font-normal text-black">{{ $status }}</span>
                </div>
                <div class="flex justify-between text-black">
                    <span class="font-semibold text-gray-500 uppercase tracking-wider">Data</span>
                    <span class="font-normal text-black tracking-wide">{{ $date }}</span>
                </div>
            </div>
            <div class="col-span-4 space-y-2 text-[13px] font-normal"></div>
            <div class="col-span-4 space-y-2 text-[13px] font-normal">
                <div class="flex justify-between text-black">
                    <span class="uppercase">Base Imposable</span>
                    <span class="font-bold text-black tracking-wide">{{ number_format($taxableBasis, 2, ',', '.') }} €</span>
                </div>
                <div class="flex justify-between text-black">
                    <span>IVA (21%)</span>
                    <span class="font-bold text-black tracking-wide">{{ number_format($tax, 2, ',', '.') }} €</span>
                </div>
                <div class="flex justify-between text-black">
                    <span>TOTAL</span>
                    <span class="font-bold text-black tracking-wide">{{ number_format($total, 2, ',', '.') }} €</span>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
    function updateInvoiceSession(productId, step, currentValue) {
        const currentVal = parseInt(currentValue) || 0;
        
        // Protecció local: Si l'usuari intenta restar i ja som al mínim (el pack), bloquegem la petició asíncrona
        if (step < 0 && currentVal <= Math.abs(step)) {
            return; 
        }

        // 1. Injectem el token CSRF de validació de Laravel
        const csrfToken = "{{ csrf_token() }}";

        // 2. Preparem la petició POST cap a la teva ruta oficial d'afegir
        const xhr = new XMLHttpRequest();
        xhr.open('POST', "{{ route('orders.add') }}", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);

        // 3. Un cop la sessió s'ha modificat amb èxit pel controlador, recarreguem la URL
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                window.location.href = "{{ url('/comandes/current') }}";
            }
        };

        // 4. ENVIEM EL STEP DIRECTAMENT: Laravel farà el `+= $step` exacte a la sessió
        xhr.send(`product_id=${productId}&quantity=${step}`);
    }
    function removeInvoiceItem(productId) {
        const csrfToken = "{{ csrf_token() }}";

        const xhr = new XMLHttpRequest();
        xhr.open('POST', "{{ route('orders.remove') }}", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Quan la sessió s'ha buidat, cridem immediatament la URL comandes/current
                window.location.href = "{{ url('/comandes/current') }}";
            }
        };

        xhr.send(`product_id=${productId}`);
    }
</script>
@endsection
