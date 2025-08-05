<?php
namespace App\Repositories\Interfaces;

interface CustomerRepositoryInterface{
    public function create(array $data);
    public function findByPhoneOrEmail($email, $phone);
}