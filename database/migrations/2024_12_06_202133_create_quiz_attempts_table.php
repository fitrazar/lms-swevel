<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'quiz_id')->constrained()->onDelete('cascade');
            $table->foreignId(column: 'participant_id')->constrained()->onDelete('cascade');
            $table->timestamp(column: 'attempt_date')->default(now());
            $table->decimal(column: 'score', total: 5, places: 2)->nullable();
            $table->boolean(column: 'is_late')->nullable();
            $table->string(column: 'difference')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
