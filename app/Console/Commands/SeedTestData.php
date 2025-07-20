<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SeedTestData extends Command
{
    protected $signature = 'seed:test-data
                            {--products=10 : Число создаваемых товаров}
                            {--warehouses=3 : Число создаваемых складов}';

    protected $description = 'Команда для заполнения БД тестовыми данными о продуктах/складах и остатках';

    public function handle(): int
    {
        DB::transaction(function () {
           $this->createWarehouses();
           $this->createProducts();
           $this->createStocks();
        });

        $this->info('Данные успешно созданы!');
        return 0;
    }

    private function createWarehouses(): void
    {
        $warehouseCount = (int)$this->option('warehouses');

        Warehouse::factory()
            ->count($warehouseCount)
            ->create()
            ->each(function (Warehouse $warehouse) {
                $this->info("Склад {$warehouse->name} создан");
            });
    }

    private function createProducts(): void
    {
        $productCount = (int)$this->option('products');

        Product::factory()
            ->count($productCount)
            ->create()
            ->each(function (Product $product) {
                $this->info("Продукт {$product->name} создан");
            });
    }

    private function createStocks(): void
    {
        $warehouses = Warehouse::all();
        $products = Product::all();

        foreach ($warehouses as $warehouse) {
            foreach ($products as $product) {
                $stock = Stock::query()
                    ->firstOrCreate([
                       'product_id' => $product->id,
                       'warehouse_id' => $warehouse->id,
                       'stock' => rand(0, 100)
                    ]);
            }

            $this->info("Созданный остаток по товару {$product->name} на складе {$warehouse->name} - {$stock->stock}");
        }
    }
}
