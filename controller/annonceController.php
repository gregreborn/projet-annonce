<?php
require_once "controller.php";
require_once __DIR__ . "/../model/annonces.php";
require_once __DIR__ . "/../model/media.php";
require_once __DIR__ . "/../model/categories.php";
require_once __DIR__ . "/../model/localites.php";
use model\annonce;
use model\media;
use model\categories;
use model\Localite;

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
        $categories = Categories::getAllCategories() ?: [];
        $localites = Localite::getAll() ?: [];
    
        $data = [
            'categories' => is_array($categories) ? $categories : [],
            'localites' => is_array($localites) ? $localites : [],
        ];
    
        $this->renderPage('forms', "soumission_offre.html", $data);
    }
    

    // Render the "J’ai besoin" ad submission form
    function renderFormBesoin() {
        $categories = Categories::getAllCategories() ?: [];
        $localites = Localite::getAll() ?: [];
    
        $data = [
            'categories' => is_array($categories) ? $categories : [],
            'localites' => is_array($localites) ? $localites : [],
        ];
    
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
        error_log("POST keys: " . implode(", ", array_keys($_POST)));
        error_log("uploadedMedia raw: " . ($_POST['uploadedMedia'] ?? 'NULL'));
        $fields = [
            'nomOrganisme', 'nom', 'prenom', 'titre', 'description',
            'telephone', 'courriel', 'site', 'dateDeDebutPub', 'dateDeFinPub',
            'adresse', 'ville', 'categoriesId','localiteId'
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

        // 🔥 Get the postal code prefix from ville
        $localite = Localite::getById($formData['localiteId']);
        if ($localite) {
            $formData['ville'] = $localite['nom'];
            $formData['mrc'] = $localite['mrc'];
            $formData['codePostal'] = $localite['prefixe_postal'] ?? '';
        } else {
            $formData['ville'] = '';
            $formData['mrc'] = '';
            $formData['codePostal'] = '';
        }

            
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

        // Process plugin image data if provided.
        if (!empty($_POST['uploadedImages'])) {
            $uploadedImages = json_decode($_POST['uploadedImages'], true);
            if ($uploadedImages && is_array($uploadedImages)) {
                foreach ($uploadedImages as $image) {
                    $isThumbnail = (!empty($image['isThumbnail']) && $image['isThumbnail']) ? '1' : '0';
                    Media::saveMedia($annonceId, $image['filePath'], $image['fileUrl'], $image['fileType'], $isThumbnail);
                    if ($isThumbnail === '1' && isset($image['originalFilePath'])) {
                        Media::saveMedia($annonceId, $image['originalFilePath'], $image['originalFileUrl'] ?? $image['fileUrl'], $image['fileType'], '0');
                    }
                }
            }
        }
        // Process plugin media data if provided.
        if (!empty($_POST['uploadedMedia'])) {
            $uploadedMedias = json_decode($_POST['uploadedMedia'], true);
            if ($uploadedMedias && is_array($uploadedMedias)) {
                foreach ($uploadedMedias as $m) {
                    $isThumb = (!empty($m['isThumbnail']) && $m['isThumbnail']) ? '1' : '0';
                    // Enregistre le chunk final ou le filePath/fileUrl fourni par le plugin
                    Media::saveMedia(
                        $annonceId,
                        $m['filePath'],
                        $m['fileUrl'],
                        $m['fileType'],
                        $isThumb
                    );
                }
            }
        }

        $this->setFlashMessage("✅ Annonce créée avec succès !");
        header("Location: " . SERVER_ABSOLUTE_PATH . "/annonces");
        exit();
    }
    
    

    // Retrieves all ads and renders a listing page.
    function getAllAnnonces() {
        $annonces = Annonce::getAllAnnonces();
        $data = ["annonces" => $annonces];
        $this->renderPage('listings', "liste_offres.html", $data);
    }

    function getAnnoncesByType($type) {
        $dbType = ($type === 'besoin') ? 'b' : 'o';
    
        $q = $_GET['q'] ?? null;
        $sort = $_GET['sort'] ?? null;
        $selectedCategory = $_GET['cat'] ?? null;
        $ville = $_GET['ville'] ?? null;
        $radius = $_GET['radius'] ?? ($ville ? 10 : null);
    
        if ($q || $selectedCategory) {
            $annonces = Annonce::searchAnnoncesByTypeAndQuery($dbType, $q, $sort, $selectedCategory);
        } else {
            $annonces = Annonce::getAnnoncesWithFirstImageByTypeSorted($dbType, $sort);
        }
    
        if ($ville && $radius) {
            $selectedLocalite = Localite::getByName($ville);
        
            if ($selectedLocalite) {
                $userLat = floatval($selectedLocalite['latitude']);
                $userLng = floatval($selectedLocalite['longitude']);
        
                // Fetch lat/lng from joined localites
                foreach ($annonces as &$a) {
                    if (!isset($a['localiteId'])) {
                        $a['withinRadius'] = false;
                        continue;
                    }
        
                    $annonceLocalite = Localite::getById($a['localiteId']);
        
                    if (!$annonceLocalite || !isset($annonceLocalite['latitude'], $annonceLocalite['longitude'])) {
                        $a['withinRadius'] = false;
                        continue;
                    }
        
                    $lat = floatval($annonceLocalite['latitude']);
                    $lng = floatval($annonceLocalite['longitude']);
        
                    $earthRadius = 6371; // in km
                    $dLat = deg2rad($lat - $userLat);
                    $dLng = deg2rad($lng - $userLng);
        
                    $calcA = sin($dLat / 2) ** 2 + cos(deg2rad($userLat)) * cos(deg2rad($lat)) * sin($dLng / 2) ** 2;
                    $c = 2 * atan2(sqrt($calcA), sqrt(1 - $calcA));
                    $distance = $earthRadius * $c;
        
                    $a['withinRadius'] = $distance <= $radius;
                }
        
                // Keep only annonces within radius
                $annonces = array_filter($annonces, fn($a) => $a['withinRadius'] ?? false);
            }
        }
        
        
    
        // Categories for filter dropdown
        $categories = Categories::getAllCategories();
        foreach ($categories as &$cat) {
            $cat['isSelected'] = ($cat['id'] == $selectedCategory);
        }
    
        $data = [
            "annonces" => $annonces,
            "query" => $q,
            "sort" => $sort,
            "categories" => $categories,
            "selectedCategory" => $selectedCategory,
            "selectedCity" => $ville,
            "selectedRadius" => $radius,
            "ville" => $ville,
            "selectedLat" => $_GET['lat'] ?? null,   
            "selectedLng" => $_GET['lng'] ?? null,      
            "isSortRecent" => $sort === "recent",
            "isSortAlpha" => $sort === "alpha",
            "isSortVille" => $sort === "ville"
        ];
        
        // Mark selected radius options for Mustache
        foreach (['10', '25', '50', '100'] as $r) {
            $data["selectedRadius$r"] = ($radius == $r);
        }
    
        $template = ($type === 'besoin') ? "liste_besoins.html" : "liste_offres.html";
        $localites = Localite::getAllLocalites();
        foreach ($localites as &$loc) {
            $loc['isSelected'] = ($ville && $ville === $loc['nom']);
        }
        
        $data['localites'] = $localites;
        
        $this->renderPage('listings', $template, $data);
    } 

    function viewAnnonce($id) {
        $annonce = Annonce::getAnnonceById($id);
        $media = Media::getMediaByAnnonceId($id);
        $categorie = Categories::getCategoryById($annonce['categoriesId']);
        $localite = Localite::getById($annonce['localiteId']);
    
        if (!$annonce) {
            return $this->renderPage('pages', '404.html');
        }
        foreach ($media as &$m) {
            $type = $m['fileType'] ?? '';
            $m['url'] = $m['fileUrl']; // ✅ needed for Mustache
            $m['isImage'] = strpos($type, 'image/') === 0;
            $m['isVideo'] = strpos($type, 'video/') === 0;
            $m['isPDF']   = $type === 'application/pdf';
        }  
        $data = [
            'annonce' => $annonce,
            'media' => $media,
            'categorie' => $categorie['nom'] ?? '',
            'localite' => $localite,
            'title' => $annonce['titre'],
        ];
              
        
        $this->renderPage('pages', 'annonce_detail.html', $data);
    }
        
    

    // Deletes an ad.
    function deleteAnnonce($id) {
        // Fetch media associated with the annonce
        $mediaList = Media::getMediaByAnnonceId($id);
    
        // Delete physical files
        foreach ($mediaList as $media) {
            $filePath = $media['filePath'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    
        // Delete media entries from DB (if not already handled by foreign key ON DELETE CASCADE)
        Media::deleteMediaByAnnonceId($id); // Optional if your DB handles it
    
        // Delete the annonce itself
        Annonce::deleteAnnonce($id);
    
        // Redirect or render confirmation
        header("Location: " . SERVER_ABSOLUTE_PATH . "/liste_offres");
        exit;
    }
    

    function render() {
        $this->getAllAnnonces();
    }
}
?>
