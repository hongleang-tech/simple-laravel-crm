<div x-data="{
    toasts: [],
    init() {
        // Listen for toast events
        window.addEventListener('toast', (e) => {
            this.toasts.push({
                id: Date.now(),
                type: e.detail.type,
                message: e.detail.message
            });
        });
    }
}" class="fixed z-50 space-y-2 top-4 right-4" x-init="console.log('Toast container initialized')">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-full"
            x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-full"
            x-bind:class="{
                'bg-green-500': toast.type === 'success',
                'bg-red-500': toast.type === 'error',
                'bg-yellow-500': toast.type === 'warning',
                'bg-blue-500': toast.type === 'info'
            }"
            class="text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 min-w-[300px] max-w-md"
            @click="show = false" style="cursor: pointer;">
            <svg x-show="toast.type === 'success'" class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <svg x-show="toast.type === 'error'" class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <svg x-show="toast.type === 'warning' || toast.type === 'info'" class="flex-shrink-0 w-5 h-5" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="flex-1" x-text="toast.message"></span>
            <svg class="flex-shrink-0 w-4 h-4 hover:opacity-70" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
    </template>
</div>
