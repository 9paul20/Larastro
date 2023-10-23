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
        Schema::create('model_has_tags', function (Blueprint $table) {
            $table->unsignedBigInteger('tag_id')->index();
            $table->string('model_type');
            $table->unsignedBigInteger('model_id')->index();
            $table->timestamps();

            // Índices y claves foráneas
            $table->primary(['tag_id', 'model_type', 'model_id']);
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_has_tags');
    }
};