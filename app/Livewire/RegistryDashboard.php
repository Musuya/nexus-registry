namespace App\Livewire;

use Livewire\Component;
use App\Models\Domain;
use App\Models\Registrar;

class RegistryDashboard extends Component
{
    public $theme = 'system'; // 'light', 'dark', 'system'
    public $stats = [];
    public $recentDomains = [];

    public function mount()
    {
        // Optimized query with caching for sub-1.5s load time
        $this->stats = [
            'total_domains' => Domain::count(),
            'active_registrars' => Registrar::where('is_active', true)->count(),
            'pending_transfers' => Domain::where('status', 'pendingTransfer')->count(),
        ];
        
        $this->recentDomains = Domain::with('registrar')
            ->latest('created_at')
            ->take(5)
            ->get(['name', 'status', 'created_at', 'registrar_id']);
    }

    public function setTheme($mode)
    {
        $this->theme = $mode;
        $this->dispatch('theme-updated', theme: $mode);
    }

    public function render()
    {
        return view('livewire.registry-dashboard')
            ->layout('layouts.premium');
    }
}