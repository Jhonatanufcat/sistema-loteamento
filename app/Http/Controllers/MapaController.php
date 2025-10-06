<?php
// app/Http/Controllers/MapaController.php

namespace App\Http\Controllers;

use App\Models\Lote;
use Illuminate\Http\Request;

class MapaController extends Controller
{
    public function index()
    {
        $lotes = Lote::all();
        return view('mapa.index', compact('lotes'));
    }

    public function getLotes()
    {
        $lotes = Lote::all()->map(function ($lote) {
            return [
                'id' => $lote->id,
                'numero' => $lote->numero,
                'quadra' => $lote->quadra,
                'area' => $lote->area,
                'valor' => $lote->valor,
                'status' => $lote->status,
                'status_formatado' => $lote->status_formatado,
                'pos_x' => $lote->pos_x ?? 0,
                'pos_y' => $lote->pos_y ?? 0,
                'largura' => $lote->largura ?? 100,
                'altura' => $lote->altura ?? 80,
                'cor' => $this->getCorStatus($lote->status),
                'tooltip' => $this->getTooltip($lote),
                'url_editar' => route('lotes.edit', $lote->id),
                'url_visualizar' => route('lotes.show', $lote->id)
            ];
        });

        return response()->json($lotes);
    }

    private function getCorStatus($status)
    {
        $cores = [
            'disponivel' => '#28a745', // Verde
            'reservado' => '#ffc107',  // Amarelo
            'vendido' => '#dc3545'     // Vermelho
        ];
        
        return $cores[$status] ?? '#6c757d';
    }

    private function getTooltip($lote)
    {
        return "Lote {$lote->numero}\nQuadra: {$lote->quadra}\nÁrea: {$lote->area} m²\nValor: R$ " . number_format($lote->valor, 2, ',', '.') . "\nStatus: " . $this->getStatusFormatado($lote->status);
    }

    private function getStatusFormatado($status)
    {
        $statusFormatados = [
            'disponivel' => 'Disponível',
            'reservado' => 'Reservado', 
            'vendido' => 'Vendido'
        ];
        
        return $statusFormatados[$status] ?? $status;
    }

    public function updateCoordenadas(Request $request, Lote $lote)
    {
        $request->validate([
            'pos_x' => 'required|integer',
            'pos_y' => 'required|integer',
            'largura' => 'required|integer|min:50',
            'altura' => 'required|integer|min:50'
        ]);

        $lote->update($request->only(['pos_x', 'pos_y', 'largura', 'altura']));

        return response()->json(['success' => true, 'message' => 'Coordenadas atualizadas!']);
    }
}