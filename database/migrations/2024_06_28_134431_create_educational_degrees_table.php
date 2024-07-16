<?php

use App\Enums\EducationDegreeEnum;
use App\Traits\EnumKeysTrait;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use EnumKeysTrait;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('educational_degrees', function (Blueprint $table) {
            $table->enum('degree',$this->keys(EducationDegreeEnum::cases(), true))->unique();
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
