namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class DomainSearch extends Component
{
    public $query = '';
    public $results = [];
    public $isSearching = false;

    // Real-time debounced search (300ms)
    public function updatedQuery()
    {
        $this->isSearching = true;
        $this->results = [];
        
        // Simulated async API call (in production, hits local Redis/DB)
        $this->dispatch('check-domain', domain: $this->query)->self();
    }

    public function checkDomain($domain)
    {
        // Premium pattern: Non-blocking UI update
        $available = !\App\Models\Domain::where('name', $domain)->exists();
        $this->results = [
            'domain' => $domain,
            'available' => $available,
            'price' => 15.00
        ];
        $this->isSearching = false;
    }

    public function render()
    {
        return view('livewire.domain-search');
    }
}