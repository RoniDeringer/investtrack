<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained()->cascadeOnDelete();
            $table->foreignId('asset_id')->constrained()->restrictOnDelete();
            $table->string('type'); // BUY|SELL
            $table->dateTime('traded_at');
            $table->decimal('quantity', 18, 8);
            $table->decimal('price', 18, 8);
            $table->decimal('fee', 18, 8)->default(0);
            $table->decimal('tax', 18, 8)->default(0);
            $table->string('broker')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['portfolio_id', 'traded_at']);
            $table->index(['portfolio_id', 'asset_id']);
            $table->index(['asset_id', 'traded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};

