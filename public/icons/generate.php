<?php
/**
 * PWA Icon Generator
 * Run: php public/icons/generate.php
 */

$sizes = [72, 96, 128, 144, 192, 384, 512];
$sourceSvg = __DIR__ . '/icon.svg';

if (!file_exists($sourceSvg)) {
    die("Source SVG not found: $sourceSvg\n");
}

$svgContent = file_get_contents($sourceSvg);

foreach ($sizes as $size) {
    // Create image from SVG
    $image = new Imagick();
    $image->setBackgroundColor(new ImagickPixel('transparent'));
    $image->readImageBlob($svgContent);
    $image->setImageFormat('png');
    $image->resizeImage($size, $size, Imagick::FILTER_LANCZOS, 1);
    
    $outputFile = __DIR__ . "/icon-{$size}x{$size}.png";
    $image->writeImage($outputFile);
    $image->destroy();
    
    echo "Generated: icon-{$size}x{$size}.png\n";
}

echo "\nAll icons generated successfully!\n";
