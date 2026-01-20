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
        Schema::table('auctions', function (Blueprint $table) {
            $table->string('guid', 36)->unique()->nullable()->after('id');
            $table->string('logo')->nullable()->after('status');
            $table->tinyInteger('is_deleted')->default(0)->after('logo');
        });

        Schema::table('auction_players', function (Blueprint $table) {
            $table->string('guid', 36)->unique()->nullable()->after('id');
        });
        Schema::table('bids', function (Blueprint $table) {
            $table->string('guid', 36)->unique()->nullable()->after('id');
        });
        Schema::table('players', function (Blueprint $table) {
            $table->string('guid', 36)->unique()->nullable()->after('id');
            $table->string('image')->nullable()->after('jersey_number');
            $table->tinyInteger('is_deleted')->default(0)->after('status');
        });
        Schema::table('sessions', function (Blueprint $table) {
            $table->string('guid', 36)->unique()->nullable()->after('id');
        });
        Schema::table('team_players', function (Blueprint $table) {
            $table->string('guid', 36)->unique()->nullable()->after('id');
        });
        Schema::table('teams', function (Blueprint $table) {
            $table->string('guid', 36)->unique()->nullable()->after('id');
            $table->tinyInteger('status')->default(1)->after('logo');
            $table->tinyInteger('is_deleted')->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            //
        });
    }
};
