<template id="orders-list-item">
<div class="order-row">
    <div class="banner-row-content"></div>
    @if(session('is_admin') === 'client') <a href="#" class="order-link items-center"> @endif
    <div class="order-row-content py-3 grid grid-cols-12 gap-4 hover:bg-gray-50 px-2 transition text-[13px] text-black font-normal">
            <div class="order-code col-span-2 text-black font-normal tracking-wide whitespace-nowrap"></div>
            <div class="col-span-2 flex items-center gap-2 text-black font-normal">
                @if(session('is_admin') === 'admin')
                    <select name="status" class="order-status-select bg-[#fffacd] hover:bg-[#fff27e] border border-[#bed1dc] rounded-lg p-1 text-xs text-black font-medium focus:outline-none" data-order-id="">
                        @foreach($statusesList as $status)
                            <option value="{{ $status['status'] }}" data-status-id="{{ $status['id'] }}">{{ $status['status'] }}</option>
                        @endforeach
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
            </a>
        @else
            <div class="col-span-2 text-center text-[13px] text-blue-600 mt-1 font-normal tracking-wider cursor-pointer">detalls</div>
        @endif
    </div>
    @if(session('is_admin') === 'client') </a> @endif
</div>
</template>