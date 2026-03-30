<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\StorageService;
use App\Services\ImageProcessorService;
use App\Models\Upload;
use App\Models\Vado;

class UploadController {
    private $auth;
    private $storage;
    private $processor;
    private $uploadModel;

    public function __construct() {
        $this->auth = new AuthService();
        $this->storage = new StorageService();
        $this->processor = new ImageProcessorService();
        $this->uploadModel = new Upload();
    }

    public function handle() {
        $vado = $this->auth->authenticate();
        
        if (!$vado) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        if (empty($_FILES['file'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'No file uploaded']);
            return;
        }

        $file = $_FILES['file'];
        
        // Validate MIME
        $allowedType = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!in_array($file['type'], $allowedType)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid file type']);
            return;
        }

        // Validate Size
        if ($file['size'] > $vado['max_size']) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'File exceeds max size for this vado']);
            return;
        }

        $uuid = bin2hex(random_bytes(16));
        $formatsSaved = [];
        $vadoId = $vado['vado_id'];

        // Determine which formats to generate
        $formatsToProcess = [];
        if ($vado['enable_jpg']) $formatsToProcess[] = 'jpg';
        if ($vado['enable_webp']) $formatsToProcess[] = 'webp';
        if ($vado['enable_avif']) $formatsToProcess[] = 'avif';

        foreach ($formatsToProcess as $format) {
            $formatDir = $this->storage->ensureDirectory($vadoId, $format);
            $targetPath = "{$formatDir}/{$uuid}.{$format}";
            
            try {
                $this->processor->process($file['tmp_name'], $targetPath, $format);
                $formatsSaved[$format] = $this->storage->getRelativePath($vadoId, $format, "{$uuid}.{$format}");
            } catch (\Exception $e) {
                // Log error or handle
            }
        }

        // Save to DB
        $this->uploadModel->create($uuid, $vadoId, $file['name'], $file['size'], json_encode($formatsSaved));

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'id' => $uuid,
            'urls' => $formatsSaved
        ]);
    }

    public function status() {
        $vado = $this->auth->authenticate();
        
        if (!$vado) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $uploads = $this->uploadModel->getByVado($vado['vado_id']);
        
        header('Content-Type: application/json');
        echo json_encode([
            'vado' => $vado['vado_name'],
            'total_uploads' => count($uploads),
            'recent_uploads' => $uploads
        ]);
    }
}
