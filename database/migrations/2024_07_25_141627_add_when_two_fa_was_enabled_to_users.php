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
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('enabled_two_step_verification', 'two_fa_enabled');
            $table->timestamp('two_fa_enabled_at')->nullable()->after('two_fa_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('two_fa_enabled', 'enabled_two_step_verification');
            $table->dropColumn('two_fa_enabled_at');
        });
    }
};
