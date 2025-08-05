<?php
namespace App\Repositories\Admin\Interfaces;

use Illuminate\Support\Collection;

interface AccountAdminRepositoryInterface{
    public function show();
    public function createNewAccount(array $data);
    public function findById(int $id);
    public function findByEmail(string $email);
    public function updateForgotToken(int $id, string $token);
    public function updateTokenForgotPassword(int $id, array $data);

    public function findByEmailAndToken(string $email, string $token):Collection;
    public function updatePassword(int $id, string $token, string $password):bool;

    public function updateNewPassword(int $id, array $data);
    public function updateNewDataAccount(int $id, array $data);

}