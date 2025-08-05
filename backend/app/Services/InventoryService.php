<?php

namespace App\Services;

use App\Models\Inventory;
use App\Repositories\Admin\Eloquent\InventoryRepository;
use Dotenv\Regex\Success;
use Exception;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    protected $inventoryRepository;
    public function __construct(InventoryRepository $inventoryRepository){
        $this->inventoryRepository = $inventoryRepository;
    }
    public function checkStock($productID, $quantity)
    {
        $inventory = $this->inventoryRepository->findProductById($productID);

        if (!$inventory) {
            return [
                'available' => false,
                'message' => 'Không có sản phẩm trong kho'
            ];
        }
        if (!$inventory->hasStock($quantity)) {
            return [
                'available' => false,
                'message' => "Chỉ còn {$inventory->getAvailableQuantity()} trong kho"
            ];
        }
        return [
            'available' => true,
            'inventory' => $inventory
        ];
    }

    /**
     * xử lí khi khách hàng thêm sản phẩm vào giỏ hàng, tăng reserved_quantity
     */
    public function reservedStock($productID, $quantity)
    {
        DB::beginTransaction();
        try {
            $inventory = $this->inventoryRepository->findProductById($productID);
            if (!$inventory || !$inventory->hasStock($quantity)) {
                throw new \Exception('Sản phẩm không đủ hàng');
            }
            $inventory->reserveStock($quantity);
            DB::commit();
            return [
                'success' => true,
                'message' => 'Thêm đặt hàng trước thành công'
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function deleteReservedStock($productID, $quantity)
    {
        DB::beginTransaction();
        try {
            $inventory = $this->inventoryRepository->findProductById($productID);
            if (!$inventory) {
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm trong kho'
                ];
            }
            $inventory->unreservedStock($quantity);

            DB::commit();
            return [
                'success' => true
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    /**
     * Handle successfully order, decrease inventory quantity & reserved quantity 
     */
    public function processSuccessFully(array $items)
    {
        DB::beginTransaction();
        try {
            foreach ($items as $item) {
                $inventory = $this->inventoryRepository->findProductById($item['id']);
                if (!$inventory) {
                    throw new \Exception('Không tìm thấy sản phẩm trong kho');
                }
                // $inventory->decreaseStock($item['inventory_quantity']);
                $inventory->decreaseStock($item['quantity']);
            }
            DB::commit();
            return response()->json([
                'success' => true
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     *  xử lý khách hàng huỷ đơn hàng, trừ reserved_quantity
     */
    public function cancelOrder(array $items)
    {
        DB::beginTransaction();
        try {
            foreach ($items as $item) {
                $inventory = Inventory::where('product_id', $item['product_id'])->first();
                if ($inventory) {
                    $inventory->unreservedStock($item['product_id']);
                }
            }
            DB::commit();
            return [
                'success' => true
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function addStock($productID, $quantity, $type = 'restock', $note = ''){
        DB::beginTransaction();
        try{
            if($quantity <= 0 ){
                return [
                    'success' => false,
                    'message' => 'Số lượng không hợp lệ'
                ];
            }
            $currentInventory = $this->inventoryRepository->find($productID);
            if($currentInventory){
                $currentInventory->increaseStock($quantity);
            }else{
                $data = [
                    'product_id' => $productID,
                    'inventory_quantity' => $quantity,
                    'reserved_quantity' => 0
                ];
                $this->inventoryRepository->create($data);
            }
            DB::commit();
            return [
                'success' => true,
                'message' => 'Đã thêm số lượng sản phẩm vào kho'
            ];
        }catch(Exception $e){
            DB::rollBack();
            return [
                'success' => false,
                'message' => "Có lỗi xảy ra " .$e->getMessage()
            ];
        }   
    }

    public function increaseStock($productID, $quantity){
        DB::beginTransaction();
        try{
            $inventory = $this->inventoryRepository->findProductById($productID);
            if(!$inventory){
                $data = [
                    'product_id' => $productID,
                    'inventory_quantity' => $quantity,
                    'reserved_quantity' => 0
                ];
                $inventory = $this->inventoryRepository->create($data);
            }else{
                $inventory->increaseStock($quantity);
            }
            
            DB::commit();
            return [
                'success' => true,
                'message' => 'Thêm hàng vào kho thành công'
            ];
        }catch(Exception $e){
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Lỗi: '.$e->getMessage()
            ];
        }
    }
}
