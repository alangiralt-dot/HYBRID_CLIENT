@extends('layouts.app')

@section('tab_name', $category->category)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="space-y-8">
        @foreach($groupedProducts as $fatherName => $variants)
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden p-6 space-y-6">
                <a href="#">
                <div class="flex items-center gap-5">
                    <div class="w-[110px] bg-white flex items-center justify-center overflow-hidden flex-shrink-0 shadow-2xs">
                        @if(count($variants) > 0 && isset($variants[0]->father_product->image_path))
                            <img src="{{ config('services.api_serra.url') }}/storage/{{ $variants[0]->father_product->image_path }}" alt="{{ $fatherName }}" class="w-full h-auto object-contain">
                        @else
                            <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 uppercase tracking-wider">
                            {{ $fatherName }}
                        </h3>
                        <p class="text-[13px] text-blue-600 bg-white mt-1 font-normal tracking-wider cursor-pointer">
                            Veure més informació
                        </p>
                    </div>
                </div>
                </a>
                <div class="grid grid-cols-12 gap-4 px-2 pb-2 text-[15px] font-semibold text-gray-500 uppercase tracking-wider border-b border-[#bed1dc]">
                    <div class="col-span-2">Referència</div>
                    <div class="col-span-2">Mesures</div>
                    <div class="col-span-2">Disponibilitat</div>
                    <div class="col-span-2 text-right pr-2">Preu</div>
                    <div class="col-span-2 text-center">Quantitat</div>
                    <div class="col-span-2 text-center">Acció</div>
                </div>
                <div class="divide-y divide-[#bed1dc] !mt-0">
                    @foreach($variants as $product)
                        <div id="row-{{ $product->id }}" class="py-3 grid grid-cols-12 items-center gap-4 hover:bg-gray-50 px-2 transition text-[13px] text-black font-normal">
                            <div class="col-span-2 text-black font-normal tracking-wide whitespace-nowrap">
                                {{ $product->reference }}
                            </div>
                            <div class="col-span-2 text-black tracking-wide uppercase whitespace-nowrap font-normal">
                                @if($product->height === -1)
                                    Ø{{ $product->width }}X{{ $product->length }}MM
                                @else
                                    {{ $product->width }}X{{ $product->height }}X{{ $product->length }}MM
                                @endif
                            </div>
                            <div class="col-span-2 flex items-center gap-2 text-black font-normal">
                                <span class="truncate">{{ $product->availability?->availability }}</span>
                            </div>
                            <div class="col-span-2 text-right pr-2 font-bold text-black">
                                <span>
                                    {{ number_format($product->current_unit_price, 2, ',', '.') }}&nbsp;{{ $product->unit?->unit }}
                                </span>
                            </div>
                            <div class="col-span-2 flex justify-center">
                                <div class="flex items-center border border-[#bed1dc] rounded-lg overflow-hidden bg-white shadow-3xs">
                                    <button type="button" onclick="this.parentNode.querySelector('input').stepDown()" class="px-2 py-1 bg-[#fffacd] text-black hover:bg-[#fff27e] transition border-r border-[#bed1dc] select-none text-[14px]">-</button>
                                    <input type="number"
                                           name="quantity[{{ $product->id }}]"
                                           value="{{ $product->pack }}"
                                           min="{{ $product->pack }}"
                                           step="{{ $product->pack }}"
                                           class="w-10 text-center text-[12px] bg-white text-black font-normal focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                    <button type="button" onclick="this.parentNode.querySelector('input').stepUp()" class="px-2 py-1 bg-[#fffacd] text-black hover:bg-[#fff27e] transition border-l border-[#bed1dc] select-none text-[14px]">+</button>
                                </div>
                            </div>
                            <div class="col-span-2 flex justify-center">
                                <button type="button" onclick="testAddProduct({{ $product->id }})" class="bg-[#fffacd] hover:bg-[#fff27e] border border-[#bed1dc] px-4 py-2 rounded-xl text-xs text-black font-medium tracking-wider uppercase shadow-sm transition">
                                    AFEGIR
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
<script>
    function testAddProduct(productId) {
        // 1. Busquem la fila i l'input de quantitat corresponent del producte fill
        const row = document.getElementById(`row-${productId}`);
        /*
        $_POST = [
            'quantity' => [
                135 => "1", // producte fill 135 -> 1 paquet
                136 => "3", // producte fill 136 -> 3 paquets
                140 => "12" // producte fill 140 -> 12 paquets
            ]
        ];
        */
        const quantityInput = document.querySelector(`input[name="quantity[${productId}]"]`);
        if (!row || !quantityInput) return;

        const quantity = quantityInput.value;

        // 2. Injectem el token directament des de Laravel de forma nativa via Blade
        const csrfToken = "{{ csrf_token() }}";

        // 3. Preparem la petició asíncrona amb XMLHttpRequest
        const xhr = new XMLHttpRequest();
        xhr.open('POST', "{{ url('/orders/add') }}", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);

        // 4. Definim què fer quan el servidor ens respongui
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // GUARDEM el disseny original de la teva graella abans de canviar-lo
                const originalContent = row.innerHTML;
                const originalClasses = row.className;

                // SUBSTITUÏM la línia per la teva franja verda de confirmació
                row.className = "bg-green-50 border border-green-200 py-4 text-sm text-green-700 font-medium shadow-sm flex justify-center";
                row.innerHTML = `
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>El producte s'ha afegit correctament a la comanda actual</span>
                    </div>
                `;

                // TEMPORITZADOR de 4 segons per restaurar el teu disseny original intacte
                setTimeout(() => {
                    row.className = originalClasses;
                    row.innerHTML = originalContent;
                }, 4000);
            }
        };

        // 5. Enviem les dades en segon pla cap a Laravel
        // La petició viatja pel servidor, Laravel processa la sessió, i quan retorna un codi d'èxit 200, l'objecte detecta el canvi d'estat i és en aquell precís moment de futur quan s'executa la funció de dins de la callback per pintar la teva franja verda
        xhr.send(`product_id=${productId}&quantity=${quantity}`);
    }
</script>

@endsection
