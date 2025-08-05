<?php

namespace App\Http\Controllers\Api\Admin;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddProductRequest;
use App\Http\Requests\EditProductRequest;
use App\Repositories\Admin\Eloquent\CategoryRepository;
use App\Repositories\Admin\Eloquent\ProductRepository;
use App\Services\InventoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ManagerProductControllerApi extends Controller
{
    //
    protected $productRepository, $categoryRepository, $inventoryService;
    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository, InventoryService $inventoryService)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->inventoryService = $inventoryService;
    }
    public function listManagerProduct()
    {
        $listProduct = $this->productRepository->getAll();
        $listCategory = $this->categoryRepository->showAll();
        return response()->json([
            'listProduct' => $listProduct,
            'listCategory' => $listCategory,
        ]);
    }
    public function ManagerAddProduct(AddProductRequest $request)
    {
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $data = [
                'prod_name' => $validated['name'],
                'prod_slug' => Str::slug($validated['name']),
                'prod_price' => $validated['price'],
                'prod_status' => $validated['status'],
                'prod_summary' => $validated['summary'],
                'prod_des' => $validated['description'],
                'prod_promotion' => $validated['promotion'],
                'prod_cate' => $validated['category'],
                'prod_brand' => $validated['brand'],
                'prod_featured' => $validated['featured'],
            ];
            $quantity = $validated['quantity'];
            $storedFiles = [];
            if ($request->hasFile('imageAvatar')) {
                $image = $request->file('imageAvatar');
                $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/avatar', $imageName);
                $data['prod_img'] = $imageName;
                $storedFiles[] = 'public/avatar/' . $imageName;
            }

            if ($request->hasFile('gallery')) {
                $galleryFile = $request->file('gallery');
                $galleryImagePath = [];
                if (count($galleryFile) > 5) {
                    throw new \Exception(' Upload tối đa 5 ảnh ');
                }
                foreach ($galleryFile as $index => $image) {
                    if ($image->isValid()) {
                        $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                        $image->storeAs('public/gallery', $imageName);
                        $galleryImagePath[] = $imageName;
                        $storedFiles[] = 'public/gallery/' . $imageName;
                    }
                }
                $data['prod_gallery'] = implode('|', $galleryImagePath);
            }
            $product = $this->productRepository->create($data);
            if ($quantity > 0) {
                $this->inventoryService->addStock($product->prod_id, $quantity, 'initial_stock', 'Nhập kho ban đầu');
            }
            DB::commit();
            return response()->json([
                'message' => 'Thêm sản phẩm thành công !',
            ], 200);
        } catch (ValidationException $e) {
            DB::rollBack();
            foreach ($storedFiles as $file) {
                if (Storage::exists($file)) {
                    Storage::delete($file);
                }
            }
            return response()->json([
                'message' => 'Lỗi khi thêm sản phẩm',
                'error' => $e->getMessage()
            ], 422);
        } catch (Exception $e) {
            DB::rollBack();
            foreach ($storedFiles as $file) {
                if (Storage::exists($file)) {
                    Storage::delete($file);
                }
            }

            return response()->json([
                'message' => 'Lỗi khi thêm dữ liệu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function actionSearchProduct(Request $request)
    {
        $inputQuery = $request->query('inputQuery');
        if (empty($inputQuery)) {
            $productWithSearch = $this->productRepository->getAll();
        } else {
            $productWithSearch = $this->productRepository->findByStr($inputQuery);
        }
        $result = $productWithSearch->paginate();
        return response()->json([
            'productList' => $result
        ]);
    }
    public function actionEditProduct(EditProductRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $product = $this->productRepository->findById($id);
            $validated = $request->validated();
            $data = [
                'prod_name' => $validated['prod_name'],
                'prod_slug' => Str::slug($validated['prod_name']),
                'prod_price' => $validated['prod_price'],
                'prod_status' => $validated['prod_status'],
                'prod_summary' => $validated['prod_summary'],
                'prod_des' => $validated['prod_des'],
                'prod_promotion' => $validated['prod_promotion'],
                'prod_cate' => $validated['prod_cate'],
                'prod_brand' => $validated['prod_brand'],
                'prod_featured' => $validated['prod_featured'],
            ];
            $deleteFiles = [];
            if ($request->hasFile('imageAvatar')) {
                if ($product->prod_img && Storage::exists('public/avatar/' . $product->prod_img)) {
                    $deleteFiles[] = 'product/avatar/' . $product->prod_img;
                    Storage::delete('public/avatar/' . $product->prod_img);
                }
                $image = $request->file('imageAvatar');
                $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/avatar', $imageName);
                $data['prod_img'] = $imageName;
            }

            $galleryFromDB = $product->prod_gallery ? explode("|", $product->prod_gallery) : [];
            $currentGallery = $request->currentGallery ? explode("|", $request->currentGallery) : [];
            $imageDelete = array_diff($galleryFromDB, $currentGallery);
            foreach ($imageDelete as $img) {
                if ($img && Storage::exists('public/gallery/' . $img)) {
                    $deleteFiles[] = 'public/gallery/' . $img;
                    Storage::delete('public/gallery/' . $img);
                }
            }

            $newGallery = [];
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $index => $image) {
                    if ($image->isValid()) {
                        $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                        $image->storeAs('public/gallery', $imageName);
                        $newGallery[] = $imageName;
                    }
                }
                $finalGallery = array_merge($currentGallery, $newGallery);
            } else {
                $finalGallery = $currentGallery;
            }
            $data['prod_gallery'] = implode("|", $finalGallery);
            $newQuantity = $validated['inventory_quantity'];
            $currentInventoryQuantity = $product->inventory_quantity ?? 0;
            if ($currentInventoryQuantity != $newQuantity) {
                $this->inventoryService->increaseStock($id, $newQuantity);
            }
            $this->productRepository->update($id, $data);

            DB::commit();
            return response()->json([
                'message' => 'Cập nhật thay đổi thành công !',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            foreach ($deleteFiles as $file) {
                Storage::put($file, Storage::get($file));
            }
            Log::error('Lỗi khi cập nhật sản phẩm: ' . $e->getMessage());
            return response()->json([
                'message' => 'Lỗi khi cập nhật sản phẩm',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function actionDeleteProduct($id)
    {
        $this->productRepository->delete($id);
        return response()->json([
            'message' => 'Delete Product Success'
        ]);
    }
}
