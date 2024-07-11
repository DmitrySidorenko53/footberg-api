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
        Schema::table('mail_patterns', function (Blueprint $table) {
            $table->dropUnique('mail_patterns_scope_unique');
        });

        Schema::table('supported_locales', function (Blueprint $table) {
            $table->dropUnique('supported_locales_locale_unique');
        });

        Schema::table('security_tokens', function (Blueprint $table) {
            $table->dropUnique('security_tokens_token_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
