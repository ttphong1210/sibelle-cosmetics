<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';
    protected $fillable = ['product_id', 'inventory_quantity', 'reserved_quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'prod_id');
    }

    /**
     * Lấy số lượng có thể bán 
     */
    public function getAvailableQuantity()
    {
        return $this->inventory_quantity - $this->reserved_quantity;
    }

    /**
     * Kiểm tra hàng còn trong kho không
     */
    public function hasStock($requestedQuantity)
    {
        return $this->getAvailableQuantity() >= $requestedQuantity;
    }

    /**
     * Đặt trước số lượng (khi thêm vào giỏ hàng)
     */
    public function reserveStock($quantity)
    {
        if (!$this->hasStock($quantity)) {
            throw new \Exception('Không đủ số lượng hàng trong kho');
        }
        $this->increment('reserved_quantity', $quantity);
        return $this;
    }

    /**
     * Cancel reserved stock ( when cancel order )
     */
    public function unreservedStock($quantity)
    {
        $decrementAmount = min($this->reserved_quantity, $quantity);
        $this->decrement('reserved_quantity', $decrementAmount);
        return $this;
    }
    /**
     * Decrement quantity when order successfully
     */
    public function decreaseStock($quantity)
    {
        if ($this->inventory_quantity < $quantity) {
            throw new \Exception('Không đủ số lượng trong kho');
        }
        $this->decrement('inventory_quantity', $quantity);
        $this->decrement('reserved_quantity', min($this->reserved_quantity, $quantity));
        return $this;
    }
    /**
     * Add inventory quantity
     */
    public function increaseStock($quantity)
    {
        $this->increment('inventory_quantity', $quantity);
        return $this;
    }
}
