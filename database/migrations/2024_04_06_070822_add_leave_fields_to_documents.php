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
            $table->string('lv_reference_number')->nullable();
            $table->string('lv_designation')->nullable();
            $table->string('lv_department')->nullable();
            $table->string('lv_type')->nullable();
            $table->date('lv_start_date')->nullable();
            $table->date('lv_end_date')->nullable();

            $table->unsignedBigInteger('lv_line_manager_id')->nullable();
            $table->unsignedBigInteger('lv_hr_manager_id')->nullable();
            $table->unsignedBigInteger('lv_managing_director_id')->nullable();


            $table->foreign('lv_line_manager_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lv_hr_manager_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lv_managing_director_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Drop columns
            $table->dropColumn('lv_reference_number');
            $table->dropColumn('lv_designation');
            $table->dropColumn('lv_department');
            $table->dropColumn('lv_type');
            $table->dropColumn('lv_start_date');
            $table->dropColumn('lv_end_date');
    
            // Drop foreign keys
            $table->dropForeign(['lv_line_manager_id']);
            $table->dropForeign(['lv_hr_manager_id']);
            $table->dropForeign(['lv_managing_director_id']);
        });
    }
};
