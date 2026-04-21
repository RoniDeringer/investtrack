<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dividends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->restrictOnDelete();
            $table->string('kind'); // DIVIDEND|JCP|RENT|...
            $table->date('ex_date');
            $table->date('payment_date')->nullable();
            $table->decimal('value_per_share', 18, 8);
            $table->string('currency', 3)->default('BRL');
            $table->string('source')->nullable();
            $table->timestamps();

            $table->index(['asset_id', 'ex_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dividends');
    }
};

