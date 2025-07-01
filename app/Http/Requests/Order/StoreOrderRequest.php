<?php

namespace App\Http\Requests\Order;

use App\Enums\Order\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        $orderItems = $this->post('orderItems');

        $trimmedOrderItems = [];
        if (is_array($orderItems)) {
            $trimmedOrderItems = array_map(function ($item) {
                return [
                    'thingId' => trim($item['thingId'] ?? ''),
                    'quantity' => trim($item['quantity'] ?? ''),
                    'price' => trim($item['price'] ?? ''),
                ];
            }, $orderItems);
        }

        $this->merge([
            'userId' => trim($this->post('userId')),
            'orderNumber' => trim($this->post('orderNumber')),
            'totalAmount' => trim($this->post('totalAmount')),
            'status' => trim($this->post('status')),
            'shippingAddress' => strip_tags(trim($this->post('shippingAddress'))),
            'paymentMethod' => trim($this->post('paymentMethod')),
            'orderItems' => $trimmedOrderItems
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'userId' => 'required|integer|exists:users,id',
            'orderNumber' => 'required|string|max:25',
            'totalAmount' => 'required|decimal',
            'status' => ['nullable', Rule::enum(OrderStatus::class)],
            'shippingAddress' => 'required|string|max:255',
            'paymentMethod' => 'required|string|max:100',
            'orderItems' => 'required|array',
            'orderItems.*.thingId' => 'required|integer|exists:merchant_things,id',
            'orderItems.*.quantity' => 'required|integer',
            'orderItems.*.price' => 'required|decimal',
        ];
    }
}
