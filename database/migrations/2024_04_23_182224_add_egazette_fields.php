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
            $table->date('gaz_published_on')->nullable();
            $table->string('gaz_issue_no')->nullable();
            $table->string('gaz_sub_category')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            //
            $table->dropColumn('gaz_published_on');
            $table->dropColumn('gaz_issue_no');
            $table->dropColumn('gaz_sub_category');
        });
    }
};
