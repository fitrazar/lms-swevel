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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'assignment_id')->constrained()->onDelete('cascade');
            $table->foreignId(column: 'participant_id')->constrained()->onDelete('cascade');
            $table->string(column: 'file_url');
            $table->decimal(column: 'score', total: 5, places: 2);
            $table->text(column: 'feedback')->nullable();
            $table->dateTime(column: 'graded_at')->nullable();
            $table->boolean(column: 'is_late')->default(0);
            $table->timestamp(column: 'submitted_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
