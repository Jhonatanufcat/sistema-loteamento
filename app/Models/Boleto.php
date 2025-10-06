<?php
// app/Models/Boleto.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boleto extends Model
{
    use HasFactory;

    protected $fillable = [
        'parcela_id',
        'venda_id',
        'nosso_numero',
        'codigo_barras',
        'linha_digitavel',
        'valor',
        'data_vencimento',
        'data_emissao',
        'data_pagamento',
        'valor_pago',
        'situacao',
        'url_boleto',
        'pagador_nome',
        'pagador_cpf_cnpj',
        'beneficiario_nome',
        'beneficiario_cpf_cnpj',
        'codigo_barras_baixa',
        'agencia_recebedora',
        'banco_recebedor',
        'historico'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'valor_pago' => 'decimal:2',
        'data_vencimento' => 'date',
        'data_emissao' => 'date',
        'data_pagamento' => 'date'
    ];

    public function parcela()
    {
        return $this->belongsTo(Parcela::class);
    }

    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }

    public function getValorFormatadoAttribute()
    {
        return 'R$ ' . number_format($this->valor, 2, ',', '.');
    }

    public function getValorPagoFormatadoAttribute()
    {
        return $this->valor_pago ? 'R$ ' . number_format($this->valor_pago, 2, ',', '.') : '-';
    }

    public function getSituacaoFormatadaAttribute()
    {
        $situacoes = [
            'ABERTO' => 'Aberto',
            'LIQUIDADO' => 'Pago',
            'BAIXADO' => 'Baixado',
            'VENCIDO' => 'Vencido'
        ];

        return $situacoes[$this->situacao] ?? $this->situacao;
    }
}