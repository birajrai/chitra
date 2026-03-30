<?php

namespace App\Services;

use App\Models\Token;

class AuthService {
    private $tokenModel;

    public function __construct() {
        $this->tokenModel = new Token();
    }

    public function authenticate() {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (empty($authHeader) || !str_starts_with($authHeader, 'Bearer ')) {
            return false;
        }

        $tokenStr = substr($authHeader, 7);
        $token = $this->tokenModel->findByToken($tokenStr);

        return $token ?: false;
    }
}
