<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Budget;

class BudgetPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Budget $budget)
    {
        return $user->perfil === 'admin' || $budget->user_id === $user->id;
    }

    public function create(User $user)
    {
        return in_array($user->perfil, ['admin', 'fornecedor']);
    }

    public function update(User $user, Budget $budget)
    {
        return $user->perfil === 'admin' || $budget->user_id === $user->id;
    }

    public function delete(User $user, Budget $budget)
    {
        return $user->perfil === 'admin' || $budget->user_id === $user->id;
    }
}
