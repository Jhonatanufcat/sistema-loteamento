<?php
// app/Http/Controllers/LoteController.php

namespace App\Http\Controllers;

use App\Models\Lote;
use Illuminate\Http\Request;

class LoteController extends Controller
{
    public function index()
    {
        $lotes = Lote::orderBy('numero')->get();
        return view('lotes.index', compact('lotes'));
    }

    public function create()
    {
        return view('lotes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required|unique:lotes',
            'quadra' => 'required',
            'area' => 'required|numeric',
            'valor' => 'required|numeric',
            'caracteristicas' => 'nullable|string'
        ]);

        Lote::create($request->all());

        return redirect()->route('lotes.index')
                         ->with('success', 'Lote cadastrado com sucesso!');
    }

    public function show(Lote $lote)
    {
        return view('lotes.show', compact('lote'));
    }

    public function edit(Lote $lote)
    {
        return view('lotes.edit', compact('lote'));
    }

    public function update(Request $request, Lote $lote)
    {
        $request->validate([
            'numero' => 'required|unique:lotes,numero,' . $lote->id,
            'quadra' => 'required',
            'area' => 'required|numeric',
            'valor' => 'required|numeric',
            'caracteristicas' => 'nullable|string'
        ]);

        $lote->update($request->all());

        return redirect()->route('lotes.index')
                         ->with('success', 'Lote atualizado com sucesso!');
    }

    public function destroy(Lote $lote)
    {
        // Verificar se o lote tem venda associada
        if ($lote->venda) {
            return redirect()->route('lotes.index')
                             ->with('error', 'Não é possível excluir um lote com venda associada!');
        }

        $lote->delete();

        return redirect()->route('lotes.index')
                         ->with('success', 'Lote excluído com sucesso!');
    }
}