<div id="banner-container">
    <div id="error-banner" class="{{ $error_message ?? false ? '' : 'hidden' }} mb-6 bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3">
        <div class="text-red-500 mt-0.5">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <div>
            <h4 class="text-sm font-medium text-red-800">Avís del sistema</h4>
            <p id="error-message" class="text-xs text-red-700 mt-1 font-normal">{{ $error_message ?? false ? $error_message : 'Error message' }}</p>
        </div>
    </div>
    <div id="success-banner" class="{{ $success_message ?? false ? '' : 'hidden' }} mb-6 bg-green-50 border border-green-200 rounded-2xl p-4 flex items-start gap-3">
        <div class="text-green-500 mt-0.5">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <h4 class="text-sm font-medium text-green-800">Avís del sistema</h4>
            <p id="success-message" class="text-xs text-green-700 mt-1 font-normal">{{ $success_message ?? false ? $success_message : 'Success message' }}</p>
        </div>
    </div>
</div>