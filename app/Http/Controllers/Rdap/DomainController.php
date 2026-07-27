// app/Http/Controllers/Rdap/DomainController.php
namespace App\Http\Controllers\Rdap;

use App\Models\Domain;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function show(Request $request, string $domainName)
    {
        $domain = Domain::where('name', strtolower($domainName))->firstOrFail();

        // Premium: Rate-limited, structured, privacy-compliant JSON response
        return response()->json([
            'objectClassName' => 'domain',
            'ldhName' => $domain->name,
            'status' => [$domain->status],
            'nameservers' => collect($domain->nameservers)->map(fn($ns) => ['ldhName' => $ns])->toArray(),
            'events' => [
                ['eventAction' => 'registration', 'eventDate' => $domain->created_at->toIso8601String()],
                ['eventAction' => 'expiration', 'eventDate' => $domain->expires_at->toIso8601String()],
            ],
            'entities' => [
                [
                    'objectClassName' => 'entity',
                    'roles' => ['registrar'],
                    'vcardArray' => [
                        'vcard',
                        [['version', {}, 'text', '4.0']],
                        [['fn', {}, 'text', $domain->registrar->name]],
                    ]
                ]
            ],
            'links' => [
                ['rel' => 'self', 'href' => url("/rdap/domain/{$domain->name}"), 'type' => 'application/rdap+json']
            ]
        ], 200, [
            'Content-Type' => 'application/rdap+json',
            'Access-Control-Allow-Origin' => '*' // CORS for RDAP clients
        ]);
    }
}