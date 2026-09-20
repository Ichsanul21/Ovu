<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('log_date');
            $table->integer('bleeding')->default(0);
            $table->integer('cramp')->nullable();
            $table->integer('headache')->nullable();
            $table->integer('breast_pain')->nullable();
            $table->integer('acne')->nullable();
            $table->integer('nausea')->nullable();
            $table->string('mood', 30)->nullable();
            $table->integer('energy')->nullable();
            $table->decimal('sleep_hours', 3, 1)->nullable();
            $table->integer('stress')->nullable();
            $table->string('cervical_fluid', 30)->nullable();
            $table->decimal('bbt', 4, 2)->nullable();
            $table->string('lh_test', 20)->nullable();
            $table->string('testpack', 20)->nullable();
            $table->boolean('intercourse')->default(false);
            $table->boolean('protected')->default(false);
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->text('symptoms')->nullable();
            $table->text('diary')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'log_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_logs');
    }
};
