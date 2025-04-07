<?php
header('Content-Type: application/json');

// Define the uploads directory; __DIR__ points to the current directory
$uploadDir = __DIR__ . '/../uploads/';

if (!isset($_FILES['file'])) {
    echo json_encode(['error' => 'No file uploaded.']);
    exit;
}

$file = $_FILES['file'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['error' => 'Upload error code: ' . $file['error']]);
    exit;
}

// Optionally validate file type and size
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($file['type'], $allowedTypes)) {
    echo json_encode(['error' => 'Invalid file type.']);
    exit;
}

// Create a unique name for the file
$uniqueName = time() . '_' . basename($file['name']);
$targetFilePath = $uploadDir . $uniqueName;

if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
    // Build a public URL for the file.
    // Adjust the base URL as needed for your environment.
    $publicUrl = 'http://localhost/projet-annonce/public/uploads/' . $uniqueName;
    echo json_encode([
        'filePath' => $targetFilePath,
        'fileUrl' => $publicUrl,
        'fileType' => $file['type']
    ]);
    exit;
} else {
    echo json_encode(['error' => 'Failed to move uploaded file.']);
    exit;
}
