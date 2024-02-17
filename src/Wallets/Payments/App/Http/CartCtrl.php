<?php

namespace Src\Wallets\Payments\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Cart;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CartCtrl extends DomainBaseCtrl
{
    public function index()
    {
        $cart = Cart::query()
            ->where('customer_id', '=', auth()->user()->id)
            ->whereNull('order_number')
            ->get();

        return jsonResponse(Response::HTTP_OK, $cart);
    }

    /**
     * @throws Exception
     */
    public function store(Request $request): JsonResponse
    {
        $request->merge([
            'customer_id' => auth()->user()->id,
        ]);

        $request->validate([
            'service_id' => ['required'],
            'request_id' => ['nullable'],
            'price' => ['required'],
        ]);

        $data = Cart::query()->create($request->only(['service_id', 'request_id', 'price', 'customer_id']));

        return jsonResponse(Response::HTTP_OK, $data);
    }

    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'cart_id' => ['required', 'exists:App\Models\Cart,id'],
        ]);

        $cart = Cart::query()->find($request->cart_id);

        if (! $cart->delete()) {
            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => 'Cart item not removed',
            ]);
        }

        return jsonResponse(Response::HTTP_OK, [
            'message' => 'Cart item removed',
        ]);
    }
}
