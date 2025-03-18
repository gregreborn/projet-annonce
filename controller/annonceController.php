<?php
require_once "controller.php";
require_once __DIR__ . "/../model/annonce.php";
require_once __DIR__ . "/../model/media.php";

use model\annonce;
use model\media;

class annonceController extends Controller
{
    function renderform()
    {
        $data = [];
        $this->renderTemplate(file_get_contents(HTML_PUBLIC . "/annonce_form.html"), $data);
    }

    private function validateFormData($data)
{
    $errors = [];

    if (empty($data['nomOrganisme'])) $errors[] = ERR_ORGANISATION_REQUIRED;
    if (empty($data['nom'])) $errors[] = ERR_LAST_NAME_REQUIRED;
    if (empty($data['prenom'])) $errors[] = ERR_FIRST_NAME_REQUIRED;
    if (empty($data['titre'])) $errors[] = ERR_TITLE_REQUIRED;
    if (empty($data['description'])) $errors[] = ERR_DESCRIPTION_REQUIRED;
    if (!filter_var($data['courriel'], FILTER_VALIDATE_EMAIL)) $errors[] = ERR_INVALID_EMAIL;
    if (!empty($data['telephone']) && !is_numeric($data['telephone'])) $errors[] = ERR_PHONE_NUMERIC;
    if (!empty($data['site']) && !filter_var($data['site'], FILTER_VALIDATE_URL)) $errors[] = ERR_INVALID_URL;
    if (empty($data['ville'])) $errors[] = ERR_CITY_REQUIRED;
    if (empty($data['province'])) $errors[] = ERR_PROVINCE_REQUIRED;
    if (empty($data['codePostal'])) $errors[] = ERR_POSTAL_REQUIRED;

    return $errors;
}
// Helper to set flash messages
private function setFlashMessage($message, $isError = false)
{
    $_SESSION["rcrcq_message"] = $message;
    $_SESSION["rcrcq_erreur"] = $isError ? 1 : 0;
}

function createAnnonce()
{
    // Only process POST requests; otherwise, show the form.
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        return $this->renderForm();
    }

    // Define expected form fields.
    $fields = [
        'nomOrganisme', 'nom', 'prenom', 'titre', 'description',
        'telephone', 'courriel', 'site', 'dateDeDebutPub', 'dateDeFinPub',
        'adresse', 'ville', 'province', 'codePostal', 'mrc'
    ];

    // Retrieve form data using the defined fields.
    $formData = [];
    foreach ($fields as $field) {
        $formData[$field] = $_POST[$field] ?? '';
    }

    // Validate form data.
    $errors = $this->validateFormData($formData);
    if (!empty($errors)) {
        $this->setFlashMessage(implode("<br>", $errors), true);
        return $this->renderForm();
    }

    // Attempt to insert the annonce into the database.
    $annonceId = Annonce::createAnnonce($formData);
    if (!$annonceId) {
        $this->setFlashMessage("❌ Failed to create the ad.", true);
        return $this->renderForm();
    }

    // Handle file uploads if media files were provided.
    if (!empty($_FILES['media']['name'][0])) {
        $this->handleMediaUpload($_FILES['media'], $annonceId);
    }

    // Set a success message and redirect.
    $this->setFlashMessage("✅ Ad created successfully!");
    header("Location: " . SERVER_ABSOLUTE_PATH . "/annonces");
    exit();
}

// ✅ Handle media uploads
private function handleMediaUpload($files, $annonceId)
{
    $allowedTypes = ["image/jpeg", "image/png", "image/gif", "video/mp4", "application/pdf"];
    $uploadDir = __DIR__ . "/../public/uploads/";
    $maxFileSize = 20 * 1024 * 1024; // 20MB

    foreach ($files['name'] as $key => $fileName) {
        if ($files['error'][$key] === UPLOAD_ERR_OK) {
            $fileTmpPath = $files['tmp_name'][$key];
            $fileSize = $files['size'][$key];
            $fileType = mime_content_type($fileTmpPath);

            if (!in_array($fileType, $allowedTypes)) {
                continue; // Skip invalid file types
            }

            if ($fileSize > $maxFileSize) {
                continue; // Skip files exceeding size limit
            }

            $uniqueName = time() . "_" . basename($fileName);
            $targetFilePath = $uploadDir . $uniqueName;

            if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
                // Save file information to the database
                Media::saveMedia($annonceId, $targetFilePath, SERVER_ABSOLUTE_PATH . "/public/uploads/" . $uniqueName, $fileType);
            }
        }
    }
}

// ✅ Retrieve all annonces
function getAllAnnonces()
{
    $annonces = Annonce::getAllAnnonces();
    $data = ["annonces" => $annonces];
    $this->renderTemplate(file_get_contents(HTML_PUBLIC . "/annonces.html"), $data);
}

// ✅ Delete an annonce
function deleteAnnonce($id)
{
    // Delete associated media first
    Media::deleteMediaByAnnonceId($id);

    // Delete the annonce
    $deleted = Annonce::deleteAnnonce($id);

    if ($deleted) {
        $_SESSION["rcrcq_message"] = "✅ Ad deleted successfully!";
    } else {
        $_SESSION["rcrcq_message"] = "❌ Failed to delete the ad.";
    }

    header("Location: " . SERVER_ABSOLUTE_PATH . "/annonces");
    exit();
}

    
}