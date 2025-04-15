<?php
header('Content-Type: application/json');

/*
  This script lives at for example:
  C:\wamp64\www\projet-annonce\public\assets\js\plugins\mediaUploader\mediaUploader.php
  It uses the "uploads" folder inside your public directory.
*/

// Compute the absolute path to the public directory.
$publicDir = realpath(__DIR__ . '/../../../..');
if ($publicDir === false) {
    die('Error: Could not resolve public directory.');
}

// Append 'uploads' and ensure proper directory separators.
$uploadsDir = $publicDir . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;

// Define your temp base directory within uploads.
$tempBaseDir = $uploadsDir . 'temp' . DIRECTORY_SEPARATOR;

// For debugging: output the computed paths.
error_log('Public directory: ' . $publicDir);
error_log('Uploads directory: ' . $uploadsDir);
error_log('Temp directory: ' . $tempBaseDir);

// --- Finalization (Reassembly) Branch ---
if (isset($_POST['finalize']) && $_POST['finalize'] == 'true') {
    // Increase execution time for large files.
    set_time_limit(0);
    
    if (!isset($_POST['fileId'])) {
        echo json_encode(['error' => 'Missing fileId for finalization.']);
        exit;
    }
    $fileId = $_POST['fileId'];
    $tempDir = $tempBaseDir . $fileId . '/';
    error_log("Finalizing file with ID: $fileId in directory: $tempDir");
    
    // Optionally, obtain the original file name to preserve its extension.
    $originalFileName = isset($_POST['fileName']) ? $_POST['fileName'] : $fileId;
    $ext = pathinfo($originalFileName, PATHINFO_EXTENSION);
    $ext = $ext ? '.' . $ext : '';
    
    // Create a unique final file name.
    $finalFileName = $fileId . '_' . time() . $ext;
    $finalFilePath = $uploadsDir . $finalFileName;
    error_log("Creating final file: $finalFilePath");

    // Attempt to open the final file for writing in binary mode.
    if (!$out = fopen($finalFilePath, "wb")) {
        error_log("Failed to open final file for writing: $finalFilePath");
        echo json_encode(['error' => 'Failed to open final file for writing.']);
        exit;
    }
    
    // Get all chunk files.
    $chunkFiles = glob($tempDir . 'chunk_*');
    if ($chunkFiles === false || empty($chunkFiles)) {
        error_log("No chunk files found in: $tempDir");
        echo json_encode(['error' => 'No chunk files found for finalization.']);
        fclose($out);
        exit;
    }
    
    // Sort chunk files numerically.
    usort($chunkFiles, function($a, $b) {
        preg_match('/chunk_(\d+)/', $a, $matchA);
        preg_match('/chunk_(\d+)/', $b, $matchB);
        return intval($matchA[1]) - intval($matchB[1]);
    });
    
    // Reassemble chunks into the final file.
    foreach ($chunkFiles as $chunkFile) {
        error_log("Reassembling chunk file: " . basename($chunkFile));
        if (!$in = fopen($chunkFile, "rb")) {
            error_log("Failed to open chunk file: " . basename($chunkFile));
            echo json_encode(['error' => 'Failed to open chunk file: ' . basename($chunkFile)]);
            fclose($out);
            exit;
        }
        while (!feof($in)) {
            $buffer = fread($in, 8192);
            if ($buffer === false) {
                error_log("Error reading chunk file: " . basename($chunkFile));
                echo json_encode(['error' => 'Error reading chunk file: ' . basename($chunkFile)]);
                fclose($in);
                fclose($out);
                exit;
            }
            fwrite($out, $buffer);
        }
        fclose($in);
    }
    fclose($out);
    
    // Cleanup: remove all chunk files and the temporary directory.
    array_map('unlink', glob($tempDir . '*'));
    rmdir($tempDir);
    
    // Build the public URL for the final file.
    $publicUrl = 'http://localhost/projet-annonce/public/uploads/' . $finalFileName;
    
    // Save the final URL into the session for later processing.
    session_start();
    if (!isset($_SESSION['uploadMediaFiles'])) {
        $_SESSION['uploadMediaFiles'] = [];
    }
    $_SESSION['uploadMediaFiles'][$fileId] = $publicUrl;
    
    error_log("Finalization complete. File URL: $publicUrl");
    
    echo json_encode([
        'filePath' => $finalFilePath,
        'fileUrl'  => $publicUrl,
        'fileType' => ''
    ]);
    exit;
}

// --- Handle a Chunk Upload ---
if (!isset($_FILES['file'])) {
    echo json_encode(['error' => 'No file uploaded.']);
    exit;
}

$file = $_FILES['file'];
if ($file['error'] !== UPLOAD_ERR_OK) {
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

// Create the temporary directory if it doesn't exist.
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

error_log("Attempting to move file from " . $file['tmp_name'] . " to " . $targetFilePath);
if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
    error_log("Moved chunk $chunkIndex successfully for fileId $fileId");
    echo json_encode(['success' => true, 'chunkIndex' => $chunkIndex]);
    exit;
} else {
    error_log("Failed to move uploaded chunk.");
    echo json_encode(['error' => 'Failed to move uploaded chunk.']);
    exit;
}
?>
