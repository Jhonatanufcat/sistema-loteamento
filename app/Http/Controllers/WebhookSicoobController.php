<?php
// app/Console/Commands/ConfigurarWebhookSicoob.php

namespace App\Console\Commands;

use App\Services\SicoobService;
use Illuminate\Console\Command;

class ConfigurarWebhookSicoob extends Command
{
    protected $signature = 'sicoob:configurar-webhook';
    protected $description = 'Configurar webhook para receber notificações de pagamento';

    public function handle(SicoobService $sicoobService)
    {
        try {
            $urlWebhook = config('app.url') . '/api/webhook/sicoob/pagamentos';
            $this->info("Configurando webhook para: {$urlWebhook}");
            
            $result = $sicoobService->cadastrarWebhook($urlWebhook);
            
            $this->info('✅ Webhook configurado com sucesso!');
            $this->info("ID Webhook: {$result['idWebhook']}");
            
        } catch (\Exception $e) {
            $this->error('❌ Erro ao configurar webhook: ' . $e->getMessage());
        }
    }
}