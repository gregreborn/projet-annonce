<?php
require_once "controller.php";
require_once __DIR__ . "/../model/annonces.php";
require_once __DIR__ . "/../model/media.php";
require_once __DIR__ . "/../model/categories.php";
use model\annonce;
use model\media;
use model\categories;

class annonceController extends Controller {

    // Private helper to load a template from a section and render it using the base layout.
    private function renderPage($section, $template, $data = [])
    {
        // Ensure the absolute path variables are present
        $data["SERVER_ABSOLUTE_PATH"] = SERVER_ABSOLUTE_PATH;
        $data["PUBLIC_ABSOLUTE_PATH"] = PUBLIC_ABSOLUTE_PATH;

        // ✅ Pass flash message if available
        if (isset($_SESSION["rcrcq_message"])) {
            $data["flashMessage"] = $_SESSION["rcrcq_message"];
            $data["flashError"] = $_SESSION["rcrcq_erreur"] == 1;
            unset($_SESSION["rcrcq_message"], $_SESSION["rcrcq_erreur"]); // Clear after displaying
        }

        switch ($section) {
            case 'forms':
                $templateFile = HTML_PUBLIC_FORMS_FS . "/" . $template;
                break;
            case 'listings':
                $templateFile = HTML_PUBLIC_LISTINGS_FS . "/" . $template;
                break;
            case 'pages':
            default:
                $templateFile = HTML_PUBLIC_PAGES_FS . "/" . $template;
                break;
        }
        
        if (!file_exists($templateFile)) {
            die("❌ ERROR: Template file not found: " . $templateFile);
        }
        
        $content = file_get_contents($templateFile);
        // Pre‑render the content so that its tags (like {{SERVER_ABSOLUTE_PATH}}) are replaced.
        $mustache = new Mustache_Engine([
            'loader' => new Mustache_Loader_StringLoader()
        ]);
        $renderedContent = $mustache->render($content, $data);
        $this->renderWithBase($renderedContent, $data);
    }

    
    

    // Render the ad type selection page (choix_annonce.html)
    function renderChoixAnnonce() {
        $this->renderPage('pages', "choix_annonce.html", []);
    }

    // Render the "J’offre" ad submission form
    function renderFormOffre() {
        $categories = Categories::getAllCategories() ?: [];  // Ensure it's an array even if no categories exist
        $data = ['categories' => is_array($categories) ? $categories : []];
        $this->renderPage('forms', "soumission_offre.html", $data);
    }

    // Render the "J’ai besoin" ad submission form
    function renderFormBesoin() {
        $categories = Categories::getAllCategories() ?: [];  // Ensure it's an array even if no categories exist
        $data = ['categories' => is_array($categories) ? $categories : []];
        $this->renderPage('forms', "soumission_besoin.html", $data);
    }
    

    // Validates form data.
    private function validateFormData($data) {
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
        if (empty($data['codePostal'])) $errors[] = ERR_POSTAL_REQUIRED;
        if (empty($data['categoriesId'])) $errors[] = ERR_CATEGORY_REQUIRED;
    
        // ✅ Date validation
        $today = date("Y-m-d");
    
        if (!empty($data['dateDeDebutPub']) && $data['dateDeDebutPub'] < $today) {
            $errors[] = "❌ La date de début ne peut pas être dans le passé.";
        }
    
        if (!empty($data['dateDeFinPub']) && !empty($data['dateDeDebutPub'])) {
            if ($data['dateDeFinPub'] <= $data['dateDeDebutPub']) {
                $errors[] = "❌ La date de fin doit être après la date de début.";
            }
        }
    
        return $errors;
    }
    

    // Helper to set flash messages.
    private function setFlashMessage($message, $isError = false) {
        $_SESSION["rcrcq_message"] = $message;
        $_SESSION["rcrcq_erreur"] = $isError ? 1 : 0;
    }

