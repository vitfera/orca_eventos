<?php
namespace App\Exports;

use App\Models\Budget;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BudgetExport implements FromCollection, WithHeadings
{
    protected $budget;

    public function __construct(Budget $budget)
    {
        $this->budget = $budget;
    }

    public function collection()
    {
        return $this->budget->items->map(function ($bi) {
            return [
                'item' => $bi->item->nome,
                'quantidade' => $bi->quantidade,
                'valor_unitario' => $bi->valor_unitario,
                'valor_total' => $bi->valor_total,
            ];
        });
    }

    public function headings(): array
    {
        return ['Item', 'Quantidade', 'Valor Unitário', 'Valor Total'];
    }
}
