<?php
namespace App\Http\Controllers;

use App\Models\Price;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Item;

class PriceController extends Controller
{
    public function index(Request $request)
    {
        $query = Price::with(['item', 'supplier']);
        if ($request->has('estado')) {
            $query->whereHas('supplier', function($q) use ($request) {
                $q->where('estado', $request->estado);
            });
        }
        // Filtros adicionais podem ser aplicados aqui
        return $query->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'value' => 'required|numeric',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        return Price::create($request->only('item_id', 'supplier_id', 'value', 'date', 'notes'));
    }

    public function show(Price $price)
    {
        return $price->load(['item', 'supplier']);
    }

    public function update(Request $request, Price $price)
    {
        $request->validate([
            'value' => 'required|numeric',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        $price->update($request->only('value', 'date', 'notes'));
        return $price;
    }

    public function destroy(Price $price)
    {
        $price->delete();
        return response()->noContent();
    }

    public function importPreview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx|max:' . env('MAX_UPLOAD_SIZE')
        ]);
        $rows = Excel::toCollection(null, $request->file('file'))->first();
        $summary = [];
        foreach ($rows->skip(1) as $row) {
            $item = Item::find($row[0]) ?? Item::where('codigo', $row[0])->first();
            $oldPrice = $item?->prices()->latest('date')->value('value');
            $summary[] = [
                'item_ref' => $row[0],
                'item_name' => $item?->name,
                'old_price' => $oldPrice,
                'new_price' => $row[1],
            ];
        }
        return response()->json($summary);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx|max:' . env('MAX_UPLOAD_SIZE')
        ]);
        $rows = Excel::toCollection(null, $request->file('file'))->first();
        foreach ($rows->skip(1) as $row) {
            $item = Item::find($row[0]) ?? Item::where('codigo', $row[0])->first();
            if ($item) {
                Price::create([
                    'item_id' => $item->id,
                    'supplier_id' => $request->user()->supplier->id,
                    'value' => $row[1],
                    'date' => now(),
                    'approved' => true,
                    'notes' => null,
                ]);
            }
        }
        return response()->json(['message' => 'Importação concluída']);
    }

    public function search(Request $request)
    {
        $query = Price::with(['item', 'supplier']);
        if ($request->filled('nome')) {
            $query->whereHas('item', fn($q) => $q->where('nome', 'like', "%{$request->nome}%"));
        }
        if ($request->filled('categoria_id')) {
            $query->whereHas('item', fn($q) => $q->where('category_id', $request->categoria_id));
        }
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('estado')) {
            $query->whereHas('supplier', fn($q) => $q->where('estado', $request->estado));
        }
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('data', [$request->from, $request->to]);
        }
        return $query->paginate($request->get('per_page', 20));
    }
}
