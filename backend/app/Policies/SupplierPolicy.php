<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Supplier;

class SupplierPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Supplier $supplier)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->perfil === 'admin';
    }

    public function update(User $user, Supplier $supplier)
    {
        return $user->perfil === 'admin';
    }

    public function delete(User $user, Supplier $supplier)
    {
        return $user->perfil === 'admin';
    }
}
