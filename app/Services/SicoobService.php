<?php
// app/Services/SicoobService.php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SicoobService
{
    private $client;
    private $baseUrl;
    
    public function __construct()
    {
        $this->baseUrl = config('sicoob.base_url');
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getToken(),
                'client_id' => config('sicoob.client_id'),
                'Accept' => 'application/json'
            ],
            'verify' => false, // Apenas para desenvolvimento
            'timeout' => 30,
        ]);
    }
    
    private function getToken()
    {
        return Cache::remember('sicoob_token', 3500, function () {
            return $this->authenticate();
        });
    }
    
    private function authenticate()
    {
        try {
            // Implementar autenticação OAuth2 do Sicoob
            // Esta é uma implementação básica - ajuste conforme documentação oficial
            $authClient = new Client([
                'base_uri' => 'https://api.sicoob.com.br/',
                'verify' => false,
            ]);
            
            $response = $authClient->post('token', [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => config('sicoob.client_id'),
                    'client_secret' => config('sicoob.client_secret'),
                    'scope' => 'cobranca_boletos_consultar cobranca_boletos_incluir cobranca_boletos_pagador cobranca_boletos_segunda_via cobranca_boletos_baixa'
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return $data['access_token'];
            
        } catch (\Exception $e) {
            Log::error('Erro na autenticação Sicoob: ' . $e->getMessage());
            throw new \Exception('Falha na autenticação com Sicoob');
        }
    }
    
    /**
     * Incluir boleto no Sicoob
     */
    public function incluirBoleto(array $dadosBoleto)
    {
        try {
            Log::info('Enviando boleto para Sicoob:', $dadosBoleto);
            
            $response = $this->client->post('/boletos', [
                'json' => $dadosBoleto
            ]);
            
            $data = json_decode($response->getBody(), true);
            Log::info('Resposta Sicoob - Boleto incluído:', $data);
            
            return $data;
            
        } catch (\Exception $e) {
            Log::error('Erro ao incluir boleto no Sicoob: ' . $e->getMessage());
            throw new \Exception('Falha ao gerar boleto: ' . $e->getMessage());
        }
    }
    
    /**
     * Consultar boleto
     */
    public function consultarBoleto($nossoNumero)
    {
        try {
            $response = $this->client->get("/boletos?nossoNumero={$nossoNumero}");
            return json_decode($response->getBody(), true);
            
        } catch (\Exception $e) {
            Log::error('Erro ao consultar boleto: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Emitir segunda via
     */
    public function emitirSegundaVia($nossoNumero)
    {
        try {
            $response = $this->client->get("/boletos/segunda-via?nossoNumero={$nossoNumero}");
            return json_decode($response->getBody(), true);
            
        } catch (\Exception $e) {
            Log::error('Erro ao emitir segunda via: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Baixar boleto
     */
    public function baixarBoleto($nossoNumero)
    {
        try {
            $response = $this->client->post("/boletos/{$nossoNumero}/baixar");
            return json_decode($response->getBody(), true);
            
        } catch (\Exception $e) {
            Log::error('Erro ao baixar boleto: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Cadastrar webhook
     */
    public function cadastrarWebhook($urlWebhook)
    {
        try {
            $dados = [
                'url' => $urlWebhook,
                'codigoTipoMovimento' => 7, // Pagamento (baixa operacional)
                'codigoPeriodoMovimento' => 1, // Movimento Atual (D0)
                'email' => config('mail.from.address')
            ];
            
            $response = $this->client->post('/webhooks', ['json' => $dados]);
            return json_decode($response->getBody(), true);
            
        } catch (\Exception $e) {
            Log::error('Erro ao cadastrar webhook: ' . $e->getMessage());
            throw $e;
        }
    }
}