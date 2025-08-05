<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Mail\ResetPasswordMail;
use App\Repositories\Admin\Eloquent\AccountAdminRepository;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterAccountAuthRequest;
use Carbon\Carbon;
use Illuminate\Foundation\Console\Presets\React;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthControllerApi extends Controller
{
    protected $accountAuthRepository;
    public function __construct(AccountAdminRepository $accountAuthRepository)
    {
        $this->accountAuthRepository = $accountAuthRepository;
        $this->middleware('jwt.auth', ['except' => ['actionLoginAuth']]);
    }
    public function actionLoginAuth(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            $token = JWTAuth::attempt($credentials);
            if (!$token) {
                return response()->json([
                    'message' => 'Sai thông tin đăng nhập'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo token',
                'error' => $e->getMessage()
            ], 500);
        }
        $user = auth()->user();
        return response()->json([
            'success' => true,
            'message' => 'Login Successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => config('jwt.ttl') * 60,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'number_phone' => $user->number_phone,
                'image' => $user->image
            ],
        ], 200);
    }
    /**
     * LogOut - Invalidate token
     */
    public function actionLogOutAuth(Request $request)
    {
    
        try {
            $token = JWTAuth::getToken();
            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token không được cung cấp'
                ], 401);
            }
            JWTAuth::invalidate($token);
            return response()->json([
                'success' => true,
                'message' => 'Đăng xuất thành công'
            ], 200);

        } catch (TokenExpiredException $e) {
            Log::error('Error Log out' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Token đã hết hạn, nhưng đã đăng xuất thành công'
            ], 200);
        } catch (JWTException $e) {
            Log::error('Error Log out' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể đăng xuất: ' . $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            Log::error('Error Log out' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function actionRegisterAccountAuth(RegisterAccountAuthRequest $request)
    {
        $dataValidated = $request->validated();
        $this->accountAuthRepository->createNewAccount($dataValidated);
        return response()->json([
            'message' => 'Đăng ký tải khoản thành công, sẽ quay về trang đăng nhập !'
        ]);
    }
    public function actionForgotPasswordAuthAccount(Request $request)
    {
        $rules = [
            'email' => 'required|email|exists:vp_users,email'
        ];
        $message = [
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.exists' => 'Email này không tồn tại trong hệ thống.',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Dữ liệu không hợp lệ'
            ], 422);
        }

        try {
            $email = $request->email;
            $account = $this->accountAuthRepository->findByEmail($email)->first();
            if (!$account) {
                return response()->json([
                    'message'  => 'Email không tồn tại trong hệ thống.',
                ], 404);
            }
            $token = hash_hmac('sha256', Str::random(40), config('app.key'));
            $expiresAt = now()->addMinutes(30);
            $this->accountAuthRepository->updateTokenForgotPassword($account->id, [
                'forgot_token' => $token,
                'reset_password_expires_at' => $expiresAt,
            ]);
            $resetUrl = config('app.frontend_url', 'http://192.168.2.2:8081') . '/update-password-auth?email=' . urlencode($email) . '&token=' . $token;
            $data = [
                'body_send_mail' => $resetUrl,
                'user_name' => $account->name ?? 'Người dùng'
            ];
            $title = 'Yêu cầu đặt lại mật khẩu - ' . now('Asia/Ho_Chi_Minh')->format('d-m-Y');
            Mail::to($email)->queue(new ResetPasswordMail($data, $title));
            return response()->json([
                'message' => 'Vui lòng kiểm tra hòm thư email để đặt lại mật khẩu!',
            ], 200);
        } catch (Exception $e) {
            Log::error('Lỗi khi gửi email đặt lại mật khẩu: ' . $e->getMessage());
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi gửi email. Vui lòng thử lại sau.',
            ], 500);
        }
    }

    public function updateNewPasswordAccountAuth(Request $request)
    {
        try {
            $account = $this->accountAuthRepository->findByEmailAndToken($request->email, $request->token)->first();
            if (!$account) {
                return response()->json([
                    'message' => 'Link đã hết hạn'
                ], 404);
            }
            if ($account->reset_password_expires_at < now()) {
                return response()->json([
                    'message' => 'Token đã hết hạn. Vui lòng yêu cầu đặt lại mật khẩu mới.'
                ], 400);
            }
            $this->accountAuthRepository->updateNewPassword($account->id, [
                'password' => bcrypt($request->password),
                'forgot_token' => null,
                'reset_password_expires_at' => null
            ]);

            return response()->json([
                'message' => 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập.',
            ], 200);
        } catch (Exception $e) {
            Log::error('Lỗi khi đặt lại mật khẩu: ' . $e->getMessage());
            return response()->json([
                'message' => 'Có lỗi xảy ra'
            ], 500);
        }
    }

    public function checkAuth(Request $request)
    {
       
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json([
                'success' => true,
                'valid' => true,
                'user' => $user
            ], 200);
        } catch (TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'valid' => false,
                'message' => 'Token đã hết hạn',
                'error_code' => 'TOKEN_EXPIRED'
            ], 401);
        }catch(TokenInvalidException $e){
            return response()->json([
                'success' => false,
                'message' => 'Token không hợp lệ. Vui lòng đăng nhập',
                'error_code' => 'TOKEN_INVALID'
            ], 401);
        }
    }
}
