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
            $table->string('cr_reference_number')->nullable();
            $table->string('cr_department')->nullable();
            $table->string('cr_title')->nullable();
            $table->string('cr_purpose')->nullable();
            $table->mediumInteger('cr_amount')->nullable();

            // roles -> hod, finance_manager,internal_auditor,managing_director
            $table->unsignedBigInteger('cr_hod_id')->nullable();
            $table->unsignedBigInteger('cr_finance_manager_id')->nullable();
            $table->unsignedBigInteger('cr_internal_auditor_id')->nullable();
            $table->unsignedBigInteger('cr_managing_director_id')->nullable();

            $table->string('cr_hod_notes')->nullable();
            $table->string('cr_finance_manager_notes')->nullable();
            $table->string('cr_internal_auditor_notes')->nullable();
            $table->string('cr_managing_director_notes')->nullable();

            $table->dateTime('cr_hod_at')->nullable();
            $table->dateTime('cr_finance_manager_at')->nullable();
            $table->dateTime('cr_internal_auditor_at')->nullable();
            $table->dateTime('cr_managing_director_at')->nullable();

            $table->foreign('cr_hod_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cr_finance_manager_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cr_internal_auditor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cr_managing_director_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('cr_reference_number');
            $table->dropColumn('cr_department');
            $table->dropColumn('cr_title');
            $table->dropColumn('cr_purpose');
            $table->dropColumn('cr_amount');
    
            $table->dropForeign(['cr_hod_id']);
            $table->dropColumn('cr_hod_id');
    
            $table->dropForeign(['cr_finance_manager_id']);
            $table->dropColumn('cr_finance_manager_id');
    
            $table->dropForeign(['cr_internal_auditor_id']);
            $table->dropColumn('cr_internal_auditor_id');
    
            $table->dropForeign(['cr_managing_director_id']);
            $table->dropColumn('cr_managing_director_id');
        });
    }
};
