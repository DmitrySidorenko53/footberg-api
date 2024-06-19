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
        Schema::create('mail_patterns', function (Blueprint $table) {
            $table->set('scope', ['confirmation', 'support', 'reset'])->unique();
            $table->primary('scope');

            $table->string('subject');
            $table->string('title');
            $table->longText('body');
            $table->string('footer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_patterns');
    }
};
