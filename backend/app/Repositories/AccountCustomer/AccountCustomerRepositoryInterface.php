<?php

namespace App\Repositories\AccountCustomer;

use Illuminate\Support\Collection;

interface AccountCustomerRepositoryInterface {

    public function postRegisterCustomer(array $data);
    public function findByEmail(string $email);
    public function updateForgotToken(int $id, string $token);
    public function findByEmailAndToken(string $email, string $token):Collection;
    public function updatePassword(int $id, string $password, string $token):bool;
}