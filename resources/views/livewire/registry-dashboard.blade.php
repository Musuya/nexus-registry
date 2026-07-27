<div class="min-h-screen bg-gray-50 dark:bg-gray-950 transition-colors duration-500">
    <!-- Premium Header with Theme Toggle -->
    <header class="sticky top-0 z-50 luxury-glass border-b border-gray-200 dark:border-white/10">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                </div>
                <flux:heading size="xl" class="gradient-text">NexusRegistry</flux:heading>
            </div>

            <!-- Mandatory Theme Toggle -->
            <div class="flex items-center gap-2 bg-gray-200/50 dark:bg-white/5 p-1 rounded-xl backdrop-blur-md">
                <button wire:click="setTheme('light')" class="p-2 rounded-lg hover:bg-white dark:hover:bg-white/10 transition-all {{ $theme === 'light' ? 'bg-white dark:bg-white/10 shadow-sm' : '' }}">
                    <flux:icon.sun class="w-5 h-5 text-amber-500" />
                </button>
                <button wire:click="setTheme('dark')" class="p-2 rounded-lg hover:bg-white dark:hover:bg-white/10 transition-all {{ $theme === 'dark' ? 'bg-white dark:bg-white/10 shadow-sm' : '' }}">
                    <flux:icon.moon class="w-5 h-5 text-indigo-400" />
                </button>
                <button wire:click="setTheme('system')" class="p-2 rounded-lg hover:bg-white dark:hover:bg-white/10 transition-all {{ $theme === 'system' ? 'bg-white dark:bg-white/10 shadow-sm' : '' }}">
                    <flux:icon.computer-desktop class="w-5 h-5 text-gray-500 dark:text-gray-400" />
                </button>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-8 space-y-8">
        <!-- Three.js Canvas Container (Subtle background network effect) -->
        <div id="network-viz" class="absolute top-20 right-0 w-1/2 h-96 pointer-events-none opacity-30 dark:opacity-20"></div>

        <!-- Stats Grid with Magnetic Hover Effects -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($stats as $key => $value)
                <flux:card class="luxury-glass magnetic-element hover:scale-[1.02] transition-all duration-300 cursor-default group">
                    <div class="flex items-center justify-between">
                        <div>
                            <flux:text class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ str_replace('_', ' ', $key) }}
                            </flux:text>
                            <flux:heading size="xl" class="mt-2 gradient-text">{{ number_format($value) }}</flux:heading>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-indigo-500/10 flex items-center justify-center group-hover:bg-indigo-500/20 transition-colors">
                            <flux:icon.chart-bar class="w-6 h-6 text-indigo-500" />
                        </div>
                    </div>
                </flux:card>
            @endforeach
        </div>

        <!-- Recent Domains Table -->
        <flux:card class="luxury-glass overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
                <flux:heading size="lg">Recent Domain Registrations</flux:heading>
                <flux:button variant="ghost" icon="arrow-right" class="text-sm">View All</flux:button>
            </div>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Domain Name</flux:table.column>
                    <flux:table.column>Registrar</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                    <flux:table.column>Registered</flux:table.column>
                    <flux:table.column align="right">Actions</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach($recentDomains as $domain)
                        <flux:table.row class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <flux:table.cell class="font-mono text-indigo-600 dark:text-indigo-400 font-medium">
                                {{ $domain->name }}
                            </flux:table.cell>
                            <flux:table.cell>{{ $domain->registrar->name }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:badge color="{{ $domain->status === 'ok' ? 'green' : 'yellow' }}" size="sm">
                                    {{ $domain->status }}
                                </flux:badge>
                            </flux:table.cell>
                            <flux:table.cell class="text-gray-500 dark:text-gray-400">
                                {{ $domain->created_at->diffForHumans() }}
                            </flux:table.cell>
                            <flux:table.cell align="right">
                                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </flux:card>
    </main>
</div>

@push('styles')
<style>
    /* Premium CSS Patterns */
    .luxury-glass {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
    }
    .dark .luxury-glass {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }
    .gradient-text {
        background: linear-gradient(135deg, #4F46E5 0%, #9333EA 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .magnetic-element {
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease;
    }
    .magnetic-element:hover {
        transform: translateY(-4px) scale(1.01);
        box-shadow: 0 20px 40px rgba(79, 70, 229, 0.15);
    }
</style>
@endpush

@push('scripts')
<script>
    // Alpine.js handles the theme persistence seamlessly
    document.addEventListener('livewire:navigated', () => {
        const theme = @js($theme);
        if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    });

    // Magnetic hover effect logic
    document.querySelectorAll('.magnetic-element').forEach(el => {
        el.addEventListener('mousemove', (e) => {
            const rect = el.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            el.style.transform = `translate(${x * 0.05}px, ${y * 0.05}px) scale(1.02)`;
        });
        el.addEventListener('mouseleave', () => {
            el.style.transform = 'translate(0, 0) scale(1)';
        });
    });
</script>
@endpush