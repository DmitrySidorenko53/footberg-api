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
        Schema::table('educational_institutions', function (Blueprint $table) {
            $table->renameColumn('degree', 'degree_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('educational_institutions', function (Blueprint $table) {
            $table->renameColumn('degree_id', 'degree');
        });
    }
};