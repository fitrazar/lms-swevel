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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('alias');
            $table->string('logo');
            $table->string('description');
            $table->string('phone');
            $table->string('address');
            $table->text('map')->nullable();
            $table->json('social_media')->nullable();
            $table->string('email')->nullable();
            $table->date('open_date')->nullable();
            $table->date('close_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
