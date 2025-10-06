<?php
// app/Models/Contrato.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;

    protected $fillable = [
        'venda_id',
        'numero_contrato',
        'conteudo',
        'arquivo_path',
        'data_emissao',
        'status',
        'observacoes'
    ];

    protected $casts = [
        'data_emissao' => 'date'
    ];

    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }

    public function getStatusFormatadoAttribute()
    {
        $status = [
            'rascunho' => 'Rascunho',
            'assinado' => 'Assinado',
            'cancelado' => 'Cancelado'
        ];

        return $status[$this->status] ?? $this->status;
    }
}