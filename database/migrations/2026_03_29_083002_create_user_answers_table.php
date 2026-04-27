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
        Schema::create('user_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(\App\Models\Question::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(\App\Models\Test::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('answer')->nullable();
            $table->json('answers')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->integer('points')->nullable();
            $table->string('ai_feedback')->nullable();
            $table->float('ai_score')->nullable();
            $table->boolean('checked_by_ai')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_answers');
    }
};
