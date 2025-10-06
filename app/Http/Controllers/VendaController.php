<?php
// app/Http/Controllers/VendaController.php

namespace App\Http\Controllers;

use App\Models\Venda;
use App\Models\Lote;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Parcela;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendaController extends Controller
{
    public function index()
    {
        $vendas = Venda::with(['cliente', 'lote', 'corretor'])->get();
        return view('vendas.index', compact('vendas'));
    }

    public function create()
    {
        $lotes = Lote::where('status', 'disponivel')->get();
        $clientes = Cliente::all();
        $corretores = User::all();
        
        return view('vendas.create', compact('lotes', 'clientes', 'corretores'));
    }

public function store(Request $request)
{
    $request->validate([
        'lote_id' => 'required|exists:lotes,id',
        'cliente_id' => 'required|exists:clientes,id',
        'valor_total' => 'required|numeric',
        'entrada' => 'required|numeric',
        'parcelas' => 'required|integer|min:1',
        'taxa_juros' => 'required|numeric|min:0',
        'data_venda' => 'required|date'
    ]);

    DB::transaction(function () use ($request) {
        // Criar venda com user_id do usuário autenticado
        $vendaData = $request->all();
        $vendaData['user_id'] = auth()->id();
        
        $venda = Venda::create($vendaData);
        
        // Atualizar status do lote
        $lote = Lote::find($request->lote_id);
        $lote->update(['status' => 'vendido']);
        
        // Calcular valor das parcelas
        $valorFinanciado = $request->valor_total - $request->entrada;
        $valorParcela = $valorFinanciado / $request->parcelas;
        
        // Criar parcelas
        $dataVencimento = now()->addMonth(); // Primeira parcela em 30 dias
        
        for ($i = 1; $i <= $request->parcelas; $i++) {
            \App\Models\Parcela::create([
                'venda_id' => $venda->id,
                'numero_parcela' => $i,
                'valor' => $valorParcela,
                'data_vencimento' => $dataVencimento->copy(),
                'status' => 'pendente'
            ]);
            
            $dataVencimento->addMonth();
        }
        
        // Se houve entrada, criar parcela de entrada como paga
        if ($request->entrada > 0) {
            \App\Models\Parcela::create([
                'venda_id' => $venda->id,
                'numero_parcela' => 0, // Entrada
                'valor' => $request->entrada,
                'data_vencimento' => now(),
                'data_pagamento' => now(),
                'valor_pago' => $request->entrada,
                'status' => 'pago'
            ]);
        }
    });

    return redirect()->route('vendas.index')
                     ->with('success', 'Venda registrada com sucesso!');
}

public function show(Venda $venda)
{
    $venda->load(['cliente', 'lote', 'corretor', 'parcelasVenda', 'boletos']);
    return view('vendas.show', compact('venda'));
}

    public function gerarContrato(Venda $venda)
    {
        $venda->load(['cliente', 'lote']);
        
        // Gerar número do contrato
        $numeroContrato = 'CTR-' . now()->format('Ymd') . '-' . $venda->id;
        
        // Criar contrato
        $contrato = $venda->contrato()->create([
            'numero_contrato' => $numeroContrato,
            'conteudo' => $this->gerarConteudoContrato($venda),
            'data_emissao' => now(),
            'status' => 'rascunho'
        ]);
        
        return redirect()->route('contratos.show', $contrato->id)
                         ->with('success', 'Contrato gerado com sucesso!');
    }

    private function gerarConteudoContrato(Venda $venda)
    {
        // Conteúdo básico do contrato - você pode personalizar
        return "
CONTRATO DE COMPRA E VENDA DE LOTE

Entre as partes:
VENDEDOR: " . config('app.name') . "
COMPRADOR: {$venda->cliente->nome}, {$venda->cliente->cpf_cnpj}

Fica acordado a venda do Lote {$venda->lote->numero}, Quadra {$venda->lote->quadra}, 
com área de {$venda->lote->area} m², pelo valor total de R$ " . number_format($venda->valor_total, 2, ',', '.') . ".

Forma de pagamento:
- Entrada: R$ " . number_format($venda->entrada, 2, ',', '.') . "
- {$venda->parcelas} parcelas de R$ " . number_format(($venda->valor_total - $venda->entrada) / $venda->parcelas, 2, ',', '.') . "

Local e data: " . now()->format('d/m/Y') . "

___________________________
VENDEDOR

___________________________
COMPRADOR
";
    }
}