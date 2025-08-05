<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
class RegisterCustomerAccountRequest extends FormRequest
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
            'number_phone' => 'required|digits_between:9,11|unique:account_customer,number_phone',
            'email' => 'required|email|unique:account_customer,email',
            'password' => 'required|string|min:6|confirmed',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập họ tên.',
            'number_phone.required' => 'Vui lòng nhập số điện thoại.',
            'number_phone.digits_between' => 'Số điện thoại phải có từ 9 đến 11 chữ số.',
            'number_phone.unique' => 'Số điện thoại đã được sử dụng.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã được đăng ký.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
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
