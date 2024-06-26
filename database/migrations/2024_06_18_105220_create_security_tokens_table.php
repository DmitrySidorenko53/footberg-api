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
        Schema::create('security_tokens', function (Blueprint $table) {
            $table->string('token', 255)->unique();
            $table->timestamp('valid_until');
            $table->boolean('is_valid');
            $table->boolean('is_deleted')->default(false);
            $table->timestamp('created_at');
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('user_id');

            $table->primary('token');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_tokens');
    }
};
