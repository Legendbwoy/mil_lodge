<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('accommodation_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('report_type'); // amenity_issue, repair, renovation
            $table->string('title');
            $table->text('description');
            $table->string('location')->nullable();
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->json('images')->nullable();
            $table->string('status')->default('pending'); // pending, in_progress, resolved, cancelled
            $table->timestamp('resolved_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
};