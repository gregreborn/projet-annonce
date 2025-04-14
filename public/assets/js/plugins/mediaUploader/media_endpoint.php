<?php
header('Content-Type: application/json');

// Compute the absolute path to the public directory
$publicDir = realpath(__DIR__ . '/../../../..');

// Check if realpath() succeeded
if ($publicDir === false) {
    die('Error: Could not resolve public directory.');
}

// Append 'uploads' and ensure proper directory separators (helpful on Windows)
$uploadsDir = $publicDir . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;

// Optionally, define your temp base directory within uploads
$tempBaseDir = $uploadsDir . 'temp' . DIRECTORY_SEPARATOR;

// --- Deletion Branch ---
if (isset($_POST['delete']) && ($_POST['delete'] === 'true' || $_POST['delete'] === true)) {
    if (!isset($_POST['fileId'])) {
        echo json_encode(['error' => 'Missing fileId for deletion.']);
        exit;
    }
    $fileId = $_POST['fileId'];
    $tempDir = $tempBaseDir . $fileId . '/';
    error_log("Deleting temporary folder for fileId: $fileId in directory: $tempDir");
    
    if (is_dir($tempDir)) {
        // Delete all files in the temp directory
        $files = glob($tempDir . '*');
        if ($files !== false) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    unlink($file);
                    error_log("Deleted file: " . $file);
                }
            }
        }
        // Remove the directory
        if (rmdir($tempDir)) {
            error_log("Successfully removed directory: $tempDir");
        } else {
            error_log("Failed to remove directory: $tempDir");
        }
    } else {
        error_log("Temp directory not found for fileId: $fileId");
    }

    // Optionally, remove any assembled final files as well
    $finalFiles = glob($uploadsDir . $fileId . '_*');
    if ($finalFiles !== false) {
        foreach ($finalFiles as $file) {
            if (file_exists($file)) {
                unlink($file);
                error_log("Deleted final file: " . $file);
            }
        }
    }
    echo json_encode(['success' => true, 'message' => 'Media deleted successfully.']);
    exit;
}
// For debugging, you might want to output the computed paths:
error_log('Public directory: ' . $publicDir);
error_log('Uploads directory: ' . $uploadsDir);
error_log('Temp directory: ' . $tempBaseDir);
session_start(); // Si ce n'est pas déjà fait en début de script

// --- Finalization Code (if finalize is sent) --- //
if (isset($_POST['finalize']) && $_POST['finalize'] == 'true') {
    if (!isset($_POST['fileId'])) {
        echo json_encode(['error' => 'Missing fileId for finalization.']);
        exit;
    }
    $fileId = $_POST['fileId'];
    $tempDir = $tempBaseDir . $fileId . '/';
    error_log("Finalizing file with ID: $fileId in directory: $tempDir");
    
    // Optional: Get original file name to preserve extension
    $originalFileName = isset($_POST['fileName']) ? $_POST['fileName'] : $fileId;
    $ext = pathinfo($originalFileName, PATHINFO_EXTENSION);
    $ext = $ext ? '.' . $ext : '';
    
    $finalFileName = $fileId . '_' . time() . $ext;
    $finalFilePath = $uploadsDir . $finalFileName;
    
    error_log("Creating final file: $finalFilePath");

    if (!$out = fopen($finalFilePath, "wb")) {
        error_log("Failed to open final file for writing: $finalFilePath");
        echo json_encode(['error' => 'Failed to open final file for writing.']);
        exit;
    }
    
    $chunkFiles = glob($tempDir . 'chunk_*');
    usort($chunkFiles, function($a, $b) {
        preg_match('/chunk_(\d+)/', $a, $matchA);
        preg_match('/chunk_(\d+)/', $b, $matchB);
        return intval($matchA[1]) - intval($matchB[1]);
    });
    
    foreach ($chunkFiles as $chunkFile) {
        error_log("Reassembling chunk file: " . basename($chunkFile));
        if (!$in = fopen($chunkFile, "rb")) {
            error_log("Failed to open chunk file: " . basename($chunkFile));
            echo json_encode(['error' => 'Failed to open chunk file: ' . basename($chunkFile)]);
            fclose($out);
            exit;
        }
        while (!feof($in)) {
            fwrite($out, fread($in, 8192));
        }
        fclose($in);
    }
    fclose($out);
    
    // Cleanup: remove chunk files and temporary directory.
    array_map('unlink', glob($tempDir . '*'));
    rmdir($tempDir);
    
    $publicUrl = 'http://localhost/projet-annonce/public/uploads/' . $finalFileName;
    
        // Sauvegarder le lien dans la session.
    if (!isset($_SESSION['uploadMediaFiles'])) {
        $_SESSION['uploadMediaFiles'] = [];
    }
    $_SESSION['uploadMediaFiles'][] = $publicUrl;

    echo json_encode([
        'filePath' => $finalFilePath,
        'fileUrl' => $publicUrl,
        'fileType' => ''
    ]);
    exit;
}

// --- Handle a Chunk Upload --- //
if (!isset($_FILES['file'])) {
    echo json_encode(['error' => 'No file uploaded.']);
    exit;
}

$file = $_FILES['file'];
if ($file['error'] !== UPLOAD_ERR_OK) {
    error_log("Upload error code: " . $file['error']);
    echo json_encode(['error' => 'Upload error code: ' . $file['error']]);
    exit;
}

if (!isset($_POST['fileId'], $_POST['chunkIndex'], $_POST['totalChunks'])) {
    echo json_encode(['error' => 'Missing chunk parameters.']);
    exit;
}

$fileId = $_POST['fileId'];
$chunkIndex = intval($_POST['chunkIndex']);
$totalChunks = intval($_POST['totalChunks']);
$tempDir = $tempBaseDir . $fileId . '/';

if (!is_dir($tempDir)) {
    if (mkdir($tempDir, 0777, true)) {
        error_log("Created temporary directory: $tempDir");
    } else {
        error_log("Failed to create temporary directory: $tempDir");
        echo json_encode(['error' => 'Failed to create temporary directory.']);
        exit;
    }
}

$chunkFileName = 'chunk_' . $chunkIndex;
$targetFilePath = $tempDir . $chunkFileName;

// Log before moving the file:
    error_log("Attempting to move file from " . $file['tmp_name'] . " to " . $targetFilePath);

    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
        error_log("Moved chunk $chunkIndex successfully for fileId $fileId");
        echo json_encode(['success' => true, 'chunkIndex' => $chunkIndex]);
        exit;
    } else {
        $lastError = error_get_last();
        error_log("Failed to move uploaded chunk. Last error: " . json_encode($lastError));
        echo json_encode(['error' => 'Failed to move uploaded chunk.']);
        exit;
    }
?>
