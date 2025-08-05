<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Repositories\EloquentRepository;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Support\Facades\DB;
use OrderDetails;

class OrderRepository extends EloquentRepository implements OrderRepositoryInterface
{

    /**
     * Get the model class.
     * 
     * @return string
     */
    public function getModel()
    {
        return Order::class();
    }
    /**
     * OrderRepository constructor.
     * 
     * @param Order $_model
     */
    public function __construct(Order $_model)
    {
        $this->_model = $_model;
    }

    public function create(array $data)
    {
        return $this->_model::create($data);
    }
    public function show()
    {
        return $this->_model::query()
            ->join('customers', 'customers.cust_id', '=', 'orders.customer_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->leftJoin('products', 'order_details.product_id', '=', 'products.prod_id')
            ->select([
                'orders.id',
                'orders.order_code',
                'orders.date_order',
                'orders.total',
                'orders.notes',
                'orders.order_status',
                DB::raw('GROUP_CONCAT(products.prod_name) as product_names'),
                DB::raw('SUM(order_details.quantily) as total_quantity_products'),
                'customers.cust_id',
                'customers.cust_name',
                'customers.cust_email',
                'customers.cust_phone',
                'customers.address'
            ])
            ->groupBy('orders.id');
    }
    public function showProductWithOrderCode(int $id)
    {
        $order = $this->_model::query()
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->leftJoin('products', 'products.prod_id', '=', 'order_details.product_id')
            ->select([
                'products.prod_id',
                'products.prod_name',
                'products.prod_price',
                'order_details.quantily',
            ])->where('orders.id', $id)
            ->get();
        return $order;
    }
    public function orderTotal(int $id)
    {
        return $this->_model::where('id', $id)->value('total');
    }

    /**
     * Tìm đơn hàng theo mã đơn hàng.
     *
     * @param string $code Mã đơn hàng.
     * @return \App\Models\Order|null Đối tượng đơn hàng hoặc null nếu không tìm thấy.
     */
    public function findByCode($code)
    {
        if(empty($code)){
            return null;
        }
        return $this->_model->where('order_code', $code)->first();
    }
}
