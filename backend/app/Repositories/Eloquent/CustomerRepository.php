<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Repositories\EloquentRepository;
use App\Repositories\EloquentRepositoryInterface;
use App\Repositories\Interfaces\CustomerRepositoryInterface;

class CustomerRepository extends EloquentRepository implements CustomerRepositoryInterface
{
    public function getModel()
    {
        return Customer::class;
    }
    public function __construct(Customer $_model)
    {
        $this->_model = $_model;
    }
    public function create(array $data)
    {
        return $this->_model->create($data);
    }

    public function findByPhoneOrEmail($email, $phone)
    {
        $customer = $this->_model->query();
        if ($email || $phone) {
            $customer->where(function ($query) use ($email, $phone) {
                if($email && $phone){
                    $query->where('cust_email', $email)->orWhere('cust_phone', $phone);
                }elseif($email){
                    $query->where('cust_email', $email);
                }elseif($phone){
                    $query->where('cust_phone', $phone);
                }
            });
        }
        return $customer->first();
    }
}
