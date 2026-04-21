<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('ticker')->unique();
            $table->string('name')->nullable();
            $table->string('type'); // stock|fii|crypto|etf|bond
            $table->string('exchange')->nullable(); // ex: B3
            $table->string('currency', 3)->default('BRL');
            $table->timestamps();

            $table->index(['type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};

