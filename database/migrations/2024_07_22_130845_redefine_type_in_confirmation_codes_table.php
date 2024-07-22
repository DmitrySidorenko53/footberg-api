<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('confirmation_codes', function (Blueprint $table) {
            $table->set('type', ['email', 'reset', 'phone'])->change();
            $table->index('type');
        });

        DB::table('confirmation_codes')->whereNull('type')->update([
            'type' => 'email'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('confirmation_codes', function (Blueprint $table) {
            $table->dropIndex('confirmation_codes_type_index');
            $table->set('type', ['confirm', 'reset'])->change();
        });

    }
};
