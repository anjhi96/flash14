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
        Schema::create('news_generation_runs', function (Blueprint $table) {
            $table->id();
            $table->enum('triggered_by', ['scheduler', 'manual'])->default('scheduler');
            $table->timestamp('started_at');
            $table->timestamp('finished_at')->nullable();
            $table->unsignedInteger('items_fetched')->default(0);
            $table->unsignedInteger('articles_created')->default(0);
            $table->enum('status', ['running', 'success', 'failed'])->default('running');
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_generation_runs');
    }
};
