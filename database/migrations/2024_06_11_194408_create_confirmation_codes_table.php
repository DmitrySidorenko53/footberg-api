<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropColumns('users', ['confirmation_code', 'confirmed_at', 'is_confirmed']);

        Schema::create('confirmation_codes', function (Blueprint $table) {
            $table->id('code_id');
            $table->string('code_text');
            $table->timestamp('created_at');
            $table->timestamp('valid_until');
            $table->timestamp('confirmed_at')->nullable();
            $table->tinyInteger('is_confirmed')->default(false);
            $table->tinyInteger('is_expired')->default(false);

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('confirmation_codes');
    }
};
