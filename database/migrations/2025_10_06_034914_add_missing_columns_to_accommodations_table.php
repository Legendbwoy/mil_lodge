<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('accommodations', function (Blueprint $table) {
            // Add the missing type column
            if (!Schema::hasColumn('accommodations', 'type')) {
                $table->string('type')->nullable()->after('lodge_name');
            }
            
            // Add other missing columns if needed
            if (!Schema::hasColumn('accommodations', 'lodge_name')) {
                $table->string('lodge_name')->nullable()->after('description');
            }
            
            if (!Schema::hasColumn('accommodations', 'block_name')) {
                $table->string('block_name')->nullable()->after('lodge_name');
            }
            
            if (!Schema::hasColumn('accommodations', 'total_beds')) {
                $table->integer('total_beds')->default(0)->after('bathrooms');
            }
            
            if (!Schema::hasColumn('accommodations', 'available_beds')) {
                $table->integer('available_beds')->default(0)->after('total_beds');
            }
            
            if (!Schema::hasColumn('accommodations', 'square_feet')) {
                $table->decimal('square_feet', 8, 2)->nullable()->after('available_beds');
            }
            
            if (!Schema::hasColumn('accommodations', 'family_friendly')) {
                $table->boolean('family_friendly')->default(false)->after('square_feet');
            }
        });
    }

    public function down()
    {
        Schema::table('accommodations', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'lodge_name', 
                'block_name',
                'total_beds',
                'available_beds',
                'square_feet',
                'family_friendly'
            ]);
        });
    }
};