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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'assignment_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId(column: 'quiz_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId(column: 'participant_id')->constrained()->onDelete('cascade');
            $table->string(column: 'option_text')->comment('point jawaban');
            $table->decimal(column: 'score', total: 5, places: 2)->nullable();
            $table->string(column: 'file_url')->nullable();
            $table->text(column: 'feedback')->nullable();
            $table->dateTime(column: 'graded_at')->nullable();
            $table->boolean(column: 'is_late')->default(0);
            $table->timestamp('submitted_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
