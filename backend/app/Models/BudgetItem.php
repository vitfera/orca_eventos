<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'budget_id', 'item_id', 'quantidade', 'valor_unitario', 'valor_total'
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
