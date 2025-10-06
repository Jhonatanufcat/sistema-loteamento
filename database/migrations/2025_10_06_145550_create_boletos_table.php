<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateBoletosTable extends Migration
{
    public function up()
    {
        Schema::create('boletos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parcela_id')->constrained()->onDelete('cascade');
            $table->foreignId('venda_id')->constrained()->onDelete('cascade');
            $table->string('nosso_numero')->unique();
            $table->string('codigo_barras')->nullable();
            $table->string('linha_digitavel')->nullable();
            $table->decimal('valor', 10, 2);
            $table->date('data_vencimento');
            $table->date('data_emissao');
            $table->date('data_pagamento')->nullable();
            $table->decimal('valor_pago', 10, 2)->nullable();
            $table->enum('situacao', ['ABERTO', 'LIQUIDADO', 'BAIXADO', 'VENCIDO'])->default('ABERTO');
            $table->string('url_boleto')->nullable();
            $table->string('pagador_nome');
            $table->string('pagador_cpf_cnpj');
            $table->string('beneficiario_nome');
            $table->string('beneficiario_cpf_cnpj');
            $table->string('codigo_barras_baixa')->nullable();
            $table->string('agencia_recebedora')->nullable();
            $table->string('banco_recebedor')->nullable();
            $table->text('historico')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('boletos');
    }
}