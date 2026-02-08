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
        Schema::table('team_players', function (Blueprint $table) {
            $table->tinyInteger('is_captain')->default(0)->after('player_id');
            $table->tinyInteger('is_wk')->default(0)->after('is_captain');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team_players', function (Blueprint $table) {
            //
        });
    }
};
