<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



class CreateContratosTable extends Migration
{
    public function up()
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venda_id')->constrained()->onDelete('cascade');
            $table->string('numero_contrato')->unique();
            $table->text('conteudo');
            $table->string('arquivo_path')->nullable();
            $table->date('data_emissao');
            $table->enum('status', ['rascunho', 'assinado', 'cancelado'])->default('rascunho');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contratos');
    }
}