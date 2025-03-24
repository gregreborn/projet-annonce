<?php
require_once "controller.php";
require_once __DIR__ . "/../model/annonces.php";
require_once __DIR__ . "/../model/media.php";
require_once __DIR__ . "/../model/categories.php";
use model\annonce;
use model\media;
use model\categories;

class annonceController extends Controller {

    /**
     * Rend une page HTML à partir d'un template spécifique.
     * Le contenu est inséré dans une structure de base (layout).
     */    private function renderPage($section, $template, $data = [])
    {
        $data["SERVER_ABSOLUTE_PATH"] = SERVER_ABSOLUTE_PATH;
        $data["PUBLIC_ABSOLUTE_PATH"] = PUBLIC_ABSOLUTE_PATH;

        // Ajoute un message flash s'il existe
        if (isset($_SESSION["rcrcq_message"])) {
            $data["flashMessage"] = $_SESSION["rcrcq_message"];
            $data["flashError"] = $_SESSION["rcrcq_erreur"] == 1;
            unset($_SESSION["rcrcq_message"], $_SESSION["rcrcq_erreur"]); // Clear after displaying
        }

        // Détermine le bon dossier de template selon la section
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
        // Remplace les balises personnalisées (comme {{VAR}}) avec Mustache
        $mustache = new Mustache_Engine([
            'loader' => new Mustache_Loader_StringLoader()
        ]);
        $renderedContent = $mustache->render($content, $data);
        $this->renderWithBase($renderedContent, $data);
    }

    
    

    /**
     * Affiche la page de sélection du type d’annonce.
     */    
    function renderChoixAnnonce() {
        $this->renderPage('pages', "choix_annonce.html", []);
    }

    /**
    * Affiche le formulaire de soumission pour une annonce de type "offre".
    */
    function renderFormOffre() {
        $categories = Categories::getAllCategories() ?: [];  // Ensure it's an array even if no categories exist
        $data = ['categories' => is_array($categories) ? $categories : []];
        $this->renderPage('forms', "soumission_offre.html", $data);
    }

    /**
     * Affiche le formulaire de soumission pour une annonce de type "besoin".
     */    function renderFormBesoin() {
        $categories = Categories::getAllCategories() ?: [];  // Ensure it's an array even if no categories exist
        $data = ['categories' => is_array($categories) ? $categories : []];
        $this->renderPage('forms', "soumission_besoin.html", $data);
    }
    

    /**
     * Valide les champs du formulaire de soumission.
     */    private function validateFormData($data) {
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
        if (empty($data['categoriesId'])) $errors[] = ERR_CATEGORY_REQUIRED;
    
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
    

    /**
     * Définit un message flash à afficher à l'utilisateur.
     */    
    private function setFlashMessage($message, $isError = false) {
        $_SESSION["rcrcq_message"] = $message;
        $_SESSION["rcrcq_erreur"] = $isError ? 1 : 0;
    }

    
    /**
     * Traite la création d'une annonce (POST).
     */
    function createAnnonce() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            return $this->renderChoixAnnonce();
        }
    
        $fields = [
            'nomOrganisme', 'nom', 'prenom', 'titre', 'description',
            'telephone', 'courriel', 'site', 'dateDeDebutPub', 'dateDeFinPub',
            'adresse', 'ville', 'province', 'codePostal', 'mrc', 'categoriesId'
        ];
        $formData = [];
        foreach ($fields as $field) {
            $formData[$field] = trim($_POST[$field] ?? '');
        }
    
        // Ajoute le protocole HTTP/HTTPS si manquant
        if (!empty($formData['site']) && !preg_match('#^https?://#i', $formData['site'])) {
            $formData['site'] = 'http://' . $formData['site'];
        }
    
        $formData['type'] = $_POST['type'] ?? '';
    
        // Validation des données
        $errors = $this->validateFormData($formData);
        if (!empty($errors)) {
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
    
        // Traitement des fichiers média si fournis
        if (!empty($_FILES['media']['name'][0])) {
            $this->handleMediaUpload($_FILES['media'], $annonceId);
        }
    
        $this->setFlashMessage("✅ Annonce créée avec succès !");
        header("Location: " . SERVER_ABSOLUTE_PATH . "/annonces");
        exit();
    }
    
    
    

    /**
     * Gère l’upload de fichiers média liés à l’annonce.
     */    
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

    /**
     * Récupère et affiche toutes les annonces.
     */    
    function getAllAnnonces() {
        $annonces = Annonce::getAllAnnonces();
        $data = ["annonces" => $annonces];
        $this->renderPage('listings', "liste_offres.html", $data);
    }

    /**
     * Récupère les annonces selon leur type ("offre" ou "besoin").
     */    
    function getAnnoncesByType($type) {
        if (!in_array($type, ["offre", "besoin"])) {
            $type = "offre";
        }
        $annonces = Annonce::getAnnoncesByType($type);
        $data = ["annonces" => $annonces];
        $this->renderPage('listings', "liste_{$type}s.html", $data);
    }

    /**
     * Supprime une annonce et les fichiers média associés.
     */    
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
    
    /**
     * Point d’entrée par défaut du contrôleur : liste toutes les annonces.
     */
    function render() {
        $this->getAllAnnonces();
    }
}
?>
