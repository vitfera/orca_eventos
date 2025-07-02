<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\BudgetItemController;

// Rotas públicas
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('public/budgets/{token}', [BudgetController::class, 'publicShow']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('units', UnitController::class);
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('items', ItemController::class);
    Route::apiResource('prices', PriceController::class);
    Route::apiResource('budgets', BudgetController::class);
    Route::apiResource('budget-items', BudgetItemController::class);

    // Importação Excel de preços
    Route::post('prices/import/preview', [PriceController::class, 'importPreview']);
    Route::post('prices/import', [PriceController::class, 'import']);

    // Busca avançada de preços
    Route::get('search/prices', [PriceController::class, 'search']);

    // Histórico de preços de um item
    Route::get('items/{item}/prices', [ItemController::class, 'prices']);

    // Exportação de orçamentos
    Route::get('budgets/{budget}/export/pdf', [BudgetController::class, 'exportPdf']);
    Route::get('budgets/{budget}/export/excel', [BudgetController::class, 'exportExcel']);
});
