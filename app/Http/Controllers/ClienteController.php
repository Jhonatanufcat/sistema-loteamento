<?php
// app/Http/Controllers/ClienteController.php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('nome')->get();
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cpf_cnpj' => 'required|unique:clientes',
            'email' => 'nullable|email',
            'telefone' => 'nullable|string',
            'celular' => 'nullable|string'
        ]);

        Cliente::create($request->all());

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente cadastrado com sucesso!');
    }

    public function show(Cliente $cliente)
    {
        $vendas = $cliente->vendas()->with('lote')->get();
        return view('clientes.show', compact('cliente', 'vendas'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cpf_cnpj' => 'required|unique:clientes,cpf_cnpj,' . $cliente->id,
            'email' => 'nullable|email',
            'telefone' => 'nullable|string',
            'celular' => 'nullable|string'
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente atualizado com sucesso!');
    }

    public function destroy(Cliente $cliente)
    {
        if ($cliente->vendas()->count() > 0) {
            return redirect()->route('clientes.index')
                             ->with('error', 'Não é possível excluir um cliente com vendas associadas!');
        }

        $cliente->delete();

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente excluído com sucesso!');
    }
}