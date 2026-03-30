<?php

namespace App\Models;

class Vado extends BaseModel {
    protected $table = 'vados';

    public function create($data) {
        $sql = "INSERT INTO vados (name, enable_jpg, enable_webp, enable_avif, max_size, quota_mb) 
                VALUES (:name, :enable_jpg, :enable_webp, :enable_avif, :max_size, :quota_mb)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }
}
