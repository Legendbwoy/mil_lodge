<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add missing columns to amenities table
        Schema::table('amenities', function (Blueprint $table) {
            if (!Schema::hasColumn('amenities', 'category')) {
                $table->string('category')->default('general')->after('icon');
            }
            if (!Schema::hasColumn('amenities', 'description')) {
                $table->text('description')->nullable()->after('category');
            }
        });

        // Add missing columns to accommodations table
        Schema::table('accommodations', function (Blueprint $table) {
            if (!Schema::hasColumn('accommodations', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('bathrooms');
            }
            if (!Schema::hasColumn('accommodations', 'rating')) {
                $table->decimal('rating', 3, 2)->default(0)->after('is_featured');
            }
            if (!Schema::hasColumn('accommodations', 'review_count')) {
                $table->integer('review_count')->default(0)->after('rating');
            }
            if (!Schema::hasColumn('accommodations', 'images')) {
                $table->json('images')->nullable()->after('review_count');
            }
        });
    }

    public function down()
    {
        Schema::table('amenities', function (Blueprint $table) {
            $table->dropColumn(['category', 'description']);
        });

        Schema::table('accommodations', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'rating', 'review_count', 'images']);
        });
    }
};