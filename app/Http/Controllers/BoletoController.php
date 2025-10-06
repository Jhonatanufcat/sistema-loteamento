<?php
// app/Http/Controllers/BoletoController.php

namespace App\Http\Controllers;

use App\Models\Boleto;
use App\Models\Parcela;
use App\Services\BoletoService;
use App\Services\SicoobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BoletoController extends Controller
{
    private $boletoService;
    
    public function __construct(BoletoService $boletoService)
    {
        $this->boletoService = $boletoService;
    }
    
    public function index()
    {
        $boletos = Boleto::with(['venda.cliente', 'parcela'])
                        ->orderBy('data_vencimento', 'desc')
                        ->get();
        
        return view('boletos.index', compact('boletos'));
    }
    
    public function gerarBoleto($parcela_id)
    {
        try {
            $parcela = Parcela::with(['venda.cliente', 'venda.lote'])->findOrFail($parcela_id);
            
            // Verificar se já existe boleto para esta parcela
            if ($parcela->boleto) {
                return redirect()->back()
                                 ->with('warning', 'Já existe um boleto gerado para esta parcela.');
            }
            
            $resultado = $this->boletoService->gerarBoleto($parcela);
            
            return redirect()->route('boletos.show', $resultado['boleto']->id)
                             ->with('success', $resultado['message'] ?? 'Boleto gerado com sucesso!');
            
        } catch (\Exception $e) {
            Log::error('Erro ao gerar boleto: ' . $e->getMessage());
            return redirect()->back()
                             ->with('error', 'Erro ao gerar boleto: ' . $e->getMessage());
        }
    }
    
    public function show(Boleto $boleto)
    {
        $boleto->load(['venda.cliente', 'parcela', 'venda.lote']);
        return view('boletos.show', compact('boleto'));
    }
    
    public function consultarBoleto(Boleto $boleto)
    {
        try {
            // Em desenvolvimento, apenas atualizamos localmente
            if (str_starts_with($boleto->nosso_numero, 'MOCK')) {
                $boleto->update(['situacao' => 'ABERTO']);
                return redirect()->route('boletos.show', $boleto->id)
                                 ->with('info', 'Modo desenvolvimento: Situação mantida como ABERTO');
            }
            
            // Se for boleto real, consultar Sicoob
            $sicoobService = app(SicoobService::class);
            $dados = $sicoobService->consultarBoleto($boleto->nosso_numero);
            
            if (isset($dados['situacao'])) {
                $boleto->update(['situacao' => $dados['situacao']]);
            }
            
            return redirect()->route('boletos.show', $boleto->id)
                             ->with('success', 'Situação do boleto atualizada!');
            
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Erro ao consultar boleto: ' . $e->getMessage());
        }
    }
    
    public function segundaVia(Boleto $boleto)
    {
        try {
            if (str_starts_with($boleto->nosso_numero, 'MOCK')) {
                return redirect()->back()
                                 ->with('info', 'Modo desenvolvimento: Use os dados exibidos acima');
            }
            
            $sicoobService = app(SicoobService::class);
            $segundaVia = $sicoobService->emitirSegundaVia($boleto->nosso_numero);
            
            return redirect()->away($segundaVia['url'])
                             ->with('success', 'Segunda via gerada com sucesso!');
            
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Erro ao gerar segunda via: ' . $e->getMessage());
        }
    }
    
    public function baixarBoleto(Boleto $boleto)
    {
        try {
            if (str_starts_with($boleto->nosso_numero, 'MOCK')) {
                $boleto->update(['situacao' => 'BAIXADO']);
                $boleto->parcela->update(['status' => 'cancelado']);
                
                return redirect()->route('boletos.show', $boleto->id)
                                 ->with('success', 'Boleto baixado em modo desenvolvimento!');
            }
            
            $sicoobService = app(SicoobService::class);
            $sicoobService->baixarBoleto($boleto->nosso_numero);
            
            $boleto->update(['situacao' => 'BAIXADO']);
            $boleto->parcela->update(['status' => 'cancelado']);
            
            return redirect()->route('boletos.show', $boleto->id)
                             ->with('success', 'Boleto baixado com sucesso!');
            
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Erro ao baixar boleto: ' . $e->getMessage());
        }
    }
}