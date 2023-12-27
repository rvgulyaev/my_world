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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('fio');
            $table->date('burndate');
            $table->text('diagnos');
            $table->text('contras');
            $table->string('mother')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('father')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('adress')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
