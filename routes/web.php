<?php

use App\Http\Controllers\MapaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VendaController;
use App\Http\Controllers\BoletoController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\WebhookSicoobController;



Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Lotes
    Route::resource('lotes', LoteController::class);
    
    // Clientes
    Route::resource('clientes', ClienteController::class);
    
    // Vendas
    Route::resource('vendas', VendaController::class);
    Route::post('/vendas/{venda}/gerar-contrato', [VendaController::class, 'gerarContrato'])->name('vendas.gerar-contrato');
    
    // Boletos
    Route::resource('boletos', BoletoController::class)->except(['create', 'store']);
    Route::post('/boletos/gerar/{parcela}', [BoletoController::class, 'gerarBoleto'])->name('boletos.gerar');
    Route::post('/boletos/{boleto}/consultar', [BoletoController::class, 'consultarBoleto'])->name('boletos.consultar');
    Route::get('/boletos/{boleto}/segunda-via', [BoletoController::class, 'segundaVia'])->name('boletos.segunda-via');
    Route::post('/boletos/{boleto}/baixar', [BoletoController::class, 'baixarBoleto'])->name('boletos.baixar');

 
        // Mapa do Loteamento
    Route::get('/mapa', [MapaController::class, 'index'])->name('mapa.index');
    Route::get('/mapa/lotes', [MapaController::class, 'getLotes'])->name('mapa.lotes');
    Route::post('/mapa/lotes/{lote}/coordenadas', [MapaController::class, 'updateCoordenadas'])->name('mapa.update-coordenadas');

    
    // Contratos
    Route::resource('contratos', ContratoController::class);
    Route::get('/contratos/{contrato}/download', [ContratoController::class, 'download'])->name('contratos.download');
    
    // Financeiro
    Route::get('/financeiro', [FinanceiroController::class, 'index'])->name('financeiro.index');
    Route::get('/financeiro/relatorio', [FinanceiroController::class, 'relatorio'])->name('financeiro.relatorio');
});

// Webhook Sicoob (sem autenticação)
Route::post('/api/webhook/sicoob/pagamentos', [WebhookSicoobController::class, 'handleWebhook'])
     ->name('webhook.sicoob.pagamentos');

// Autenticação
Auth::routes();
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
