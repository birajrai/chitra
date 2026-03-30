<?php

namespace App\Models;

class Upload extends BaseModel {
    protected $table = 'uploads';

    public function create($id, $vado_id, $original_name, $file_size, $formats_json) {
        $sql = "INSERT INTO uploads (id, vado_id, original_name, file_size, formats_json) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id, $vado_id, $original_name, $file_size, $formats_json]);
    }

    public function getByVado($vado_id, $limit = 50) {
        $sql = "SELECT * FROM uploads WHERE vado_id = ? ORDER BY created_at DESC LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$vado_id, $limit]);
        return $stmt->fetchAll();
    }
}
