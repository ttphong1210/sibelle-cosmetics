<?php
namespace App\Repositories\Interfaces;

interface OrderRepositoryInterface{
    public function create(array $data);
    public function show();
    public function showProductWithOrderCode(int $id);
    public function orderTotal(int $id);
    public function findByCode($data);
}