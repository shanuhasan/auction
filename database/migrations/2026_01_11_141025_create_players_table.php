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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('role', ['Batsman', 'Bowler', 'All-Rounder', 'Wicket-Keeper']);
            $table->string('base_price');
            $table->string('jersey_name')->nullable();
            $table->string('jersey_number')->nullable();
            $table->enum('status', ['available', 'sold', 'unsold'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
