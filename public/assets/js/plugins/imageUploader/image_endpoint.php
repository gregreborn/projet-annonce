<?php
header('Content-Type: application/json');

// 📍 Étape 1 : calcul du dossier public
$publicDir = realpath(__DIR__ . '/../../../');
if ($publicDir === false) {
    echo json_encode(['error' => 'Impossible de localiser le dossier public.']);
    exit;
}

// 📂 Étape 2 : chemin absolu vers /uploads
$uploadsDir = $publicDir . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;

// 📦 Étape 3 : Vérifie que le dossier existe
if (!is_dir($uploadsDir)) {
    if (!mkdir($uploadsDir, 0777, true)) {
        echo json_encode(['error' => 'Impossible de créer le dossier uploads.']);
        exit;
    }
}

// 📤 Étape 4 : Vérifie que le fichier est bien envoyé
if (!isset($_FILES['file'])) {
    echo json_encode(['error' => 'Aucun fichier reçu.']);
    exit;
}

$file = $_FILES['file'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['error' => 'Erreur à l\'upload : code ' . $file['error']]);
    exit;
}

// 🔐 Étape 5 : validation du type
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($file['type'], $allowedTypes)) {
    echo json_encode(['error' => 'Type de fichier non autorisé.']);
    exit;
}

// 🏷️ Étape 6 : crée un nom unique
$uniqueName = time() . '_' . basename($file['name']);
$targetFilePath = $uploadsDir . $uniqueName;

// 💾 Étape 7 : déplacer le fichier dans /uploads
if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
    $publicUrl = 'http://localhost/projet-annonce/public/uploads/' . $uniqueName;
    echo json_encode([
        'filePath' => $targetFilePath,
        'fileUrl' => $publicUrl,
        'fileType' => $file['type']
    ]);
    exit;
} else {
    echo json_encode(['error' => 'Impossible de déplacer le fichier.']);
    exit;
}
