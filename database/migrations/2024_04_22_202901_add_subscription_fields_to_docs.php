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
            $table->string('sub_type')->nullable();
            $table->date('sub_start_date')->nullable();
            $table->date('sub_end_date')->nullable();
            $table->string('sub_payment_method')->nullable();
            $table->string('sub_payment_mobile_network')->nullable();
            $table->string('sub_payment_mobile_no')->nullable();
            $table->string('sub_payment_ref')->nullable();
            $table->bigInteger('sub_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('sub_amount');
            $table->dropColumn('sub_payment_ref');
            $table->dropColumn('sub_payment_mobile_no');
            $table->dropColumn('sub_payment_mobile_network');
            $table->dropColumn('sub_payment_method');
            $table->dropColumn('sub_end_date');
            $table->dropColumn('sub_start_date');
            $table->dropColumn('sub_type');
        });
    }
    
};
