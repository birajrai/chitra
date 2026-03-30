<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageProcessorService {
    private $manager;
    private $maxWidth;

    public function __construct() {
        $this->manager = new ImageManager(new Driver());
        $this->maxWidth = (int)($_ENV['MAX_IMAGE_WIDTH'] ?? 1200);
    }

    public function process($filePath, $targetPath, $format, $quality = null) {
        $image = $this->manager->read($filePath);

        // Resize if it's too large
        if ($image->width() > $this->maxWidth) {
            $image->scale(width: $this->maxWidth);
        }

        switch (strtolower($format)) {
            case 'webp':
                $quality = $quality ?: (int)($_ENV['DEFAULT_WEBP_QUALITY'] ?? 75);
                $encoded = $image->toWebp($quality);
                break;
            case 'avif':
                $quality = $quality ?: (int)($_ENV['DEFAULT_AVIF_QUALITY'] ?? 50);
                $encoded = $image->toAvif($quality);
                break;
            case 'jpg':
            case 'jpeg':
            default:
                $quality = $quality ?: (int)($_ENV['DEFAULT_JPG_QUALITY'] ?? 80);
                $encoded = $image->toJpeg($quality);
                break;
        }

        $encoded->save($targetPath);
        return true;
    }
}
