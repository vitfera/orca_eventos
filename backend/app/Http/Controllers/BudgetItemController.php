<?php
namespace App\Http\Controllers;

use App\Models\BudgetItem;
use Illuminate\Http\Request;

class BudgetItemController extends Controller
{
    public function index()
    {
        return BudgetItem::with(['budget', 'item'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'budget_id' => 'required|exists:budgets,id',
            'item_id' => 'required|exists:items,id',
            'quantidade' => 'required|integer',
            'valor_unitario' => 'required|numeric',
            'valor_total' => 'required|numeric',
        ]);
        return BudgetItem::create($request->only('budget_id', 'item_id', 'quantidade', 'valor_unitario', 'valor_total'));
    }

    public function show(BudgetItem $budgetItem)
    {
        return $budgetItem->load(['budget', 'item']);
    }

    public function update(Request $request, BudgetItem $budgetItem)
    {
        $request->validate([
            'quantidade' => 'required|integer',
            'valor_unitario' => 'required|numeric',
            'valor_total' => 'required|numeric',
        ]);
        $budgetItem->update($request->only('quantidade', 'valor_unitario', 'valor_total'));
        return $budgetItem;
    }

    public function destroy(BudgetItem $budgetItem)
    {
        $budgetItem->delete();
        return response()->noContent();
    }
}
