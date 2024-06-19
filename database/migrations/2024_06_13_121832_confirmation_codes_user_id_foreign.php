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
        Schema::table('confirmation_codes', function (Blueprint $table) {
            $table->dropForeign('confirmation_codes_user_id_foreign');

            $table->foreign('user_id')->references('user_id')->on('users')
            ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('confirmation_codes', function (Blueprint $table) {
            $table->dropForeign('confirmation_codes_user_id_foreign');

            $table->foreign('user_id')->references('user_id')->on('users');
        });
    }
};