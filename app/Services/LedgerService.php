// database/migrations/2024_01_01_000005_create_ledger_entries_table.php
Schema::create('ledger_entries', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('registrar_id')->constrained();
    $table->enum('type', ['credit', 'debit']);
    $table->decimal('amount', 15, 2);
    $table->string('reference_type'); // e.g., 'domain_register', 'wire_transfer'
    $table->string('reference_id')->nullable();
    $table->text('description');
    $table->decimal('running_balance', 15, 2); // Snapshot for fast reporting
    $table->timestamps();
});

// app/Services/LedgerService.php
public function recordTransaction(Registrar $registrar, string $type, float $amount, string $refType, ?string $refId, string $description): void
{
    DB::transaction(function () use ($registrar, $type, $amount, $refType, $refId, $description) {
        $currentBalance = $registrar->balance;
        $newBalance = $type === 'credit' ? $currentBalance + $amount : $currentBalance - $amount;

        if ($newBalance < 0) {
            throw new \Exception("Transaction would result in negative balance.");
        }

        LedgerEntry::create([
            'registrar_id' => $registrar->id,
            'type' => $type,
            'amount' => $amount,
            'reference_type' => $refType,
            'reference_id' => $refId,
            'description' => $description,
            'running_balance' => $newBalance,
        ]);

        $registrar->update(['balance' => $newBalance]);
    });
}