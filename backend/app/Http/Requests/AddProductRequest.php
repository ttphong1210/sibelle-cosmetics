<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
class AddProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:0,1',
            'summary' => 'nullable|string',
            'description' => 'nullable|string',
            'promotion' => 'nullable|numeric|min:0',
            'category' => 'required|exists:categories,cate_id',
            'brand' => 'nullable|exists:brands,brand_id',
            'featured' => 'required|in:0,1',
            'imageAvatar' => 'nullable|mimes:jpeg,png,jpg,webp|max:2048', // Tối đa 2MB
            'gallery.*' => 'nullable|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery' => 'nullable|array|max:5'
        ];
    }
    public function messages(){
        return [
        'name.required' => 'Vui lòng nhập tên sản phẩm',
        'quantity.required' => 'Vui lòng nhập số lượng',
        'price.required' => 'Vui lòng nhập giá',
        'imageAvatar.mimes' => 'Vui lòng upload ảnh đúng định dạng',
        'imageAvatar.max' => 'Giới hạn kích thước ảnh 2MB',
        'gallery.max' => 'Upload tối đa 5 hình ảnh',
        'gallery.*.mimes' => 'Vui lòng upload ảnh đúng định dạng jpeg,png,jpg,webp',
        'gallery.*.max' => 'Giới hạn kích thước ảnh 2MB',
        'featured.required' => 'Vui lòng chọn sự nổi bật sản phẩm'
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
