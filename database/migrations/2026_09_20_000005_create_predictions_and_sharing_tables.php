<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('predictions_cache', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->date('next_period')->nullable();
            $table->date('ovulation_date')->nullable();
            $table->date('fertile_start')->nullable();
            $table->date('fertile_end')->nullable();
            $table->string('confidence', 20)->nullable();
            $table->decimal('avg_cycle', 5, 2)->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('push_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('endpoint');
            $table->text('p256dh');
            $table->text('auth_token');
            $table->timestamps();
        });

        Schema::create('partner_invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('code', 10)->unique();
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_invites');
        Schema::dropIfExists('push_subscriptions');
        Schema::dropIfExists('predictions_cache');
    }
};
