<?php

namespace App;

class ImageProcessor {
    private string $uploadDir;
    private array $allowed = ['image/jpeg', 'image/png'];

    public function __construct() {
        $this->uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($this->uploadDir)) mkdir($this->uploadDir, 0755, true);
    }

    public function validateAndStore(array $files): array {
        $paths = [];
        foreach ($files['error'] as $i => $err) {
            if ($err !== UPLOAD_ERR_OK) throw new \Exception("Upload error #" . $i);
            $type = $files['type'][$i];
            if (!in_array($type, $this->allowed)) throw new \Exception("Invalid type " . $type);
            $tmp = $files['tmp_name'][$i];
            $name = uniqid() . '_' . basename($files['name'][$i]);
            $dest = $this->uploadDir . $name;
            if (!move_uploaded_file($tmp, $dest)) throw new \Exception('Move failed');
            $paths[] = $dest;
        }
        return $paths;
    }
}