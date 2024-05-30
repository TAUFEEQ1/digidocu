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
            $table->string('pub_title')->nullable();
            $table->string('pub_author')->nullable();
            $table->string('pub_key')->nullable();
            $table->mediumInteger('pub_fees')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            //
            $table->dropColumn('pub_title');
            $table->dropColumn('pub_author');
            $table->dropColumn('pub_fees');
            $table->dropColumn('pub_key');
        });
    }
};
