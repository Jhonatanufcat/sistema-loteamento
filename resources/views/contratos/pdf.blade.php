<!-- resources/views/contratos/pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contrato {{ $contrato->numero_contrato }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; }
        .content { margin: 20px 0; }
        .signatures { margin-top: 100px; }
        .signature-line { border-top: 1px solid #000; width: 300px; margin: 40px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>CONTRATO DE COMPRA E VENDA</h1>
        <h2>Nº {{ $contrato->numero_contrato }}</h2>
    </div>
    
    <div class="content">
        {!! nl2br(e($contrato->conteudo)) !!}
    </div>
    
    <div class="signatures">
        <div style="float: left; width: 45%;">
            <div class="signature-line"></div>
            <p><strong>VENDEDOR</strong><br>
            {{ config('sicoob.nome_beneficiario') }}<br>
            CNPJ: {{ config('sicoob.cnpj_beneficiario') }}</p>
        </div>
        
        <div style="float: right; width: 45%;">
            <div class="signature-line"></div>
            <p><strong>COMPRADOR</strong><br>
            {{ $contrato->venda->cliente->nome }}<br>
            CPF/CNPJ: {{ $contrato->venda->cliente->cpf_cnpj }}</p>
        </div>
        
        <div style="clear: both;"></div>
    </div>
    
    <div style="margin-top: 50px; text-align: center;">
        <p>Emitido em: {{ $contrato->data_emissao->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>