<?php
namespace App\Policies;

use App\Models\User;
use App\Models\BudgetItem;

class BudgetItemPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }
    public function view(User $user, BudgetItem $budgetItem)
    {
        return $user->perfil === 'admin' || $budgetItem->budget->user_id === $user->id;
    }
    public function create(User $user)
    {
        return in_array($user->perfil, ['admin', 'fornecedor']);
    }
    public function update(User $user, BudgetItem $budgetItem)
    {
        return $user->perfil === 'admin' || $budgetItem->budget->user_id === $user->id;
    }
    public function delete(User $user, BudgetItem $budgetItem)
    {
        return $user->perfil === 'admin' || $budgetItem->budget->user_id === $user->id;
    }
}
