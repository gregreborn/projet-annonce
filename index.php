<?php
	ini_set('display_errors', 1);
    error_reporting(E_ALL);
	libxml_use_internal_errors(false);

    /*
     * LIGNES DIRECTRICES POUR LES ROUTES ET LE SYSTÈME D'URL
     * 1. Dans l'url que l'usager verra, on voit : http://nomdedomaine.com/nom-page/nom-methode
     * 2. Les paramètres seront passés par POST et accédés lors de l'exécution de la méthode
     * 3. Chaque page web doit posséder son contrôleur afin de définir sa méthode render() pour l'afficher
     * 4. Chaque page web doit avoir son nom entré dans le tableau $routes ainsi que son contrôleur correspondant et la méthode
    */

	// Chargement automatique des classes
    spl_autoload_register();
    include "controller/controller.php";
    include "repository/config.php";

    session_start();

    /**
     * === DÉFINITION DES ROUTES ===
     * Format : "url" => "NomDuContrôleur@nomMéthode[@paramètreOptionnel]"
     */      
    $routes = array(
        "accueil"           => "accueil@render",
        "annonces"          => "annonceController@getAllAnnonces",
        "annonces/create"   => "annonceController@createAnnonce", 
        "annonces/update"   => "annonceController@updateAnnonce", 
        "annonces/delete"   => "annonceController@deleteAnnonce", 
        "admin"             => "admin@render",
        "choix_annonce"     => "annonceController@renderChoixAnnonce",
        "liste_offres"      => "annonceController@getAnnoncesByType@offre",
        "liste_besoins"     => "annonceController@getAnnoncesByType@besoin",
        "soumission_offre"  => "annonceController@renderFormOffre",
        "soumission_besoin" => "annonceController@renderFormBesoin",
    );

    // Traite l'URL pour extraire le chemin
    $path = strtolower($_SERVER["REQUEST_URI"]);
    if (substr($path, -1) != "/") {
        $path .= "/";
    }

    $projectFolder = "projet-annonce"; // Nom du dossier du projet
    $path = explode("/", trim($_SERVER["REQUEST_URI"], "/"));

    // Détermine la clé de route à partir de l’URL
    if ($path[0] === $projectFolder) {
        $routeKey = isset($path[2]) ? $path[1] . '/' . $path[2] : ($path[1] ?? "accueil");
    } else {
        $routeKey = isset($path[1]) ? $path[0] . '/' . $path[1] : ($path[0] ?? "accueil");
    }

    // === RÉSOLUTION DE LA ROUTE ===
    if (array_key_exists($routeKey, $routes)) {
        $controllerEntry = $routes[$routeKey];

        // Décomposition : contrôleur, méthode, paramètre (optionnel)
        $parts = explode("@", $controllerEntry);
        $controllerName = $parts[0];
        $methodName = $parts[1] ?? "render"; // Méthode par défaut
        $param = $parts[2] ?? null; // Paramètre optionnel

        // Chargement du fichier du contrôleur
        $controllerFile = "controller/" . strtolower($controllerName) . ".php";
        if (!file_exists($controllerFile)) {
            die("Fichier du contrôleur introuvable : $controllerFile");
        }
        include_once $controllerFile;

        // Instanciation du contrôleur
        if (!class_exists($controllerName)) {
            die("Classe du contrôleur introuvable : $controllerName");
        }
        $controller = new $controllerName();

        // Appel de la méthode
        if (method_exists($controller, $methodName)) {
            if ($param) {
                $controller->$methodName($param); 
            } else {
                $controller->$methodName();
            }
        } else {
            die("La méthode '$methodName' n'existe pas dans le contrôleur '$controllerName'");
        }
    } else {
        include "controller/accueil.php";
        $controller = new accueil();
        $controller->render();
    }