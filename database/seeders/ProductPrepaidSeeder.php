<?php

namespace Database\Seeders;

use App\Helpers\PPOBHelper;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductPrepaidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = PPOBHelper::getPrepaidProductsPPOB();

        DB::beginTransaction();

        try {
            foreach ($products['data']['pricelist'] as $product) {

                $category = ProductCategory::firstOrCreate(
                    [
                        'slug' => Str::slug($product['product_category'] ?? 'unknown category'),
                    ],
                    [
                        'name' => $product['product_category'] ?? 'unknown category',
                        'icon' => '',
                        'is_billable' => false
                    ]
                );

                $type = ProductType::firstOrCreate(
                    [
                        'slug' => Str::slug($product['product_description'] ?? 'unknown type'),
                    ],
                    [
                        'product_category_id' => $category->id,
                        'name' => $product['product_description'] ?? 'unknown type',
                        'icon' => ''
                    ]
                );

                Product::firstOrCreate(
                    [
                        'code' => $product['product_code'],
                    ],
                    [
                    'product_type_id' => $type->id,
                    'product_category_id' => $category->id,

                    'description' => $product['product_description'],
                    'denomination' => $product['product_nominal'],
                    'details' => $product['product_details'],
                    'icon' => $product['icon_url'],

                    'price_origin' => $product['product_price'],
                    'price_markup' => 500,
                    'price_sell' => $product['product_price'] + 500,

                    'active_period' => $product['active_period'],

                    'is_active' => $product['status'] == 'active',
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();
    }
}
