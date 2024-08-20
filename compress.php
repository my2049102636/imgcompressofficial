<?php
// Check if a file has been uploaded
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    // Upload directory
    $uploadDir = 'uploads/';
    // Ensure the upload directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Get file info
    $fileName = $_FILES['image']['name'];
    $fileTmpName = $_FILES['image']['tmp_name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Allowed image types
    $allowedTypes = ['jpg', 'jpeg', 'png'];

    // Check if the file type is allowed
    if (!in_array($fileExtension, $allowedTypes)) {
        die("Unsupported file type. Please upload a JPG, JPEG, or PNG image.");
    }

    // Move the uploaded file to the upload directory
    $filePath = $uploadDir . $fileName;
    if (!move_uploaded_file($fileTmpName, $filePath)) {
        die("Failed to move uploaded file.");
    }

    // Load the image based on its type
    switch ($fileExtension) {
        case 'jpg':
        case 'jpeg':
            $image = imagecreatefromjpeg($filePath);
            break;
        case 'png':
            $image = imagecreatefrompng($filePath);
            break;
    }

    // Get original image size
    list($width, $height) = getimagesize($filePath);
    $originalSize = filesize($filePath);

    // Create a new image with the same dimensions
    $newImage = imagecreatetruecolor($width, $height);
    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $width, $height);

    // Initialize variables for compression
    $targetSize = $originalSize / 2;
    $quality = 75; // Start with a reasonable quality for JPEGs
    $compressionLevel = 6; // Default for PNGs

    // Set compressed image path
    $compressedFilePath = $uploadDir . 'compressed_' . $fileName;

    // Function to adjust quality and check file size
    function adjustQuality($image, $filePath, $fileExtension, &$quality) {
        global $targetSize;
        do {
            ob_start();
            if ($fileExtension == 'jpg' || $fileExtension == 'jpeg') {
                imagejpeg($image, null, $quality);
            } else {
                imagepng($image, null, $compressionLevel);
            }
            $data = ob_get_contents();
            ob_end_clean();
            $currentSize = strlen($data);
            if ($currentSize > $targetSize) {
                $quality -= 5; // Decrease quality for JPEGs
                if ($quality < 0) $quality = 0;
                if ($fileExtension == 'png') {
                    $compressionLevel += 1; // Increase compression for PNGs
                }
            } else {
                file_put_contents($filePath, $data);
                return;
            }
        } while ($currentSize > $targetSize);
    }

    // Adjust compression and save
    switch ($fileExtension) {
        case 'jpg':
        case 'jpeg':
            adjustQuality($newImage, $compressedFilePath, $fileExtension, $quality);
            break;
        case 'png':
            adjustQuality($newImage, $compressedFilePath, $fileExtension, $quality);
            break;
    }

    // Free up memory
    imagedestroy($image);
    imagedestroy($newImage);

    // Get new image size
    $compressedSize = filesize($compressedFilePath);

    // Output the result
    echo "<!DOCTYPE html>";
    echo "<html lang='en'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>Image Compression Result</title>";
    echo "<style>";
    echo "body {";
    echo "    font-family: Arial, sans-serif;";
    echo "    background: #f0f4f8;";
    echo "    display: flex;";
    echo "    justify-content: center;";
    echo "    align-items: center;";
    echo "    height: 100vh;";
    echo "    margin: 0;";
    echo "    overflow: hidden;";
    echo "}";
    echo ".result-container {";
    echo "    background: #ffffff;";
    echo "    padding: 2rem;";
    echo "    border-radius: 8px;";
    echo "    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);";
    echo "    max-width: 500px;";
    echo "    width: 100%;";
    echo "    text-align: center;";
    echo "    animation: fadeIn 1s ease-in;";
    echo "}";
    echo ".result-container p {";
    echo "    color: #333;";
    echo "    margin-bottom: 1rem;";
    echo "    animation: slideIn 1s ease-in;";
    echo "}";
    echo ".result-container a {";
    echo "    display: inline-block;";
    echo "    margin-top: 1rem;";
    echo "    color: #007bff;";
    echo "    text-decoration: none;";
    echo "    font-weight: bold;";
    echo "    border: 1px solid #007bff;";
    echo "    border-radius: 4px;";
    echo "    padding: 0.5rem 1rem;";
    echo "    transition: background 0.3s ease, color 0.3s ease;";
    echo "}";
    echo ".result-container a:hover {";
    echo "    background: #007bff;";
    echo "    color: #fff;";
    echo "}";
    echo "@keyframes fadeIn {";
    echo "    from { opacity: 0; }";
    echo "    to { opacity: 1; }";
    echo "}";
    echo "@keyframes slideIn {";
    echo "    from { transform: translateY(20px); opacity: 0; }";
    echo "    to { transform: translateY(0); opacity: 1; }";
    echo "}";
    echo "</style>";
    echo "</head>";
    echo "<body>";
    echo "<div class='result-container'>";
    echo "<p>Image uploaded and compressed successfully.</p>";
    echo "<p>Original size: " . round($originalSize / 1024, 2) . " KB</p>";
    echo "<p>Compressed size: " . round($compressedSize / 1024, 2) . " KB</p>";
    echo "<a href='$compressedFilePath' download>Download Compressed Image</a>";
    echo "</div>";
    echo "</body>";
    echo "</html>";
} else {
    echo "<!DOCTYPE html>";
    echo "<html lang='en'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>Image Upload Error</title>";
    echo "<style>";
    echo "body {";
    echo "    font-family: Arial, sans-serif;";
    echo "    background: #f0f4f8;";
    echo "    display: flex;";
    echo "    justify-content: center;";
    echo "    align-items: center;";
    echo "    height: 100vh;";
    echo "    margin: 0;";
    echo "    overflow: hidden;";
    echo "}";
    echo ".error-container {";
    echo "    background: #ffffff;";
    echo "    padding: 2rem;";
    echo "    border-radius: 8px;";
    echo "    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);";
    echo "    max-width: 500px;";
    echo "    width: 100%;";
    echo "    text-align: center;";
    echo "    animation: fadeIn 1s ease-in;";
    echo "}";
    echo ".error-container p {";
    echo "    color: #ff0000;";
    echo "    margin-bottom: 1rem;";
    echo "}";
    echo "</style>";
    echo "</head>";
    echo "<body>";
    echo "<div class='error-container'>";
    echo "<p>No file uploaded or there was an error uploading the file.</p>";
    echo "</div>";
    echo "</body>";
    echo "</html>";
}
?>
