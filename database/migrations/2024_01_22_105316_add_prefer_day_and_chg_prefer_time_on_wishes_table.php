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
        Schema::table('wishes', function (Blueprint $table) {
            $table->string('prefer_day')->nullable();
            $table->dropColumn('prefer_time_id');
            $table->string('prefer_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wishes', function (Blueprint $table) {
           $table->dropColumn('prefer_day');
           $table->dropColumn('prefer_time');
           $table->integer('prefer_time_id')->nullable();
        });
    }
};

