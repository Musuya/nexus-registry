// app/Http/Controllers/Api/V1/DomainController.php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\DomainLifecycleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DomainController extends Controller
{
    public function __construct(private DomainLifecycleService $lifecycle) {}

    public function checkAvailability(Request $request, string $domain)
    {
        // Ultra-fast Redis cached check
        $exists = Cache::remember("domain_check:{$domain}", 60, function () use ($domain) {
            return \App\Models\Domain::where('name', $domain)->exists();
        });

        return response()->json([
            'domain' => $domain,
            'available' => !$exists,
            'premium' => $this->isPremiumName($domain),
            'pricing' => config('registry.pricing.register')
        ], 200, ['X-Response-Time' => app('request')->server('REQUEST_TIME_FLOAT') - LARAVEL_START]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain' => 'required|string|regex:/^[a-z0-9-]+\.[a-z]{2,}$/i',
            'nameservers' => 'required|array|min:2',
            'years' => 'integer|min:1|max:10'
        ]);

        $domain = $this->lifecycle->register(
            $request->user()->registrar, 
            $validator['domain'], 
            $validator['nameservers'], 
            $validator['years']
        );

        return response()->json(['data' => $domain, 'message' => 'Domain registered successfully'], 201);
    }
}