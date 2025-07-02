<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Item;

class ItemPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }
    public function view(User $user, Item $item)
    {
        return true;
    }
    public function create(User $user)
    {
        return $user->perfil === 'admin';
    }
    public function update(User $user, Item $item)
    {
        return $user->perfil === 'admin';
    }
    public function delete(User $user, Item $item)
    {
        return $user->perfil === 'admin';
    }
}
