<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->integer('typical_cycle_length')->nullable()->after('goal');
            $table->integer('typical_period_length')->nullable()->after('typical_cycle_length');
            $table->boolean('is_teen')->default(false)->after('typical_period_length');
            $table->date('pregnancy_start')->nullable()->after('is_teen');
            $table->integer('strip_days')->default(7)->after('pregnancy_start');
            $table->text('visible_categories')->nullable()->after('strip_days');
            $table->string('kb_pill_time', 5)->nullable()->after('visible_categories');
            $table->boolean('kb_pill_active')->default(false)->after('kb_pill_time');
        });

        Schema::table('daily_logs', function (Blueprint $table) {
            $table->boolean('pill_taken')->default(false)->after('protected');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('pin_code', 255)->nullable()->after('partner_code');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('pin_code');
        });

        Schema::table('daily_logs', function (Blueprint $table) {
            $table->dropColumn('pill_taken');
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'typical_cycle_length', 'typical_period_length', 'is_teen',
                'pregnancy_start', 'strip_days', 'visible_categories',
                'kb_pill_time', 'kb_pill_active',
            ]);
        });
    }
};
