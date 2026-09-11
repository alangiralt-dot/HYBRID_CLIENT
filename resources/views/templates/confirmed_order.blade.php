<template id="confirmed-line-item">
    <div class="confirmed-line-row py-3 font-normal text-black text-[13px] transition hover:bg-gray-50 px-2 space-y-1">
        <div class="grid grid-cols-12 gap-4 items-center">
            <div class="line_name col-span-6 uppercase text-black font-normal break-words"></div>
        </div>
        <div class="grid grid-cols-12 gap-4 items-center">
            <div class="line-reference col-span-2 text-xs text-black tracking-wide"></div>
            <div class="line-dimensions col-span-2 text-right font-normal tracking-wide text-black whitespace-nowrap">
                <!-- @if((int)$product->height === -1)
                    Ø {{ (int)$product->width }} × {{ (int)$product->length }}
                @else
                    {{ (int)$product->width }} × {{ (int)$product->height }} × {{ (int)$product->length }}
                @endif -->
            </div>
            <div class="col-span-2 text-right font-normal"></div>
            <div class="line-quantity col-span-2 text-center font-normal flex justify-center"></div>
            <div class="line-unit-price col-span-2 tracking-wide whitespace-nowrap">
                <!-- {{ number_format($product->unit_price, 2, ',', '.') }} {{ $product->unit }} -->
            </div>
            <div class="line-subtotal col-span-2 text-right font-bold text-black tracking-wide">
                <!-- {{ number_format($product->subtotal, 2, ',', '.') }} € -->
            </div>
        </div>
    </div>
</template>