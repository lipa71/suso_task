<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/ImageProcessor.php';
require __DIR__ . '/../src/GraphExtractor.php';
require __DIR__ . '/../src/ReportGenerator.php';

use App\ImageProcessor;
use App\GraphExtractor;
use App\ReportGenerator;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['graphs'])) {
    header('Location: index.php');
    exit;
}

try {
    $processor = new ImageProcessor();
    $paths = $processor->validateAndStore($_FILES['graphs']);

    $extractor = new GraphExtractor();
    $metrics = array_map(fn($p) => $extractor->extract($p), $paths);

    $reporter = new ReportGenerator();
    $html = $reporter->generateWithLLM($metrics);

    header('Location: index.php?report=' . urlencode(base64_encode($html)));
    exit;
} catch (Exception $e) {
    echo "<p style='color:red; text-align: center; margin-top: 50px'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<a style='display: flex; align-items: center; justify-content: center;' href='index.php'>Go back</a>";
}