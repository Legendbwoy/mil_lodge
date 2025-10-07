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
        Schema::table('accommodations', function (Blueprint $table) {
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available')->after('available_beds');
        });

        // Set initial status based on available_beds
        DB::table('accommodations')->update([
            'status' => DB::raw('CASE WHEN available_beds > 0 THEN "available" ELSE "occupied" END')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accommodations', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};