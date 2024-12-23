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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('photo_url')->nullable();
            $table->string('name', 50);
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->string('phone_number', 15)->nullable();
            $table->timestamp('phone_number_verified_at')->nullable();
            $table->string('verification_token')->nullable();
            $table->text('address', 100)->nullable();
            $table->timestamps();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
