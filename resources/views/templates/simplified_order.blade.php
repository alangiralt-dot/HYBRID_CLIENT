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