    // Processes ad creation.
    function createAnnonce() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            return $this->renderChoixAnnonce();
        }
    
        $fields = [
            'nomOrganisme', 'nom', 'prenom', 'titre', 'description',
            'telephone', 'courriel', 'site', 'dateDeDebutPub', 'dateDeFinPub',
            'adresse', 'ville', 'codePostal', 'mrc', 'categoriesId'
        ];
        $formData = [];
        foreach ($fields as $field) {
            $formData[$field] = trim($_POST[$field] ?? '');
        }
    
        // Ensure website URLs have HTTP/HTTPS
        if (!empty($formData['site']) && !preg_match('#^https?://#i', $formData['site'])) {
            $formData['site'] = 'http://' . $formData['site'];
        }
    
        $formData['type'] = $_POST['type'] ?? '';
    
        // ✅ Server-side validation
        $errors = $this->validateFormData($formData);
        if (!empty($errors)) {
            // Instead of redirecting, re-render the form with errors
            return $this->renderPage('forms', "soumission_offre.html", [
                'categories' => Categories::getAllCategories(),
                'formData' => $formData,
                'errors' => $errors
            ]);
        }
    
        $annonceId = Annonce::createAnnonce($formData);
        if ($annonceId === -1) {
            $this->setFlashMessage("❌ Échec de la création de l’annonce.", true);
            return $this->renderFormOffre();
        }
    
        // ✅ Handle file upload (if any)
        if (!empty($_FILES['media']['name'][0])) {
            $this->handleMediaUpload($_FILES['media'], $annonceId);
        }
    
        $this->setFlashMessage("✅ Annonce créée avec succès !");
        header("Location: " . SERVER_ABSOLUTE_PATH . "/annonces");
        exit();
    }
    
    
    

    // Handles media uploads.
    private function handleMediaUpload($files, $annonceId) {
        $allowedTypes = ["image/jpeg", "image/png", "image/gif", "video/mp4", "application/pdf"];
        $uploadDir = __DIR__ . "/../public/uploads/";
        $maxFileSize = 20 * 1024 * 1024;
        foreach ($files['name'] as $key => $fileName) {
            if ($files['error'][$key] === UPLOAD_ERR_OK) {
                $fileTmpPath = $files['tmp_name'][$key];
                $fileSize = $files['size'][$key];
                $fileType = mime_content_type($fileTmpPath);
                if (!in_array($fileType, $allowedTypes)) {
                    continue;
                }
                if ($fileSize > $maxFileSize) {
                    continue;
                }
                $uniqueName = time() . "_" . basename($fileName);
                $targetFilePath = $uploadDir . $uniqueName;
                if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
                    Media::saveMedia($annonceId, $targetFilePath, SERVER_ABSOLUTE_PATH . "/public/uploads/" . $uniqueName, $fileType);
                }
            }
        }
    }

    // Retrieves all ads and renders a listing page.
    function getAllAnnonces() {
        $annonces = Annonce::getAllAnnonces();
        $data = ["annonces" => $annonces];
        $this->renderPage('listings', "liste_offres.html", $data);
    }

    // Optionally, filter ads by type (offre or besoin)
    function getAnnoncesByType($type) {
        if (!in_array($type, ["offre", "besoin"])) {
            $type = "offre";
        }
        $annonces = Annonce::getAnnoncesByType($type);
        $data = ["annonces" => $annonces];
        $this->renderPage('listings', "liste_{$type}s.html", $data);
    }

    // Deletes an ad.
    function deleteAnnonce($id) {
        Media::deleteMediaByAnnonceId($id);
        $deleted = Annonce::deleteAnnonce($id);
        if ($deleted) {
            $_SESSION["rcrcq_message"] = "✅ Ad deleted successfully!";
        } else {
            $_SESSION["rcrcq_message"] = "❌ Failed to delete the ad.";
        }
        header("Location: " . SERVER_ABSOLUTE_PATH . "/annonces");
        exit();
    }

    function render() {
        $this->getAllAnnonces();
    }
}
?>
