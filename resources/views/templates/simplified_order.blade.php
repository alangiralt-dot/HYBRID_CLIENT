<template id="orders-list-item">
<div class="py-3 grid grid-cols-12 gap-4 hover:bg-gray-50 px-2 transition text-[13px] text-black font-normal">
    @if(session('is_admin') === 'client')
        <a href="#" class="order-link items-center">
    @endif
        <div class="order-code col-span-2 text-black font-normal tracking-wide whitespace-nowrap"></div>
        <div class="col-span-2 flex items-center gap-2 text-black font-normal">
            @if(session('is_admin') === 'admin')
                <select name="status" class="order-status-select bg-gray-50 border border-gray-200 rounded-lg p-1 text-xs text-black font-medium focus:outline-none">
                    <option value="Confirmada">Confirmada</option>
                    <option value="En preparació">En preparació</option>
                    <option value="Lliurada">Lliurada</option>
                </select>
            @else
                <span class="order-status"></span>
            @endif
        </div>
        <div class="order-date col-span-2 text-black tracking-wide uppercase whitespace-nowrap font-normal"></div>
        <div class="col-span-2 flex items-center gap-2 text-black font-normal">
            <span class="order-availability truncate"></span>
        </div>
        <div class="col-span-2 text-right pr-2 font-bold text-black">
            <span class="order-total"></span>
        </div>
    @if(session('is_admin') === 'admin')
        <a href="#" class="order-link col-span-2 text-center text-[13px] text-blue-600 mt-1 font-normal tracking-wider cursor-pointer">
            detalls
    @else
        <div class="col-span-2 text-center text-[13px] text-blue-600 mt-1 font-normal tracking-wider cursor-pointer">detalls</div>
    @endif
    </a>
</div>
</template>