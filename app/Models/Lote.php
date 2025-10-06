<?php
// app/Models/Lote.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;

protected $fillable = [
    'numero',
    'quadra',
    'area',
    'valor',
    'status',
    'caracteristicas',
    'observacoes',
    'pos_x',
    'pos_y',
    'largura',
    'altura',
    'cor'
];

    protected $casts = [
        'valor' => 'decimal:2',
        'area' => 'decimal:2'
    ];

    public function venda()
    {
        return $this->hasOne(Venda::class);
    }

    public function getStatusFormatadoAttribute()
    {
        $status = [
            'disponivel' => 'Disponível',
            'reservado' => 'Reservado',
            'vendido' => 'Vendido'
        ];

        return $status[$this->status] ?? $this->status;
    }

    public function getValorFormatadoAttribute()
    {
        return 'R$ ' . number_format($this->valor, 2, ',', '.');
    }
    // Adicione estes métodos no final da classe:
public function getCorStatusAttribute()
{
    $cores = [
        'disponivel' => '#28a745', // Verde
        'reservado' => '#ffc107',  // Amarelo
        'vendido' => '#dc3545'     // Vermelho
    ];
    
    return $cores[$this->status] ?? '#6c757d';
}

public function getTooltipAttribute()
{
    return "Lote {$this->numero}\nQuadra: {$this->quadra}\nÁrea: {$this->area} m²\nValor: R$ " . number_format($this->valor, 2, ',', '.') . "\nStatus: {$this->status_formatado}";
}
}