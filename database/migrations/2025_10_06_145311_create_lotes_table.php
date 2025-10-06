<?php
// database/migrations/2024_01_01_000002_create_lotes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotesTable extends Migration
{
    public function up()
    {
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->string('quadra');
            $table->decimal('area', 10, 2);
            $table->decimal('valor', 12, 2);
            $table->enum('status', ['disponivel', 'reservado', 'vendido'])->default('disponivel');
            $table->text('caracteristicas')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lotes');
    }
}