<?php

namespace App\Services;

class StorageService {
    private $basePath;

    public function __construct() {
        $this->basePath = __DIR__ . '/../../' . ($_ENV['STORAGE_PATH'] ?? 'public/vado');
        if (!is_dir($this->basePath)) {
            mkdir($this->basePath, 0755, true);
        }
    }

    public function ensureDirectory($vadoId, $format) {
        $path = "{$this->basePath}/{$vadoId}/{$format}";
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        return $path;
    }

    public function getBaseStoragePath() {
        return $this->basePath;
    }

    public function getRelativePath($vadoId, $format, $filename) {
        return "/vado/{$vadoId}/{$format}/{$filename}";
    }
}
