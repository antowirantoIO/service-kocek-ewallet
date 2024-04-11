<?php

namespace App\Http\Controllers\API\V1\PPOB;

use App\Helpers\PPOBHelper;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class InquiryProductPrepaidController extends Controller
{
    /**
     * Get product pulsa data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductPulsaData(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'phone_number' => 'required|numeric',
            'type' => 'required|string|in:pulsa,data'
        ]);

        $operator = PPOBHelper::identifiedOperatorByNumberPhone($request->phone_number);
        $type = $request->type;

        if (!$operator) {
            return $this->error(
                false,
                'Operator not found',
                404
            );
        }

        $cache_key = 'product_' . $operator . '_' . $type;

        $products = cache()->remember($cache_key, config('cache.ttl'), function () use ($type, $operator) {
            return Product::whereHas('category', function ($query) use ($type) {
                $query->where('slug', $type);
            })->whereHas('type', function ($query) use ($operator) {
                $query->where('slug', 'like', '%' . strtolower($operator) . '%');
            })
                ->select('code', 'price_sell', 'denomination', 'is_discount', 'discount', 'details')
                ->orderBy('price_origin', 'asc')
                ->get();
        });

        return $this->success(
            true,
            'List of products',
            200,
            $products
        );
    }

    /**
     * Get customer pln prepaid
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomerPlnPrepaid(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'customer_id' => 'required|numeric'
        ]);

        $cacheKey = 'customer_pln_prepaid_' . $request->customer_id;
        $cacheTime = config('cache.ttl');

        $response = Cache::remember($cacheKey, $cacheTime, function () use ($request) {
            return PPOBHelper::getCustomerPlnPrepaid($request->customer_id);
        });

        if ($response['data']['status'] == "1") {
            return $this->success(
                true,
                'Customer pln prepaid',
                200,
                $response['data']
            );
        }

        return $this->error(
            false,
            'Customer not found',
            404
        );
    }

    /**
     * Get product pln prepaid
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductPlnPrepaid(Request $request): \Illuminate\Http\JsonResponse
    {
        $cache_key = 'product_' . Product::PLN . '_prepaid';

        $products = cache()->remember($cache_key, config('cache.ttl'), function () {
            return Product::whereHas('category', function ($query) {
                $query->where('slug', Product::PLN);
            })
                ->select('code', 'price_sell', 'denomination', 'is_discount', 'discount', 'details')
                ->orderBy('price_origin', 'asc')
                ->get();
        });

        return $this->success(
            true,
            'List of products',
            200,
            $products
        );
    }

    /**
     * Get product e-money
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductEmoney(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'type' => 'required|string|in:mandiri-e-toll,tapcash-bni'
        ]);

        $type = $request->type;

        $cache_key = 'product_' . $type;

        $products = cache()->remember($cache_key, config('cache.ttl'), function () use ($type) {
            return Product::whereHas('type', function ($query) use ($type) {
                $query->where('slug', $type);
            })
                ->select('code', 'price_sell', 'denomination', 'is_discount', 'discount', 'details')
                ->orderBy('price_origin', 'asc')
                ->get();
        });

        return $this->success(
            true,
            'List of products',
            200,
            $products
        );
    }

    /**
     * Get product e-materai
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function getProductEmaterai(Request $request): \Illuminate\Http\JsonResponse
    {
        $cache_key = 'product_' . Product::EMETERAI;

        $products = cache()->remember($cache_key, config('cache.ttl'), function () {
            return Product::whereHas('category', function ($query) {
                $query->where('slug', Product::EMETERAI);
            })
                ->select('code', 'price_sell', 'denomination', 'is_discount', 'discount', 'details')
                ->orderBy('price_origin', 'asc')
                ->get();
        });

        return $this->success(
            true,
            'List of products',
            200,
            $products
        );
    }

    /**
     * Get product type game
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function getProductTypeGame(Request $request): \Illuminate\Http\JsonResponse
    {
        $cache_key = 'product_' . Product::GAME;

        $products = cache()->remember($cache_key, 0, function () {
            return ProductType::whereHas('category', function ($query) {
                $query->where('slug', Product::GAME);
            })
                ->get();
        });

        return $this->success(
            true,
            'List of type products in game',
            200,
            $products
        );
    }

    /**
     * Get product game by type
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function getProductGameByType(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'type' => 'required|string'
        ]);

        $type = $request->type;

        $cache_key = 'product_' . $type;

        $products = cache()->remember($cache_key, config('cache.ttl'), function () use ($type) {
            return Product::whereHas('type', function ($query) use ($type) {
                $query->where('slug', $type);
            })
                ->select('code', 'price_sell', 'denomination', 'is_discount', 'discount', 'details')
                ->orderBy('price_origin', 'asc')
                ->get();
        });

        return $this->success(
            true,
            'List of products',
            200,
            $products
        );
    }

    /**
     * Get product type voucer
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function getProductTypeVoucher(Request $request): \Illuminate\Http\JsonResponse
    {
        $cache_key = 'product_' . Product::VOUCHER;

        $products = cache()->remember($cache_key, 0, function () {
            return ProductType::whereHas('category', function ($query) {
                $query->where('slug', Product::VOUCHER);
            })
                ->get();
        });

        return $this->success(
            true,
            'List of type products in voucher',
            200,
            $products
        );
    }

    /**
     * Get product game by type
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function getProductVoucherByType(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'type' => 'required|string'
        ]);

        $type = $request->type;

        $cache_key = 'product_' . $type;

        $products = cache()->remember($cache_key, config('cache.ttl'), function () use ($type) {
            return Product::whereHas('type', function ($query) use ($type) {
                $query->where('slug', $type);
            })
                ->select('code', 'price_sell', 'denomination', 'is_discount', 'discount', 'details')
                ->orderBy('price_origin', 'asc')
                ->get();
        });

        return $this->success(
            true,
            'List of products',
            200,
            $products
        );
    }
}
