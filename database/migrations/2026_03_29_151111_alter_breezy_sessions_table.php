<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('breezy_sessions', function (Blueprint $table) {
            $table->dropColumn([
                'guard',
                'ip_address',
                'user_agent',
                'expires_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('breezy_sessions', function (Blueprint $table) {
            $table->after('panel_id', function (BluePrint $table) {
                $table->string('guard')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamp('expires_at')->nullable();
            });
        });
    }
};
