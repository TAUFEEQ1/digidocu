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
            $table->string('ad_subtitle')->nullable();
            $table->string('ad_payment_method')->nullable();
            $table->string('ad_category')->nullable();
            $table->string('ad_payment_mobile_network')->nullable();
            $table->string('ad_payment_mobile_no')->nullable();
            $table->string('ad_payment_ref')->nullable();
            $table->bigInteger('ad_amount')->nullable();
            $table->unsignedBigInteger('ad_registrar_id')->nullable();
            
            $table->foreign('ad_registrar_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Dropping foreign key constraint
            $table->dropForeign(['ad_registrar_id']);
    
            // Dropping added columns
            $table->dropColumn('ad_subtitle');
            $table->dropColumn('ad_payment_method');
            $table->dropColumn('ad_payment_mobile_network');
            $table->dropColumn('ad_payment_mobile_no');
            $table->dropColumn('ad_payment_ref');
            $table->dropColumn('ad_amount');
            $table->dropColumn('ad_category');
            $table->dropColumn('ad_registrar_id');
        });
    }
};
