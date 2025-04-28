<?php
header('Content-Type: application/json');
session_start(); // Démarrer la session si ce n'est pas déjà fait

// Calcul du chemin absolu vers le dossier public
$publicDir = realpath(__DIR__ . '/../../../..');
if ($publicDir === false) {
    die('Erreur : impossible de résoudre le dossier public.');
}

// Dossiers de téléversement final et temporaire
$uploadsDir = $publicDir . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
$tempBaseDir = $uploadsDir . 'temp' . DIRECTORY_SEPARATOR;

// === Suppression de média ===
if (isset($_POST['delete']) && ($_POST['delete'] === 'true' || $_POST['delete'] === true)) {
    if (!isset($_POST['fileId'])) {
        echo json_encode(['error' => 'fileId manquant pour la suppression.']);
        exit;
    }

    $fileId = $_POST['fileId'];
    $tempDir = $tempBaseDir . $fileId . '/';

    // Suppression des fichiers de chunk temporaires
    if (is_dir($tempDir)) {
        $files = glob($tempDir . '*');
        if ($files !== false) {
            foreach ($files as $file) {
                if (file_exists($file)) unlink($file);
            }
        }
        rmdir($tempDir);
    }

    // Suppression du fichier final s'il existe
    $finalFiles = glob($uploadsDir . $fileId . '_*');
    if ($finalFiles !== false) {
        foreach ($finalFiles as $file) {
            if (file_exists($file)) unlink($file);
        }
    }

    // Suppression de la session
    unset($_SESSION['uploadMediaFiles'][$fileId]);

    echo json_encode(['success' => true, 'message' => 'Média supprimé avec succès.']);
    exit;
}

// === Finalisation d'un téléversement (assemblage des chunks) ===
if (isset($_POST['finalize']) && $_POST['finalize'] == 'true') {
    set_time_limit(0);

    if (!isset($_POST['fileId'])) {
        echo json_encode(['error' => 'fileId manquant pour la finalisation.']);
        exit;
    }

    $fileId = $_POST['fileId'];
    $tempDir = $tempBaseDir . $fileId . '/';

    $originalFileName = $_POST['fileName'] ?? $fileId;
    $ext = pathinfo($originalFileName, PATHINFO_EXTENSION);
    $extWithDot = $ext ? '.' . $ext : '';
    $finalFileName = $fileId . '_' . time() . $extWithDot;
    $finalFilePath = $uploadsDir . $finalFileName;

    if (!$out = fopen($finalFilePath, "wb")) {
        echo json_encode(['error' => 'Impossible de créer le fichier final.']);
        exit;
    }

    $chunkFiles = glob($tempDir . 'chunk_*');
    if (!$chunkFiles) {
        fclose($out);
        echo json_encode(['error' => 'Aucun chunk trouvé pour la finalisation.']);
        exit;
    }

    usort($chunkFiles, function($a, $b) {
        preg_match('/chunk_(\d+)/', $a, $matchA);
        preg_match('/chunk_(\d+)/', $b, $matchB);
        return intval($matchA[1]) - intval($matchB[1]);
    });

    foreach ($chunkFiles as $chunkFile) {
        $in = fopen($chunkFile, "rb");
        while (!feof($in)) {
            fwrite($out, fread($in, 8192));
        }
        fclose($in);
    }
    fclose($out);

    array_map('unlink', glob($tempDir . '*'));
    rmdir($tempDir);

    $publicUrl = 'http://localhost/projet-annonce/public/uploads/' . $finalFileName;

    $_SESSION['uploadMediaFiles'][$fileId] = [
        'fileUrl' => $publicUrl,
        'extension' => $ext,
        'fileName' => $originalFileName
    ];

    echo json_encode([
        'filePath' => $finalFilePath,
        'fileUrl' => $publicUrl,
        'extension' => $ext,
        'fileType' => ''
    ]);
    exit;
}

// === Téléversement direct d'un fichier unique (pas de chunks) ===
if (!isset($_POST['finalize']) && isset($_POST['totalChunks']) && intval($_POST['totalChunks']) === 1 && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $original = $_POST['fileName'] ?? $file['name'];
    $ext = pathinfo($original, PATHINFO_EXTENSION);
    $finalName = $_POST['fileId'] . '_' . time() . ($ext ? ".{$ext}" : '');
    $finalPath = $uploadsDir . $finalName;

    if (move_uploaded_file($file['tmp_name'], $finalPath)) {
        $_SESSION['uploadMediaFiles'][$_POST['fileId']] = [
            'fileUrl' => "http://localhost/projet-annonce/public/uploads/{$finalName}",
            'extension' => $ext,
            'fileName' => $original
        ];

        echo json_encode([
            'filePath' => $finalPath,
            'fileUrl' => $_SESSION['uploadMediaFiles'][$_POST['fileId']]['fileUrl'],
            'extension' => $ext,
            'fileType' => $file['type']
        ]);
    } else {
        echo json_encode(['error' => 'Impossible de déplacer le fichier.']);
    }
    exit;
}

// === Téléversement d'un chunk ===
if (!isset($_FILES['file'])) {
    echo json_encode(['error' => 'Aucun fichier envoyé.']);
    exit;
}

$file = $_FILES['file'];
if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['error' => 'Erreur lors du téléversement : ' . $file['error']]);
    exit;
}

if (!isset($_POST['fileId'], $_POST['chunkIndex'], $_POST['totalChunks'])) {
    echo json_encode(['error' => 'Paramètres de chunk manquants.']);
    exit;
}

$fileId = $_POST['fileId'];
$chunkIndex = intval($_POST['chunkIndex']);
$totalChunks = intval($_POST['totalChunks']);
$tempDir = $tempBaseDir . $fileId . '/';

if (!is_dir($tempDir)) {
    if (!mkdir($tempDir, 0777, true)) {
        echo json_encode(['error' => 'Impossible de créer le répertoire temporaire.']);
        exit;
    }
}

$chunkFileName = 'chunk_' . $chunkIndex;
$targetFilePath = $tempDir . $chunkFileName;

if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
    echo json_encode(['success' => true, 'chunkIndex' => $chunkIndex]);
    exit;
} else {
    echo json_encode(['error' => 'Impossible de déplacer le chunk.']);
    exit;
}
?>
