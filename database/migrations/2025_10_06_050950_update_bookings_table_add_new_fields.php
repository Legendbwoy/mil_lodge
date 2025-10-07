<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBookingsTableAddNewFields extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Add new fields
            $table->string('service_number')->after('id');
            $table->string('rank')->after('service_number');
            $table->string('unit')->after('rank');
            $table->string('branch')->after('unit');
            $table->string('purpose')->after('special_requests');
            
            // Make total_amount nullable or set default
            $table->decimal('total_amount', 10, 2)->default(0)->change();
            
            // Optional: Rename old fields to new names
            $table->renameColumn('guest_name', 'full_name');
            $table->renameColumn('guest_email', 'email');
            $table->renameColumn('guest_phone', 'phone');
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['service_number', 'rank', 'unit', 'branch', 'purpose']);
            $table->renameColumn('full_name', 'guest_name');
            $table->renameColumn('email', 'guest_email');
            $table->renameColumn('phone', 'guest_phone');
        });
    }
}