<?php
// database/migrations/2024_01_01_000007_add_coordinates_to_lotes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoordinatesToLotesTable extends Migration
{
    public function up()
    {
        Schema::table('lotes', function (Blueprint $table) {
            $table->integer('pos_x')->nullable()->comment('Posição X no mapa');
            $table->integer('pos_y')->nullable()->comment('Posição Y no mapa');
            $table->integer('largura')->default(100)->comment('Largura do lote no mapa');
            $table->integer('altura')->default(80)->comment('Altura do lote no mapa');
            $table->string('cor')->default('#28a745')->comment('Cor do lote no mapa');
        });
    }

    public function down()
    {
        Schema::table('lotes', function (Blueprint $table) {
            $table->dropColumn(['pos_x', 'pos_y', 'largura', 'altura', 'cor']);
        });
    }
}