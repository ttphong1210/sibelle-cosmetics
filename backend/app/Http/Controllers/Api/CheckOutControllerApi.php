<?php

namespace App\Http\Controllers\Api;

use App\Jobs\SendNewOrderNotificationEmail;
use App\Repositories\Eloquent\FeeShipRepository;
use App\Services\EmailService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckOutRequest;
use App\Jobs\SendOrderConfirmationEmail;
use App\Models\City;
use App\Models\District;
use App\Models\Wards;
use App\Repositories\Eloquent\CartRepository;
use App\Repositories\Eloquent\CustomerRepository;
use App\Repositories\Eloquent\OrderDetailRepository;
use App\Repositories\Eloquent\OrderRepository;
use App\Services\InventoryService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CheckOutControllerApi extends Controller
{
    //
    protected $cartRepository;
    protected $feeShipRepository;
    protected $orderDetailRepository;
    protected $customerRepository;
    protected $orderRepository;
    protected $emailService;
    protected $inventoryService;
    public function __construct(CartRepository $cartRepository, FeeShipRepository $feeShipRepository, OrderDetailRepository $orderDetailRepository, CustomerRepository $customerRepository, OrderRepository $orderRepository, EmailService $emailService, InventoryService $inventoryService)
    {
        $this->cartRepository = $cartRepository;
        $this->feeShipRepository = $feeShipRepository;
        $this->orderDetailRepository = $orderDetailRepository;
        $this->customerRepository = $customerRepository;
        $this->orderRepository = $orderRepository;
        $this->emailService = $emailService;
        $this->inventoryService = $inventoryService;
    }
    public function handlePreflight(Request $request)
    {
        return response()->json('OK', 200);
    }

    public function getCityApi()
    {
        $city = City::orderBy('matp', 'ASC')->get();

        return response()->json([
            'city' => $city
        ]);
    }
    public function postSelectShippingInformationApi(Request $request)
    {
        $data = $request->all();
        if (!isset($data['action']) || !isset($data['ma_id'])) {
            return response()->json(["message" => "Invalid Request"], 400);
        }

        if ($data['action']) {
            if ($data['action'] == "city") {
                $select_district = District::where('matp', $data['ma_id'])->orderBy('maqh', 'ASC')->get();
                return response()->json([
                    "districts" => $select_district
                ]);
            } elseif ($data['action'] == "district") {
                $select_ward = Wards::where('maqh', $data['ma_id'])->orderBy('xaid', 'ASC')->get();
                return response()->json([
                    'wards' => $select_ward
                ]);
            }
        }
        return response()->json([
            "message" => 'Invalid Resquest'
        ], 400);
    }

    public function calculateShipping(Request $request)
    {
        $data = $request->all();
        if ($data['matp']) {
            $feeship = $this->feeShipRepository->findFeeShip($data['matp'], $data['maqh'], $data['xaid']);
            $feeAmount = $feeship ? $feeship->fee_feeship : 25000; // Giá mặc định nếu không tìm thấy
            Session::put('feeship', $feeAmount);
            Session::save();
        }

        $feeAmount = Session::get('feeship');
        $data['feeship'] = number_format($feeAmount, 0, ',', '.');
        return response()->json([
            'feeship' => $feeAmount,
        ]);
    }

    public function actionPostCheckOut(CheckOutRequest $request)
    {
        DB::beginTransaction();

        $validated = $request->validated();
        $cartInfo = $request->input('cart', []);
        $data['total_after_feeship'] = $request->input('totalAfterFeeship', 0);
        $this->validateCheckOutData($cartInfo, $data['total_after_feeship']);
        $inputAddress = $request->only(['city', 'district', 'ward', 'address']);
        $fullAddress = $this->buildFullAddress($inputAddress);

        try {
            $customer = $this->createCustomer($validated, $fullAddress);
            $order_code = $this->generateUniqueOrderCode();
            $orderData = [
                'customer_id' => $customer->cust_id,
                'date_order' => Carbon::now('Asia/Ho_Chi_Minh'),
                'total' => $data['total_after_feeship'],
                'notes' => $validated['notes'],
                'order_status' => 1,
                'order_code' => $order_code,
                'order_payment' => $validated['payments'],
            ];
            $order = $this->orderRepository->create($orderData);
            foreach ($cartInfo as $item) {
                $orderDetailData = [
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantily' => $item['quantity'],
                    'price' => $item['price'],
                    'order_code' => $order_code,
                    'order_payment' => $order->order_payment,
                ];
                $this->orderDetailRepository->create($orderDetailData);
            }
            $this->inventoryService->processSuccessFully($cartInfo);
            DB::commit();
            $this->scheduleOrderEmail($validated, $cartInfo, $fullAddress, $order);
            return response()->json([
                'status' => 'success',
                'message' => 'Đơn hàng của bạn đã được tạo thành công!',
                'data' => [
                    'order_code' => $order->order_code,
                    'order_id' => $order->id,
                    'total' => $order->total,
                    'estimated_delivery' => $this->getEstimatedDelivery(),
                ],
            ]);
        } catch (Exception $error) {
            DB::rollBack();
            Log::error('Lỗi đặt hàng : ' . $error->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi xử lý đơn hàng.',
                'error' => $error
            ]);
        }
    }

    public function checkInventory(Request $request)
    {
        try {
            $validated = $request->validate([
                'items' => 'required|array',
                'items.*.product_id' => 'required|exists:products,prod_id',
                'items.*.quantity' => 'required|integer|min:1',
            ]);
            $results = [];
            foreach ($validated['items'] as $item) {
                $result = $this->inventoryService->checkStock($item['product_id'], $item['quantity']);
                $results[] = [
                    'product_id' => $item['product_id'],
                    'requested_quantity' => $item['quantity'],
                    'available' => $result['available'],
                    'message' => $result['message'] ?? null,
                    'available_quantity' => isset($result['inventory']) ? $result['inventory']->getAvailableQuantity() : 0
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $results
            ]);
        } catch (Exception $e) {
            Log::error('Lỗi check inventory: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function reservedStock(Request $request)
    {
        try {
            $data = $request->input('productID');
            $product_id = $data['product_id'];
            $quantity = $data['quantity'];
            $result = $this->inventoryService->reservedStock($product_id, $quantity);
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message']
            ]);
        } catch (Exception $e) {
            Log::error('Lỗi thêm hàng đặt trước:' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function cancelOrder(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,prod_id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
        try {
            $result = $this->inventoryService->cancelOrder($validated['items']);
            return response()->json([
                'success' => $result['success']
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteReservedStock(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,prod_id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            foreach ($validated['items'] as $item) {
                $result = $this->inventoryService->deleteReservedStock($item['product_id'], $item['quantity']);
            }
            return response()->json([
                'success' => $result['success'],
            ]);
        } catch (Exception $e) {
            Log::error('Lỗi xoá đặt trước: ' . $e->getMessage());
            return response()->json([
                'success' => false
            ]);
        }
    }
    /**
     * Validate data cart & total after feeship
     */
    private function validateCheckOutData($cartInfo, $totalAfterFeeShip)
    {
        if (empty($cartInfo)) {
            throw new \Exception('Giỏ hàng trống.Vui lòng thêm sản phẩm trước khi thanh toán.');
        }
        if (count($cartInfo) >= 30) {
            throw new \Exception('Giỏ hàng chứa quá nhiều sản phẩm. Tối đa 50 sản phẩm.');
        }
        if ($totalAfterFeeShip < 0) {
            throw new \Exception('Tổng tiền không hợp lệ.');
        }
        if ($totalAfterFeeShip > 100000000) {
            throw new \Exception('Tổng tiền vượt mức cho phép');
        }
    }
    /**
     * Build full address & validate
     */
    private function buildFullAddress($addressData)
    {
        $location = [
            'city' => City::where('matp', $addressData['city'])->pluck('name_city')->first(),
            'district' => District::where('maqh', $addressData['district'])->pluck('name_district')->first(),
            'ward' => Wards::where('xaid', $addressData['ward'])->pluck('name_ward')->first()
        ];
        if (!$location['city'] || !$location['district'] || !$location['ward']) {
            throw new \Exception('Thông tin địa chỉ không hợp lệ. Vui lòng chọn lại.');
        }
        if (empty(trim($addressData['address']))) {
            throw new \Exception('Vui lòng nhập địa chỉ cụ thể');
        }
        return implode(',', array_filter([
            trim($addressData['address']),
            $location['ward'],
            $location['district'],
            $location['city'],
        ]));
    }

    /**
     * Find or create customer by email or number phone
     *
     * @param array $validated Array contains infomation customer (name, number_phone, email).
     * @param string $fullAddress Full Address of customer.
     * @return \App\Models\Customer Đối tượng khách hàng.
     * @throws \InvalidArgumentException Nếu thông tin không hợp lệ.
     * @throws \Exception Nếu không thể tạo khách hàng.
     */
    private function createCustomer($validated, $fullAddress)
    {
        $customer = $this->customerRepository->findByPhoneOrEmail($validated['email'], $validated['number_phone']);
        if (!$customer) {
            $customerData = [
                'cust_name' => $validated['name'],
                'cust_phone' => $validated['number_phone'],
                'cust_email' => $validated['email'],
                'address' => $fullAddress,
                'created_at' => now()
            ];
            try {
                $customer = $this->customerRepository->create($customerData);
            } catch (Exception $e) {
                throw new \Exception('Không thể tạo khách hàng, vui lòng thử lại.');
            }
        }
        return $customer;
    }
    /**
     * Generate unique order code
     * @return string Mã đơn hàng duy nhất.
     * @throws \Exception Nếu không thể tạo mã duy nhất sau số lần thử tối đa.
     */
    private function generateUniqueOrderCode()
    {
        $maxAttempts = 10;
        $attempts = 0;

        do {
            $code = 'ORD' . date('Ymd') . str_pad(rand(1, 999999), 4, '0', STR_PAD_LEFT);
            try {
                $exists = $this->orderRepository->findByCode($code);
            } catch (Exception $e) {
                Log::error('Error checking order code: ' . $e->getMessage());
                throw new \Exception('Lỗi khi kiểm tra mã đơn hàng.');
            }
            $attempts++;

            if ($attempts >= $maxAttempts) {
                usleep(100000);
                $attempts = 0;
                Log::warning('Failed to generate unique order code after ' . $maxAttempts . ' attempts.');
                throw new \Exception('Không thể tạo mã đơn hàng. Vui lòng thử lại.');
            }
        } while ($exists);

        return $code;
    }

    /**
     * Schedule email sending (sử dụng queue)
     */
    private function scheduleOrderEmail($validated, $cartInfo, $fullAddress, $order)
    {

        $data = [
            'order' => $order,
            'cart' => $cartInfo,
            'fullAddress' => $fullAddress,
            'customer' => $validated
        ];
        dispatch(new SendOrderConfirmationEmail($data));
        dispatch(new SendNewOrderNotificationEmail($data));
    }

    /**
     * Tính thời gian giao hàng dự kiến
     */
    private function getEstimatedDelivery()
    {
        return Carbon::now()->addDays(3)->format('d/m/Y');
    }

}
