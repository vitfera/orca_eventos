<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Category;
use App\Policies\CategoryPolicy;
use App\Models\Unit;
use App\Policies\UnitPolicy;
use App\Models\Supplier;
use App\Policies\SupplierPolicy;
use App\Models\Item;
use App\Policies\ItemPolicy;
use App\Models\Price;
use App\Policies\PricePolicy;
use App\Models\Budget;
use App\Policies\BudgetPolicy;
use App\Models\BudgetItem;
use App\Policies\BudgetItemPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Category::class => CategoryPolicy::class,
        Unit::class => UnitPolicy::class,
        Supplier::class => SupplierPolicy::class,
        Item::class => ItemPolicy::class,
        Price::class => PricePolicy::class,
        Budget::class => BudgetPolicy::class,
        BudgetItem::class => BudgetItemPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Additional gates if needed
    }
}
