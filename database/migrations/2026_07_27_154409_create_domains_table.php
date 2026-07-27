<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique()->index(); // e.g., 'example.co.nz'
            $table->foreignUuid('registrar_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['ok', 'pendingTransfer', 'pendingDelete', 'clientHold'])->default('ok');
            $table->dateTime('created_at')->index();
            $table->dateTime('updated_at');
            $table->dateTime('expires_at')->index();
            $table->json('nameservers')->nullable(); // ['ns1.example.com', 'ns2.example.com']
            $table->foreignUuid('registrant_contact_id')->constrained('contacts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
