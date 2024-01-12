<?php

namespace App\Http\Controllers;

use App\Http\Requests\Merchant\MerchantRequest;
use App\Http\Requests\Merchant\UpdateMerchantRequest;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    public function __construct()
    {
        $this->middleware('access.merchant', ['only' => ['show', 'destroy']]);
    }

    public function index(Request $request): JsonResponse
    {
        $role = Role::where('name', 'merchant')->first();
        $merchants = User::where('role_id', $role->id)->where('id', auth()->id())->filter($request)->with('role')->get();

        return response()->json([
            'data' => $merchants,
        ], JsonResponse::HTTP_OK);
    }

    public function store(MerchantRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $role = Role::where('name', 'merchant')->first();
        $validated['role_id'] = $role->id;
        $validated['password'] = bcrypt('123456789');
        $validated['registered_at'] = Carbon::now();
        $user = User::create($validated);

        return response()->json([
            'data' => $user,
        ], JsonResponse::HTTP_CREATED);
    }

    public function show(User $merchant): JsonResponse
    {
        return response()->json([
            'data' => $merchant->load('role'),
        ], JsonResponse::HTTP_OK);
    }

    public function update(UpdateMerchantRequest $request, User $merchant): JsonResponse
    {
        $validated = $request->validated();

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $merchant->update($validated);

        return response()->json([
            'data' => $merchant,
        ], JsonResponse::HTTP_OK);
    }

    public function destroy(User $merchant): JsonResponse
    {
        $merchant->delete();

        return response()->json([], JsonResponse::HTTP_NO_CONTENT);
    }
}
