<?php
namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BudgetExport;
use Barryvdh\DomPDF\Facades\Pdf;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $query = Budget::with('items');
        if ($request->user()) {
            $query->where('user_id', $request->user()->id);
        }
        return $query->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nome_evento' => 'required|string|max:255',
            'data_evento' => 'nullable|date',
            'observacoes' => 'nullable|string',
        ]);
        $budget = Budget::create(array_merge(
            $request->only('user_id', 'nome_evento', 'data_evento', 'observacoes'),
            [
                'link_publico' => Str::random(10),
                'code' => strtoupper(Str::random(8)),
            ]
        ));
        return $budget;
    }

    public function show(Budget $budget)
    {
        return $budget->load('items');
    }

    public function update(Request $request, Budget $budget)
    {
        $request->validate([
            'nome_evento' => 'required|string|max:255',
            'data_evento' => 'nullable|date',
            'observacoes' => 'nullable|string',
            'link_publico' => 'nullable|string',
        ]);
        $budget->update($request->only('nome_evento', 'data_evento', 'observacoes', 'link_publico'));
        return $budget;
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();
        return response()->noContent();
    }

    // Rota pública para exibir orçamento por token
    public function publicShow($token)
    {
        $budget = Budget::where('link_publico', $token)->with('items.item')->firstOrFail();
        return response()->json($budget);
    }

    // Exportar orçamento em PDF
    public function exportPdf(Budget $budget)
    {
        $budget->load('items.item');
        $pdf = Pdf::loadView('budgets.pdf', compact('budget'));
        return $pdf->download("orcamento_{$budget->id}.pdf");
    }

    // Exportar orçamento em Excel
    public function exportExcel(Budget $budget)
    {
        return Excel::download(new BudgetExport($budget), "orcamento_{$budget->id}.xlsx");
    }
}
