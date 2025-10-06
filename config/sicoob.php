<?php
// config/sicoob.php

return [
    'base_url' => env('SICOOB_BASE_URL', 'https://api.sicoob.com.br/cobranca-bancaria/v3'),
    'client_id' => env('SICOOB_CLIENT_ID'),
    'client_secret' => env('SICOOB_CLIENT_SECRET'),
    'cert_path' => env('SICOOB_CERT_PATH'),
    'cert_password' => env('SICOOB_CERT_PASSWORD'),
    'numero_contrato' => env('SICOOB_NUMERO_CONTRATO'),
    'conta_corrente' => env('SICOOB_CONTA_CORRENTE'),
    'cnpj_beneficiario' => env('SICOOB_CNPJ_BENEFICIARIO'),
    'nome_beneficiario' => env('SICOOB_NOME_BENEFICIARIO'),
];