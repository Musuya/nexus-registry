// app/Services/DomainLifecycleService.php
namespace App\Services;

use App\Models\Domain;
use App\Models\Registrar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DomainLifecycleService
{
    public function register(Registrar $registrar, string $domainName, array $nameservers, int $years = 1): Domain
    {
        return DB::transaction(function () use ($registrar, $domainName, $nameservers, $years) {
            // 1. Validate & Reserve (Prevent race conditions)
            $lockKey = "domain_lock:{$domainName}";
            if (!Cache::lock($lockKey, 3)->get()) {
                throw new \Exception("Domain is currently being processed.");
            }

            // 2. Financial Check (Double-entry ledger)
            $cost = config('registry.pricing.register') * $years;
            if ($registrar->balance < $cost) {
                throw new \Exception("Insufficient registrar balance.");
            }

            // 3. Create Domain
            $domain = Domain::create([
                'name' => $domainName,
                'registrar_id' => $registrar->id,
                'status' => 'ok',
                'created_at' => now(),
                'expires_at' => now()->addYears($years),
                'nameservers' => $nameservers,
            ]);

            // 4. Deduct Balance & Log Audit
            $registrar->decrement('balance', $cost);
            AuditLog::create([
                'registrar_id' => $registrar->id,
                'action' => 'domain_register',
                'target' => $domainName,
                'metadata' => ['years' => $years, 'cost' => $cost]
            ]);

            // 5. Async DNS & RDAP Sync (Non-blocking for sub-1.5s response)
            DispatchSyncJobs::afterCommit($domain);

            Cache::lock($lockKey, 3)->release();
            return $domain;
        });
    }

    public function transfer(string $domainName, Registrar $gainingRegistrar, string $authCode): Domain
    {
        // Implementation of RFC 5731 Transfer logic with 5-day pending state
        // ...
    }
}