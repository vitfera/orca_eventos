<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Price;

class PricePolicy
{
    public function viewAny(User $user)
    {
        return true;
    }
    public function view(User $user, Price $price)
    {
        return true;
    }
    public function create(User $user)
    {
        // fornecedor pode criar preços apenas para seu próprio supplier
        return $user->perfil === 'admin';
    }
    public function update(User $user, Price $price)
    {
        return $user->perfil === 'admin';
    }
    public function delete(User $user, Price $price)
    {
        return $user->perfil === 'admin';
    }
}
