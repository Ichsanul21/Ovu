<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('wife')->after('password');
            $table->boolean('share_sensitive_with_partner')->default(false)->after('role');
            $table->string('partner_code', 10)->nullable()->after('share_sensitive_with_partner');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'share_sensitive_with_partner', 'partner_code']);
        });
    }
};
