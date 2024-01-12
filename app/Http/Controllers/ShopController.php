<?php

namespace App\Http\Controllers;

use App\Http\Requests\Shop\StoreShopRequest;
use App\Http\Requests\Shop\UpdateShopRequest;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('access.shop', ['only' => ['show', 'destroy']]);
    }

    public function index(Request $request): JsonResponse
    {
        $shops = Shop::filter($request)->where('merchat_id', auth()->id())->get();

        return response()->json([
            'data' => $shops,
        ]);
    }

    public function store(StoreShopRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $shop = Shop::create($validated);

        return response()->json([
            'data' => $shop,
        ], JsonResponse::HTTP_CREATED);
    }

    public function show(Shop $shop): JsonResponse
    {
        return response()->json([
            'data' => $shop,
        ], JsonResponse::HTTP_OK);
    }

    public function update(UpdateShopRequest $request, Shop $shop): JsonResponse
    {
        $validated = $request->validated();

        $shop->update($validated);

        return response()->json([
            'data' => $shop,
        ], JsonResponse::HTTP_OK);
    }

    public function destroy(Shop $shop): JsonResponse
    {
        $shop->delete();

        return response()->json([], JsonResponse::HTTP_NO_CONTENT);
    }

    public function findNearestShops(Request $request): JsonResponse
    {
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $shops = Shop::selectRaw('( 6371 * acos( cos( radians(?) ) *
                               cos( radians( latitude ) )
                               * cos( radians( longitude ) - radians(?)
                               ) + sin( radians(?) ) *
                               sin( radians( latitude ) ) )
                             ) AS distance, id, merchant_id,address, schedule, latitude, longitude',
            [$latitude, $longitude,$latitude])->orderBy('distance', 'asc')->take(5)->get();

        return response()->json([
            'data' => $shops,
        ]);
    }
}
