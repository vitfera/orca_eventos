<?php
namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        return Item::with(['category', 'unit'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'code' => 'nullable|string|max:255',
        ]);
        return Item::create($request->only('name', 'category_id', 'unit_id', 'code'));
    }

    public function show(Item $item)
    {
        return $item->load(['category', 'unit']);
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'code' => 'nullable|string|max:255',
        ]);
        $item->update($request->only('name', 'category_id', 'unit_id', 'code'));
        return $item;
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return response()->noContent();
    }

    public function prices(Item $item)
    {
        return $item->prices()->orderBy('data')->get();
    }
}
