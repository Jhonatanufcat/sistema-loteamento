<?php
// app/Models/Venda.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;

    protected $fillable = [
        'lote_id',
        'cliente_id',
        'user_id',
        'valor_total',
        'entrada',
        'parcelas', // número total de parcelas
        'taxa_juros',
        'data_venda',
        'status',
        'observacoes'
    ];

    protected $casts = [
        'valor_total' => 'decimal:2',
        'entrada' => 'decimal:2',
        'taxa_juros' => 'decimal:2',
        'data_venda' => 'date'
    ];

    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function corretor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Renomear o relacionamento para evitar conflito
    public function parcelasVenda()
    {
        return $this->hasMany(Parcela::class);
    }

    public function boletos()
    {
        return $this->hasMany(Boleto::class);
    }

    public function contrato()
    {
        return $this->hasOne(Contrato::class);
    }

    public function getValorTotalFormatadoAttribute()
    {
        return 'R$ ' . number_format($this->valor_total, 2, ',', '.');
    }

    public function getEntradaFormatadaAttribute()
    {
        return 'R$ ' . number_format($this->entrada, 2, ',', '.');
    }

    public function getSaldoDevedorAttribute()
    {
        return $this->valor_total - $this->entrada - $this->parcelasVenda()->where('status', 'pago')->sum('valor_pago');
    }

    public function getParcelasPagasAttribute()
    {
        return $this->parcelasVenda()->where('status', 'pago')->count();
    }

    public function getTotalParcelasAttribute()
    {
        return $this->parcelasVenda()->count();
    }
}