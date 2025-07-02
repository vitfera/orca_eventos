<?php
namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        return Supplier::with('user')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'trade_name' => 'required|string|max:255',
            'cnpj' => 'required|string|max:20|unique:suppliers',
            'state' => 'required|string|size:2',
            'city' => 'nullable|string|max:255',
        ]);
        return Supplier::create($request->only('user_id', 'trade_name', 'cnpj', 'state', 'city'));
    }

    public function show(Supplier $supplier)
    {
        return $supplier->load('user');
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'trade_name' => 'required|string|max:255',
            'state' => 'required|string|size:2',
            'city' => 'nullable|string|max:255',
        ]);
        $supplier->update($request->only('trade_name', 'state', 'city'));
        return $supplier;
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return response()->noContent();
    }
}
