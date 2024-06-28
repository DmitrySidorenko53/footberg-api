<?php

use App\Enums\EducationDegreeEnum;
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
        Schema::create('educational_degrees', function (Blueprint $table) {
            $table->enum('degree', EducationDegreeEnum::keys())->unique();
            $table->string('description')->nullable();

            $table->primary('degree');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educational_degrees');
    }
};
