<?php
header('Content-Type: application/json');

// Définissez le dossier final des uploads (par exemple, public/uploads)
$uploadsDir = __DIR__ . '/../../../../uploads/';
// Dossier temporaire pour stocker les chunks
$tempBaseDir = $uploadsDir . 'temp/';

// === Bloc de SUPPRESSION ===
if (isset($_POST['delete']) && $_POST['delete'] === 'true') {
    if (!isset($_POST['fileId'])) {
        echo json_encode(['error' => 'Missing fileId for deletion.']);
        exit;
    }

    $fileId = $_POST['fileId'];

    // Supprimer le dossier temporaire des chunks
    $tempDir = $tempBaseDir . $fileId . '/';
    if (is_dir($tempDir)) {
        array_map('unlink', glob($tempDir . '*'));
        rmdir($tempDir);
    }

    // Supprimer le(s) fichier(s) final(aux) qui contiennent le fileId
    $files = glob($uploadsDir . $fileId . '_*');
    foreach ($files as $file) {
        if (file_exists($file)) {
            unlink($file);
        }
    }

    echo json_encode(['success' => true, 'message' => 'Media deleted successfully.']);
    exit;
}
// Si le paramètre "finalize" est présent, réassemblez les chunks
if (isset($_POST['finalize']) && $_POST['finalize'] == 'true') {
    if (!isset($_POST['fileId'])) {
        echo json_encode(['error' => 'Missing fileId for finalization.']);
        exit;
    }
    $fileId = $_POST['fileId'];
    $tempDir = $tempBaseDir . $fileId . '/';
    
    // Optionnel : obtenir le nom d'origine pour conserver l'extension
    $originalFileName = isset($_POST['fileName']) ? $_POST['fileName'] : $fileId;
    $ext = pathinfo($originalFileName, PATHINFO_EXTENSION);
    $ext = $ext ? '.' . $ext : '';
    
    // Créer un nom final unique
    $finalFileName = $fileId . '_' . time() . $ext;
    $finalFilePath = $uploadsDir . $finalFileName;
    
    // Ouvrir le fichier final en écriture binaire
    if (!$out = fopen($finalFilePath, "wb")) {
        echo json_encode(['error' => 'Failed to open final file for writing.']);
        exit;
    }
    
    // Récupérer tous les chunks et les trier par ordre croissant d'index
    $chunkFiles = glob($tempDir . 'chunk_*');
    usort($chunkFiles, function($a, $b) {
        preg_match('/chunk_(\d+)/', $a, $matchA);
        preg_match('/chunk_(\d+)/', $b, $matchB);
        return intval($matchA[1]) - intval($matchB[1]);
    });
    
    // Réassembler les chunks dans le fichier final
    foreach ($chunkFiles as $chunkFile) {
        if (!$in = fopen($chunkFile, "rb")) {
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
    
    // Nettoyage : supprimer les chunks et le dossier temporaire
    array_map('unlink', glob($tempDir . '*'));
    rmdir($tempDir);
    
    // Construire l'URL publique du fichier final (ajustez selon votre environnement)
    $publicUrl = 'http://localhost/projet-annonce/public/uploads/' . $finalFileName;
    
    echo json_encode([
        'filePath' => $finalFilePath,
        'fileUrl' => $publicUrl,
        'fileType' => '' // Vous pouvez déterminer le type MIME si besoin
    ]);
    exit;
}

// Sinon, traiter un chunk individuel
if (!isset($_FILES['file'])) {
    echo json_encode(['error' => 'No file uploaded.']);
    exit;
}

$file = $_FILES['file'];
if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['error' => 'Upload error code: ' . $file['error']]);
    exit;
}

// Validation des types de fichiers autorisés pour les médias
$allowedTypes = [
    'video/mp4', 'video/webm', 'video/ogg',
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
];
if (!in_array($file['type'], $allowedTypes)) {
    echo json_encode(['error' => 'Invalid file type.']);
    exit;
}

// Vérifier les paramètres indispensables pour le chunk
if (!isset($_POST['fileId'], $_POST['chunkIndex'], $_POST['totalChunks'])) {
    echo json_encode(['error' => 'Missing chunk parameters.']);
    exit;
}

$fileId = $_POST['fileId'];
$chunkIndex = intval($_POST['chunkIndex']);
$totalChunks = intval($_POST['totalChunks']);

// Créer le dossier temporaire pour ce fichier si nécessaire
$tempDir = $tempBaseDir . $fileId . '/';
if (!is_dir($tempDir)) {
    mkdir($tempDir, 0777, true);
}

// Sauvegarder le chunk dans le dossier temporaire avec un nom indiquant son index
$chunkFileName = 'chunk_' . $chunkIndex;
$targetFilePath = $tempDir . $chunkFileName;
if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
    echo json_encode(['success' => true, 'chunkIndex' => $chunkIndex]);
    exit;
} else {
    echo json_encode(['error' => 'Failed to move uploaded chunk.']);
    exit;
}
?>
