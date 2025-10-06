<?php
// database/seeders/LoteSeeder.php

namespace Database\Seeders;

use App\Models\Lote;
use Illuminate\Database\Seeder;

class LoteSeeder extends Seeder
{
    public function run()
    {
        // Exemplo de lotes com coordenadas para teste
        $lotes = [
            ['numero' => '001', 'quadra' => 'A', 'area' => 300, 'valor' => 50000, 'pos_x' => 50, 'pos_y' => 50],
            ['numero' => '002', 'quadra' => 'A', 'area' => 350, 'valor' => 55000, 'pos_x' => 160, 'pos_y' => 50],
            ['numero' => '003', 'quadra' => 'A', 'area' => 400, 'valor' => 60000, 'pos_x' => 270, 'pos_y' => 50],
            ['numero' => '004', 'quadra' => 'B', 'area' => 320, 'valor' => 52000, 'pos_x' => 50, 'pos_y' => 150],
            ['numero' => '005', 'quadra' => 'B', 'area' => 380, 'valor' => 58000, 'pos_x' => 160, 'pos_y' => 150],
            ['numero' => '006', 'quadra' => 'B', 'area' => 420, 'valor' => 65000, 'pos_x' => 270, 'pos_y' => 150],
        ];

        foreach ($lotes as $lote) {
            Lote::create($lote);
        }
    }
}