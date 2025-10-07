<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add beds tracking to accommodations table
        Schema::table('accommodations', function (Blueprint $table) {
            $table->integer('total_beds')->default(0);
            $table->integer('available_beds')->default(0);
            $table->string('block_name')->nullable(); // Block A, Block B, etc.
            $table->string('lodge_name')->nullable(); // Akafia Lodge or Oppong Peprah Lodge
        });

        // Modify bookings table for bed-based booking
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('number_of_beds')->default(1);
            $table->string('room_number')->nullable();
            $table->dropColumn('number_of_guests'); // Replace with beds
        });
    }

    public function down()
    {
        Schema::table('accommodations', function (Blueprint $table) {
            $table->dropColumn(['total_beds', 'available_beds', 'block_name', 'lodge_name']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['number_of_beds', 'room_number']);
            $table->integer('number_of_guests')->default(1);
        });
    }
};