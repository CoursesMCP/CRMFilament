<?php

use App\Enums\DniTypeEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name')->nullable();
            $table->string('cellphone')->nullable()->unique();
            $table->string('dni_type')->default(DniTypeEnum::CC->value)->nullable();
            $table->string('dni')->nullable()->unique();
            $table->tinyInteger('active')->default(1);
            $table->tinyInteger('visits_per_day')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_name');
            $table->dropUnique('users_cellphone_unique');
            $table->dropColumn('cellphone');
            $table->dropColumn('dni_type');
            $table->dropUnique('users_dni_unique');
            $table->dropColumn('dni');
            $table->dropColumn('active');
            $table->dropColumn('visits_per_day');
        });
    }
};
