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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->boolean('is_executive_secretary')->default(FALSE);
            $table->boolean('is_registry_member')->default(FALSE);
            $table->boolean('is_managing_director')->default(FALSE);            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn(['is_executive_secretary','is_registry_member','is_managing_director']);
        });
    }
};
