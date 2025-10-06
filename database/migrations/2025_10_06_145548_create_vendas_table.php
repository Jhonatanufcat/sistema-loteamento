<?php
// database/migrations/2024_01_01_000003_create_vendas_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendasTable extends Migration
{
    public function up()
    {
        Schema::create('vendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lote_id')->constrained()->onDelete('cascade');
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Corretor
            $table->decimal('valor_total', 12, 2);
            $table->decimal('entrada', 12, 2)->default(0);
            $table->integer('parcelas')->default(1);
            $table->decimal('taxa_juros', 5, 2)->default(0);
            $table->date('data_venda');
            $table->enum('status', ['ativa', 'quitada', 'cancelada'])->default('ativa');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendas');
    }
}