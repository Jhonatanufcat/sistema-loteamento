<?php
// app/Http/Controllers/FinanceiroController.php

namespace App\Http\Controllers;

use App\Models\Parcela;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceiroController extends Controller
{
    public function index()
    {
        // Resumo financeiro
        $resumo = [
            'recebido_mes' => Parcela::whereMonth('data_pagamento', now()->month)
                                   ->where('status', 'pago')
                                   ->sum('valor_pago'),
            'a_receber_mes' => Parcela::whereMonth('data_vencimento', now()->month)
                                    ->where('status', 'pendente')
                                    ->sum('valor'),
            'atrasados' => Parcela::where('data_vencimento', '<', now())
                                ->where('status', 'pendente')
                                ->sum('valor'),
            'total_vendas' => Venda::where('status', 'ativa')->sum('valor_total')
        ];
        
        // Fluxo de caixa próximo
        $fluxoCaixa = Parcela::with(['venda.cliente'])
                            ->where('status', 'pendente')
                            ->whereBetween('data_vencimento', [now(), now()->addDays(30)])
                            ->orderBy('data_vencimento')
                            ->get();
        
        return view('financeiro.index', compact('resumo', 'fluxoCaixa'));
    }
    
    public function relatorio(Request $request)
    {
        $dataInicio = $request->get('data_inicio', now()->startOfMonth()->format('Y-m-d'));
        $dataFim = $request->get('data_fim', now()->format('Y-m-d'));
        
        $parcelas = Parcela::with(['venda.cliente', 'venda.lote'])
                          ->whereBetween('data_vencimento', [$dataInicio, $dataFim])
                          ->orderBy('data_vencimento')
                          ->get();
        
        return view('financeiro.relatorio', compact('parcelas', 'dataInicio', 'dataFim'));
    }
}