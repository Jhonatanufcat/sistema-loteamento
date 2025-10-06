<?php
// app/Http/Controllers/ContratoController.php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Venda;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ContratoController extends Controller
{
    public function index()
    {
        $contratos = Contrato::with('venda.cliente')->get();
        return view('contratos.index', compact('contratos'));
    }

    public function show(Contrato $contrato)
    {
        $contrato->load(['venda.cliente', 'venda.lote']);
        return view('contratos.show', compact('contrato'));
    }

    // ADICIONAR ESTE MÉTODO UPDATE
    public function update(Request $request, Contrato $contrato)
    {
        $request->validate([
            'status' => 'required|in:rascunho,assinado,cancelado'
        ]);

        $contrato->update($request->only('status'));

        return redirect()->route('contratos.show', $contrato->id)
                         ->with('success', 'Contrato atualizado com sucesso!');
    }

    public function download(Contrato $contrato)
    {
        $contrato->load(['venda.cliente', 'venda.lote']);
        
        $pdf = PDF::loadView('contratos.pdf', compact('contrato'));
        
        return $pdf->download("contrato-{$contrato->numero_contrato}.pdf");
    }

    public function destroy(Contrato $contrato)
    {
        $contrato->delete();
        
        return redirect()->route('contratos.index')
                         ->with('success', 'Contrato excluído com sucesso!');
    }
}