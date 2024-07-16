<?php

use App\Enums\RoleEnum;
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
        Schema::table('roles', function (Blueprint $table) {
            $table->set('role_name', $this->keys(RoleEnum::cases(), true))->change();

            $table->renameColumn('role_name', 'shortcut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
