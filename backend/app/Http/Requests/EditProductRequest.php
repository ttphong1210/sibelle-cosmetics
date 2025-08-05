<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
class EditProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'prod_name' => 'required|string|max:255',
            'inventory_quantity' => 'required|integer|min:0',
            'prod_price' => 'required|numeric|min:0',
            'prod_status' => 'required|in:0,1',
            'prod_summary' => 'nullable|string',
            'prod_des' => 'nullable|string',
            'prod_promotion' => 'nullable|numeric|min:0',
            'prod_cate' => 'required|exists:categories,cate_id',
            'prod_brand' => 'nullable|exists:brands,brand_id',
            'prod_featured' => 'required|in:0,1',
            'imageAvatar' => 'nullable|mimes:jpeg,png,jpg|max:2048', // Giới hạn 2MB
            'gallery.*' => 'nullable|mimes:jpeg,png,jpg,webp|max:2048',
            'currentGallery' => 'nullable|string',
        ];
    }
    public function messages(){
        return [
            'prod_name.required' => 'Nhập tên sản phẩm'
        ];
    }
    protected function failedValidation(Validator $validator) 
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(
            [
                'error' => $errors,
                'status_code' => 422,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
