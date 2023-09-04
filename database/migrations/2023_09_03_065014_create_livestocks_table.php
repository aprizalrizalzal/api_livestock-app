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
        Schema::create('livestocks', function (Blueprint $table) {
            $table->id();
            $table->string('photo_url')->nullable();
            $table->enum('gender', ['Male', 'Female']);
            $table->integer('age', false, 10);
            $table->decimal('price', 10, 0);
            $table->boolean('sold');
            $table->string('detail');
            $table->timestamps();

            $table->foreignId('profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('livestock_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('livestock_species_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestocks');
    }
};
