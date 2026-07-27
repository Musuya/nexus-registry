<div class="w-full max-w-3xl mx-auto">
    <!-- Premium Search Input -->
    <div class="relative group">
        <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-500"></div>
        <div class="relative luxury-glass rounded-xl flex items-center p-2">
            <flux:icon.magnifying-glass class="w-6 h-6 text-gray-400 ml-3" />
            <input 
                wire:model.live.debounce.300ms="query" 
                type="text" 
                placeholder="Search your perfect domain (e.g., nexus.co.nz)" 
                class="w-full bg-transparent border-none focus:ring-0 text-lg text-gray-900 dark:text-white placeholder-gray-400 px-4 py-3"
            >
            @if($isSearching)
                <flux:spinner class="mr-4 text-indigo-500" />
            @endif
        </div>
    </div>

    <!-- Real-time Results with Magnetic Hover -->
    @if($results)
        <div class="mt-6 luxury-glass rounded-2xl p-6 magnetic-element transition-all duration-300 animate-fade-in-up">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full {{ $results['available'] ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500' }} flex items-center justify-center">
                        <flux:icon.check-circle class="w-6 h-6" />
                    </div>
                    <div>
                        <flux:heading size="lg">{{ $results['domain'] }}</flux:heading>
                        <flux:text class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $results['available'] ? 'Available for registration' : 'Already registered' }}
                        </flux:text>
                    </div>
                </div>
                
                @if($results['available'])
                    <div class="text-right">
                        <flux:heading size="lg" class="gradient-text">${{ number_format($results['price'], 2) }}/yr</flux:heading>
                        <flux:button variant="primary" class="mt-2 shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-shadow">
                            Register Now
                        </flux:button>
                    </div>
                @else
                    <flux:button variant="secondary" disabled>Unavailable</flux:button>
                @endif
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fade-in-up 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>
@endpush