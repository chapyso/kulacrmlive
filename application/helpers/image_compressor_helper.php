<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Auto-compresses and resizes uploaded image files to prevent memory/disk bloat
 */
function compress_uploaded_image(string $filePath, int $maxWidth = 1600, int $maxHeight = 1600, int $quality = 85): bool
{
    if (!file_exists($filePath) || !is_file($filePath)) {
        return false;
    }

    $info = @getimagesize($filePath);
    if (!$info) {
        return false;
    }

    $mime = $info['mime'] ?? '';
    $width = $info[0] ?? 0;
    $height = $info[1] ?? 0;

    if ($width <= 0 || $height <= 0) {
        return false;
    }

    // Skip SVG and ICO
    if (in_array($mime, ['image/svg+xml', 'image/x-icon', 'image/vnd.microsoft.icon'])) {
        return true;
    }

    // Calculate proportional dimensions
    $newWidth = $width;
    $newHeight = $height;

    if ($width > $maxWidth || $height > $maxHeight) {
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = (int)round($width * $ratio);
        $newHeight = (int)round($height * $ratio);
    }

    // Load source image
    $srcImg = null;
    switch ($mime) {
        case 'image/jpeg':
            $srcImg = @imagecreatefromjpeg($filePath);
            break;
        case 'image/png':
            $srcImg = @imagecreatefrompng($filePath);
            break;
        case 'image/webp':
            if (function_exists('imagecreatefromwebp')) {
                $srcImg = @imagecreatefromwebp($filePath);
            }
            break;
        case 'image/gif':
            $srcImg = @imagecreatefromgif($filePath);
            break;
    }

    if (!$srcImg) {
        return false;
    }

    // Create canvas
    $dstImg = imagecreatetruecolor($newWidth, $newHeight);

    // Preserve alpha transparency
    if ($mime === 'image/png' || $mime === 'image/webp' || $mime === 'image/gif') {
        imagealphablending($dstImg, false);
        imagesavealpha($dstImg, true);
        $transparent = imagecolorallocatealpha($dstImg, 255, 255, 255, 127);
        imagefilledrectangle($dstImg, 0, 0, $newWidth, $newHeight, $transparent);
    }

    imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    // Overwrite with compressed image
    switch ($mime) {
        case 'image/jpeg':
            imagejpeg($dstImg, $filePath, $quality);
            break;
        case 'image/png':
            imagepng($dstImg, $filePath, 6);
            break;
        case 'image/webp':
            if (function_exists('imagewebp')) {
                imagewebp($dstImg, $filePath, $quality);
            }
            break;
        case 'image/gif':
            imagegif($dstImg, $filePath);
            break;
    }

    imagedestroy($srcImg);
    imagedestroy($dstImg);

    return true;
}
