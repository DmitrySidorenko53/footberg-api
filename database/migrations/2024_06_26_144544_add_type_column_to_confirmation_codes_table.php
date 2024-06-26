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
            $table->set('type', ['confirm', 'reset'])->default('confirm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('confirmation_codes', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
