<?php

namespace App\Repositories\Admin\Eloquent;

use App\Models\Inventory;
use App\Repositories\EloquentRepository;
use Illuminate\Database\Eloquent\Collection;

class InventoryRepository extends EloquentRepository
{
    public function getModel()
    {
        return Inventory::class;
    }
    public function __construct(Inventory $_model)
    {
        $this->_model = $_model;
    }
    public function create(array $attributes)
    {
        return $this->_model->create($attributes);
    }

    /**
     * Lấy inventory theo product ID
     * 
     * @param int $productID
     * @return Inventory|null
     */
    public function findProductById($productID)
    {
        return $this->_model->where('product_id', $productID)->first();
    }
    /**
     * Lấy tất cả inventory có số lượng thấp
     * 
     * @param int $threshold
     * @return Collection
     */
    public function getLowStock($threshold = 10)
    {
        return $this->_model->with('product')->where('inventory_quantity', '<=', $threshold)->get();
    }
}
