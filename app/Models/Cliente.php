<?php
// app/Models/Cliente.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'cpf_cnpj',
        'email',
        'telefone',
        'celular',
        'endereco',
        'cep',
        'cidade',
        'uf'
    ];

    public function vendas()
    {
        return $this->hasMany(Venda::class);
    }

    public function boletos()
    {
        return $this->hasManyThrough(Boleto::class, Venda::class);
    }
}