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

class ProductPostpaidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = PPOBHelper::getPostpaidProductsPPOB();

        DB::beginTransaction();

        try {
            foreach ($products['data']['pasca'] as $product) {

                $category = ProductCategory::firstOrCreate(
                    [
                        'slug' => Str::slug($product['type'] . '-postpaid'),
                    ],
                    [
                        'name' => $product['type'] . ' Postpaid',
                        'icon' => '',
                        'is_billable' => true
                    ]
                );

                $type = ProductType::firstOrCreate(
                    [
                        'slug' => Str::slug($product['name'] . '-postpaid'),
                    ],
                    [
                        'product_category_id' => $category->id,
                        'name' => $product['name'] . ' Postpaid',
                        'icon' => ''
                    ]
                );

                Product::firstOrCreate(
                    [
                        'code' => $product['code'],

                    ],
                    [
                        'product_type_id' => $type->id,
                        'product_category_id' => $category->id,

                        'description' => $product['name'],
                        'denomination' => "",
                        'details' => "",
                        'icon' => "",

                        'price_origin' => $product['fee'] - $product['komisi'],
                        'price_markup' => $product['komisi'],
                        'price_sell' => $product['fee'],

                        'active_period' => "0",

                        'is_active' => true,
                    ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();
    }
}
