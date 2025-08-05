<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Roles;
use App\Repositories\Admin\Eloquent\AccountAdminRepository;
use App\User;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ManagerAccountAuthControllerApi extends Controller
{
    protected $accountAdminRepository;
    public function __construct(AccountAdminRepository $accountAdminRepository)
    {
        $this->accountAdminRepository = $accountAdminRepository;
    }
    public function showListAccountAuth(Request $request)
    {
        try {
            $query = User::with('roles')->orderBy('id', 'DESC');
            $users = Cache::remember('user_page_' . $request->get('page', 1), 300, function () use ($query) {
                return $query->paginate(5);
            });
            // $users = $query->paginate(5);
            return response()->json([
                'accounts' => $users,
            ], 200);
        } catch (Exception $e) {
            Log::error('Lỗi khi lấy danh sách account admin page: ' . $e->getMessage());
            return response()->json([
                'message' => 'Có lỗi xảy ra'
            ], 500);
        }
    }

    public function listRoles()
    {
        $roles = Roles::select('id', 'name')->get();
        return response()->json([
            'roles' => $roles
        ], 200);
    }
    public function actionSubmitEditAccountAuth(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $account = $this->accountAdminRepository->findById($id);
            if (!$account) {
                return response()->json([
                    'message' => 'Tài khoản không tồn tại'
                ], 404);
            }
            $data = [
                'number_phone' => $request->number_phone,
                'name' => $request->name,
            ];
            $storedFiles = [];
            if ($request->hasFile('avatar')) {
                if ($account->image && Storage::exists('public/account-avatar/' . $account->image)) {
                    Storage::delete('public/account-avatar/' . $account->image);
                }
                $image = $request->file('avatar');
                $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/account-avatar', $imageName);
                $data['image'] = $imageName;
                $storedFiles[] =  'public/account-avatar/' . $imageName;
            }

            $roleID = $request->roles;
            DB::table('roles_user')->where('user_id', $id)->delete();
            if ($roleID) {
                DB::table('roles_user')->insert([
                    'user_id' => $id,
                    'roles_id' => $roleID
                ]);
            }
            $this->accountAdminRepository->updateNewDataAccount($id, $data);
            DB::commit();
            for ($i = 1; $i <= 10; $i++) {
                Cache::forget('user_page_' . $i);
            }
            return response()->json([
                'message' => 'Cập nhật tài khoản thành công !',
                'data' => $data
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            foreach ($storedFiles as $file) {
                if (Storage::exists($file)) {
                    Storage::delete($file);
                }
            }
            Log::error('Lỗi: ' . $e->getMessage());
            return response()->json([
                'message' => 'Có lỗi xảy ra',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
