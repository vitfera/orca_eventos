<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@orcamento.com',
            'password' => Hash::make('admin123'),
            'perfil' => 'admin',
        ]);
        User::create([
            'name' => 'Fornecedor Teste',
            'email' => 'fornecedor@orcamento.com',
            'password' => Hash::make('fornecedor123'),
            'perfil' => 'fornecedor',
        ]);
    }
}
