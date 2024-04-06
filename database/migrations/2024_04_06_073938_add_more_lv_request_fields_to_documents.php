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
        Schema::table('documents', function (Blueprint $table) {
            //
            $table->string('lv_line_manager_notes')->nullable();
            $table->string('lv_hr_manager_notes')->nullable();
            $table->string('lv_managing_director_notes')->nullable();
            
            $table->dateTime('lv_line_managed_at')->nullable();
            $table->dateTime('lv_hr_managed_at')->nullable();
            $table->dateTime('lv_managing_directed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            //
            $table->dropColumn('lv_line_manager_notes','lv_hr_manager_notes','lv_managing_director_notes',
            'lv_line_managed_at','lv_hr_managed_at','lv_managing_directed_at');
        });
    }
};
