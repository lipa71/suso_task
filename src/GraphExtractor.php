<?php

namespace App;

class GraphExtractor {
    public function extract(string $path): array {
        // Simple stub: random metrics
        return [
            'file' => basename($path),
            'max'  => rand(60, 100),
            'min'  => rand(10, 50),
            'avg'  => round((rand(60, 100) + rand(10, 50)) / 2, 2)
        ];
    }
}
