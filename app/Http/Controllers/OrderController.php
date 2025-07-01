<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreOrderRequest;
use App\Service\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{

    public function __construct(
        private readonly OrderService $orderService
    )
    {
    }

    public function index()
    {

    }

    public function show()
    {

    }

    public function store(StoreOrderRequest $request)
    {
        Log::info("OrderController::pay");

        $data = $request->validated();

        $orderId = $this->orderService->storeOrder([
            'user_id' => $data['userId'],
            'order_number' => $data['orderNumber'],
            'total_amount' => $data['totalAmount'],
            'status' => $data['status'],
            'shipping_address' => $data['shippingAddress'],
            'payment_method' => $data['paymentMethod'],
        ], $data['orderItems']);

        return response()->json([
            'result' => 'success',
            'orderId' => $orderId,
        ], 201);
    }
}
