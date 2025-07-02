<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['nome' => 'Peça', 'sigla' => 'pc'],
            ['nome' => 'Metro', 'sigla' => 'm'],
            ['nome' => 'Quilo', 'sigla' => 'kg'],
            ['nome' => 'Hora', 'sigla' => 'h'],
        ];
        foreach ($units as $data) {
            Unit::create($data);
        }
    }
}
