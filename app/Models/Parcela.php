<?php
// app/Models/Parcela.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcela extends Model
{
    use HasFactory;

    protected $fillable = [
        'venda_id',
        'numero_parcela',
        'valor',
        'data_vencimento',
        'data_pagamento',
        'valor_pago',
        'status',
        'observacoes'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'valor_pago' => 'decimal:2',
        'data_vencimento' => 'date',
        'data_pagamento' => 'date'
    ];

    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }

    public function boleto()
    {
        return $this->hasOne(Boleto::class);
    }

    public function getValorFormatadoAttribute()
    {
        return 'R$ ' . number_format($this->valor, 2, ',', '.');
    }

    public function getValorPagoFormatadoAttribute()
    {
        return $this->valor_pago ? 'R$ ' . number_format($this->valor_pago, 2, ',', '.') : '-';
    }

    public function getDiasAtrasoAttribute()
    {
        if ($this->status === 'pendente' && $this->data_vencimento < now()) {
            return now()->diffInDays($this->data_vencimento);
        }
        return 0;
    }
}