<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\Venda;
use App\Models\Parcela;
use App\Models\Boleto;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Métricas principais
        $metricas = [
            'total_lotes' => Lote::count(),
            'lotes_vendidos' => Lote::where('status', 'vendido')->count(),
            'lotes_disponiveis' => Lote::where('status', 'disponivel')->count(),
            'total_clientes' => Cliente::count(),
            'vendas_mes' => Venda::whereMonth('data_venda', now()->month)->count(),
            'receita_mes' => Parcela::whereMonth('data_pagamento', now()->month)
                                  ->where('status', 'pago')
                                  ->sum('valor_pago'),
            'receber_mes' => Parcela::whereMonth('data_vencimento', now()->month)
                                  ->where('status', 'pendente')
                                  ->sum('valor'),
            'atrasados' => Parcela::where('data_vencimento', '<', now())
                                ->where('status', 'pendente')
                                ->count()
        ];

        // Gráfico de vendas por mês
        $vendasPorMes = Venda::select(
                DB::raw('MONTH(data_venda) as mes'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(valor_total) as valor')
            )
            ->whereYear('data_venda', now()->year)
            ->groupBy('mes')
            ->get();

        // Próximos vencimentos
        $proximosVencimentos = Parcela::with(['venda.cliente', 'venda.lote'])
            ->where('status', 'pendente')
            ->whereBetween('data_vencimento', [now(), now()->addDays(15)])
            ->orderBy('data_vencimento')
            ->limit(10)
            ->get();

        // Lotes recentemente vendidos
        $vendasRecentes = Venda::with(['cliente', 'lote'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'metricas', 
            'vendasPorMes', 
            'proximosVencimentos',
            'vendasRecentes'
        ));
    }

    public function getDadosGrafico()
    {
        $dados = Parcela::select(
                DB::raw('MONTH(data_vencimento) as mes'),
                DB::raw('SUM(CASE WHEN status = "pago" THEN valor ELSE 0 END) as recebido'),
                DB::raw('SUM(CASE WHEN status = "pendente" THEN valor ELSE 0 END) as a_receber')
            )
            ->whereYear('data_vencimento', now()->year)
            ->groupBy('mes')
            ->get();

        return response()->json($dados);
    }
}