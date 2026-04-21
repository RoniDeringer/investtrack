<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dividend_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dividend_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity_at_ex_date', 18, 8);
            $table->decimal('amount_received', 18, 8);
            $table->date('received_at')->nullable();
            $table->timestamps();

            $table->unique(['portfolio_id', 'dividend_id']);
            $table->index(['portfolio_id', 'received_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dividend_receipts');
    }
};

