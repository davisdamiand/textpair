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
        //1. Parent table
        Schema::create('font_pairs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_dark_mode')->default(false);
            $table->boolean('same_font_allowed')->default(false);
            $table->timestamps();
        });

        Schema::create('font_headings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('font_pair_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->float('letter_spacing');
            $table->timestamps();
        });

        Schema::create('font_bodies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('font_pair_id')->constrained()->cascadeOnDelete();
            $table->integer('weight');
            $table->float('base_size');
            $table->float('line_height');
            $table->float('letter_spacing');
            $table->float('paragraph_width');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('font_pairs');
        Schema::dropIfExists('font_headings');
        Schema::dropIfExists('font_bodies');
    }
};
