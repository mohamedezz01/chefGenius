<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 
    public function up(): void
    {
        if (!Schema::hasTable('saved_recipes')) {
            Schema::create('saved_recipes', function (Blueprint $table) {
                $table->id(); 
                $table->foreignId('user_id')->constrained()->onDelete('cascade');

                $table->string('name'); 
                $table->text('description')->nullable(); 
                $table->json('ingredients')->nullable(); 
                $table->json('steps'); 
                $table->string('estimated_prep_time')->nullable();
                $table->string('difficulty')->nullable();
                $table->string('source')->default('gemini'); 
                $table->string('source_recipe_id')->nullable(); 
                $table->timestamps();

                $table->index('user_id');
            });
        }
    }
    public function down(): void
    {
        Schema::dropIfExists('saved_recipes');
    }
};
