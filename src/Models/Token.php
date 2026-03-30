<?php

namespace App\Models;

class Token extends BaseModel {
    protected $table = 'tokens';

    public function findByToken($token) {
        $sql = "SELECT t.*, v.name as vado_name FROM tokens t 
                JOIN vados v ON t.vado_id = v.id 
                WHERE t.token = :token AND t.is_active = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['token' => $token]);
        return $stmt->fetch();
    }

    public function create($vado_id, $token) {
        $sql = "INSERT INTO tokens (vado_id, token) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$vado_id, $token]);
    }
}
