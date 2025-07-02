<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Unit;

class UnitPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }
    public function view(User $user, Unit $unit)
    {
        return true;
    }
    public function create(User $user)
    {
        return $user->perfil === 'admin';
    }
    public function update(User $user, Unit $unit)
    {
        return $user->perfil === 'admin';
    }
    public function delete(User $user, Unit $unit)
    {
        return $user->perfil === 'admin';
    }
}
