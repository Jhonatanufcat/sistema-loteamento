<?php
// app/Services/BoletoService.php

namespace App\Services;

use App\Models\Boleto;
use App\Models\Parcela;
use Illuminate\Support\Str;

class BoletoService
{
    private $usarSicoob = false;
    
    public function __construct()
    {
        // Verificar se as credenciais do Sicoob estão configuradas
        $this->usarSicoob = !empty(config('sicoob.client_id')) && !empty(config('sicoob.client_secret'));
    }
    
    public function gerarBoleto(Parcela $parcela)
    {
        if ($this->usarSicoob) {
            return $this->gerarBoletoSicoob($parcela);
        } else {
            return $this->gerarBoletoMock($parcela);
        }
    }
    
    private function gerarBoletoMock(Parcela $parcela)
    {
        // Gerar dados mock para desenvolvimento
        $nossoNumero = 'MOCK' . now()->format('Ymd') . Str::random(6);
        $codigoBarras = '23793381286000782713695000063305575560000120000';
        $linhaDigitavel = '23791.36954 90000.633055 75560.000120 3 38128600078271';
        
        $boleto = Boleto::create([
            'parcela_id' => $parcela->id,
            'venda_id' => $parcela->venda_id,
            'nosso_numero' => $nossoNumero,
            'codigo_barras' => $codigoBarras,
            'linha_digitavel' => $linhaDigitavel,
            'valor' => $parcela->valor,
            'data_vencimento' => $parcela->data_vencimento,
            'data_emissao' => now(),
            'situacao' => 'ABERTO',
            'url_boleto' => null, // Em desenvolvimento, não temos URL real
            'pagador_nome' => $parcela->venda->cliente->nome,
            'pagador_cpf_cnpj' => $parcela->venda->cliente->cpf_cnpj,
            'beneficiario_nome' => config('sicoob.nome_beneficiario', 'Empresa de Desenvolvimento'),
            'beneficiario_cpf_cnpj' => config('sicoob.cnpj_beneficiario', '00.000.000/0000-00')
        ]);
        
        // Atualizar parcela
        $parcela->update(['boleto_id' => $boleto->id]);
        
        return [
            'success' => true,
            'boleto' => $boleto,
            'message' => 'Boleto gerado em modo de desenvolvimento'
        ];
    }
    
    private function gerarBoletoSicoob(Parcela $parcela)
    {
        // Usar o serviço real do Sicoob se configurado
        $sicoobService = app(SicoobService::class);
        
        $dadosBoleto = [
            'numeroContrato' => config('sicoob.numero_contrato'),
            'modalidade' => 1,
            'numeroContaCorrente' => config('sicoob.conta_corrente'),
            'especieDocumento' => 'DM',
            'dataEmissao' => now()->format('Y-m-d'),
            'dataVencimento' => $parcela->data_vencimento->format('Y-m-d'),
            'valor' => $parcela->valor,
            'seuNumero' => 'PARC-' . $parcela->venda_id . '-' . $parcela->numero_parcela,
            'pagador' => [
                'numeroCpfCnpj' => $parcela->venda->cliente->cpf_cnpj,
                'nome' => $parcela->venda->cliente->nome,
                'endereco' => $parcela->venda->cliente->endereco ?? 'Endereço não informado',
                'cep' => $parcela->venda->cliente->cep ?? '00000000',
                'cidade' => $parcela->venda->cliente->cidade ?? 'Cidade não informada',
                'uf' => $parcela->venda->cliente->uf ?? 'UF'
            ],
            'beneficiario' => [
                'numeroCpfCnpj' => config('sicoob.cnpj_beneficiario'),
                'nome' => config('sicoob.nome_beneficiario')
            ],
            'mensagem' => [
                'linha1' => 'Parcela ' . $parcela->numero_parcela . ' - Lote ' . $parcela->venda->lote->numero,
                'linha2' => 'Empreendimento: Seu Loteamento'
            ]
        ];
        
        $response = $sicoobService->incluirBoleto($dadosBoleto);
        
        $boleto = Boleto::create([
            'parcela_id' => $parcela->id,
            'venda_id' => $parcela->venda_id,
            'nosso_numero' => $response['nossoNumero'],
            'codigo_barras' => $response['codigoBarras'],
            'linha_digitavel' => $response['linhaDigitavel'],
            'valor' => $parcela->valor,
            'data_vencimento' => $parcela->data_vencimento,
            'data_emissao' => now(),
            'situacao' => 'ABERTO',
            'url_boleto' => $response['url'] ?? null,
            'pagador_nome' => $parcela->venda->cliente->nome,
            'pagador_cpf_cnpj' => $parcela->venda->cliente->cpf_cnpj,
            'beneficiario_nome' => config('sicoob.nome_beneficiario'),
            'beneficiario_cpf_cnpj' => config('sicoob.cnpj_beneficiario')
        ]);
        
        $parcela->update(['boleto_id' => $boleto->id]);
        
        return [
            'success' => true,
            'boleto' => $boleto,
            'url' => $boleto->url_boleto
        ];
    }
}