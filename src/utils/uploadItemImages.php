<?php
function storeFiles($files, $targetDirectory,$itemId ,$allowedTypes = ['jpg', 'jpeg', 'png', 'gif'], $maxSize = 5000000) {
    $uploadedFiles = [];
    $fileCount = count($files['name']);

    for ($i = 0; $i < $fileCount; $i++) {
        $originalFileName = basename($files['name'][$i]);
        $imageFileType = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
        $newFileName = "item{$itemId}-{$i}.png";
        $targetFile = $targetDirectory . $newFileName;

        // Check if image file is an actual image or fake image
        $check = getimagesize($files['tmp_name'][$i]);
        if ($check === false) {
            echo "File " . $files['name'][$i] . " is not an image.";
            continue;
        }

        // Check file size
        if ($files['size'][$i] > $maxSize) {
            echo "Sorry, file " . $files['name'][$i] . " is too large.";
            continue;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, $allowedTypes)) {
            echo "Sorry, only " . implode(", ", $allowedTypes) . " files are allowed.";
            continue;
        }

        // Convert the image to PNG format
        $sourceImage = null;
        switch ($imageFileType) {
            case 'jpg':
            case 'jpeg':
                $sourceImage = imagecreatefromjpeg($files['tmp_name'][$i]);
                break;
            case 'gif':
                $sourceImage = imagecreatefromgif($files['tmp_name'][$i]);
                break;
            case 'png':
                $sourceImage = imagecreatefrompng($files['tmp_name'][$i]);
                break;
            default:
                echo "Unsupported file type: " . $files['name'][$i];
                continue 2; // Skip to the next file
        }

        if ($sourceImage !== null) {
            if (imagepng($sourceImage, $targetFile)) {
                $uploadedFiles[] = $targetFile;
                imagedestroy($sourceImage);
            } else {
                echo "Sorry, there was an error converting your file " . $files['name'][$i] . " to PNG.";
            }
        }
    }

    return $uploadedFiles;
}
?>