<?php

namespace App\Http\Controllers\Api\Authentication\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterCustomerAccountRequest;
use App\Repositories\AccountCustomer\AccountCustomerRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;


class AccountCustomerApi extends Controller
{
    protected $accountCustomerRepository;
    public function __construct(AccountCustomerRepository $accountCustomerRepository)
    {
        $this->accountCustomerRepository = $accountCustomerRepository;
        $this->middleware('auth:customer', ['except' => ['login', 'register']]);
    }
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|min:6'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 400);
            }
            $credentials = $request->only('email', 'password');
            $customer = $this->accountCustomerRepository->getModel()::where('email', $credentials['email'])->first();
            if (!$customer || !Hash::check($credentials['password'], $customer->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email hoặc mật khẩu không chính xác'
                ], 401);
            }
            $token = Auth::guard('customer')->login($customer);
            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công',
                'data' => [
                    'customer' => $customer,
                    'token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => Auth::guard('customer')->factory()->getTTL() * 60
                ]
            ])->withCookie('jwt', $token, Auth::guard('customer')->factory()->getTTL());
        } catch (Exception $e) {
            Log::error('Lỗi đăng nhập: ', ['error' => $e->getMessage(), 'email' => $request->email]);
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }
    public function register(RegisterCustomerAccountRequest $request)
    {
        $validated = $request->validated();
        DB::beginTransaction();
        try {
            $customer = $this->accountCustomerRepository->create($validated);

            DB::commit();
            $token = Auth::guard('customer')->login($customer);
            return response()->json([
                'success' => true,
                'message' => 'Đăng ký tài khoản thành công',
                'data' => [
                    'customer' => $customer,
                    'token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => Auth::guard('customer')->factory()->getTTL() * 60
                ]
            ], 200)->withCookie('jwt', $token, Auth::guard('customer')->factory()->getTTL());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Lỗi đăng ký: ', ['error' => $e->getMessage(), 'data' => $validated]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đăng ký tài khoản'
            ], 500);
        }
    }

    public function logout()
    {
        try {
            $token = JWTAuth::getToken();
            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy token'
                ], 401);
            }
            JWTAuth::invalidate($token);
            return response()->json([
                'success' => true,
                'message' => "Đăng xuất thành công"
            ], 200)->withCookie(cookie()->forget('jwt'));
        } catch (TokenExpiredException $e) {
            Log::error('Lỗi logout: Token đã hết hạn', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Token đã hết hạn'
            ], 401);
        } catch (TokenInvalidException $e) {
            Log::error('Lỗi logout: Token không hợp lệ', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Token không hợp lệ'
            ], 401);
        } catch (Exception $e) {
            Log::error('Lỗi logout: ', ['error' => $e->getMessage(), 'user_id' => auth('customer')->id() ?? 'N/A']);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đăng xuất'
            ], 500);
        }
    }
}
