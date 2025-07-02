<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Som', 'Iluminação', 'Mobiliário', 'Alimentação', 'Segurança'];
        foreach ($categories as $nome) {
            Category::create(['nome' => $nome]);
        }
    }
}
