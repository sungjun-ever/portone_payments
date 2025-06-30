<?php

namespace App\Http\Requests\Merchant;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMerchantThingRequest extends FormRequest
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
        $this->merge([
           'name' => strip_tags($this->post('name')),
           'description' => strip_tags($this->post('description')),
           'price' => strip_tags($this->post('price')),
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
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'name' => [
                'required' => '상품명은 필수값입니다.',
                'max' => '상품명은 최대 100자까지 가능합니다.',
            ],
            'description' => [
                'max' => '상풀 설명은 최대 255자까지 가능합니다.'
            ],
            'price' => [
                'required' => '상품 가격은 필수값입니다.',
                'numeric' => '상품 가격 타입을 확인해주세요.'
            ],
        ];
    }
}
