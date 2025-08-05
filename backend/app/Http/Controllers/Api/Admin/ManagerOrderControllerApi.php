<?php

namespace App\Http\Controllers\Api\Admin;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\OrderRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ManagerOrderControllerApi extends Controller
{
    protected $orderRepository;
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function listOrder(Request $request)
    {
        try {
            $order = $this->orderRepository->show();
            if ($request->filled('search')) {
                $searchQuery = $request->input('search');
                $order->where(function ($query) use ($searchQuery) {
                    $query->where('orders.order_code', 'like', "%{$searchQuery}%")
                        ->orWhere('customers.cust_name', 'like', "%{$searchQuery}%")
                        ->orWhere('customers.cust_email', 'like', "%{$searchQuery}%");
                });
            }
            if ($request->filled('status') && $request->input('status') != 0) {
                $order->where('orders.order_status', $request->input('status'));
            }

            $result = $order->paginate(10);

            return response()->json([
                'order' => $result,
                'message' => 'Tải danh sách đơn hàng thành công'
            ], 200);
        } catch (Exception $e) {
            Log::error('Lỗi danh sách đơn hàng: ' . $e->getMessage());
            return response()->json([
                'message' => 'Lỗi lấy danh sách đơn hàng',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function actionGetEditOrder($id)
    {
        try {
            $data = $this->orderRepository->showProductWithOrderCode($id);
            $order_total = $this->orderRepository->orderTotal($id);
            return response()->json([
                'data' => $data,
                'total' => $order_total
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Lỗi: ' . $e->getMessage()
            ]);
        }
    }
    public function actionSaveEditOrder(Request $request, $id)
    {
        $data = $request->validate([
            'order_status' => 'required|in:1,2,3'
        ]);
        try {
            DB::beginTransaction();
            $order = $this->orderRepository->find($id);
            if (!$order) {
                return response()->json([
                    'message' => 'Đơn hàng không tồn tại'
                ], 404);
            }
            $this->orderRepository->update($id, $data);
            DB::commit();
            return response()->json([
                'message' => 'Cập nhật trạng thái đơn hàng thành công',
                'data' => $data
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Có lỗi xảy ra',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function actionDeleteOrder($id)
    {
        try {
            $this->authorize('delete-orders');
            $order = $this->orderRepository->find($id);
            DB::beginTransaction();
            $order->orderDetails()->delete();
            $order->delete();
            DB::commit();
            return response()->json([
                'message' => 'Xoá đơn hàng thành công !',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Có lỗi xảy ra ',
                'error' =>  $e->getMessage()
            ], 500);
        }
    }

   
}
