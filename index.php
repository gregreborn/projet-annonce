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
	
    spl_autoload_register();
    include "controller/controller.php";
    include "repository/config.php";

    session_start();

	  // Routes: URL => Controller@Method
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

    // Ensure trailing slash for consistency
    $path = strtolower($_SERVER["REQUEST_URI"]);
    if (substr($path, -1) != "/") {
        $path .= "/";
    }

    $projectFolder = "projet-annonce"; // Adjust if needed
    // Remove query string from URI (e.g., ?sort=alpha)
    $_SERVER["REQUEST_URI"] = strtok($_SERVER["REQUEST_URI"], '?');
    $path = explode("/", trim($_SERVER["REQUEST_URI"], "/"));

    if ($path[0] === $projectFolder) {
        // If there’s a second segment, include it in the route key
        $routeKey = isset($path[2]) ? $path[1] . '/' . $path[2] : ($path[1] ?? "accueil");
    } else {
        $routeKey = isset($path[1]) ? $path[0] . '/' . $path[1] : ($path[0] ?? "accueil");
    }

    // Check if route exists
    if (array_key_exists($routeKey, $routes)) {
        $controllerEntry = $routes[$routeKey];

        // Handle controllers and methods
        $parts = explode("@", $controllerEntry);
        $controllerName = $parts[0];
        $methodName = $parts[1] ?? "render"; // Default to render if not specified
        $param = $parts[2] ?? null; // Optional third parameter for filtering

        // Include controller file
        $controllerFile = "controller/" . strtolower($controllerName) . ".php";
        if (!file_exists($controllerFile)) {
            die("Controller file not found: $controllerFile");
        }
        include_once $controllerFile;

        // Instantiate the controller
        if (!class_exists($controllerName)) {
            die("Controller class not found: $controllerName");
        }
        $controller = new $controllerName();

        // Call the method if it exists
        if (method_exists($controller, $methodName)) {
            if ($param) {
                $controller->$methodName($param); // If there's a third param (like 'offre')
            } else {
                $controller->$methodName();
            }
        } else {
            die("Method '$methodName' does not exist in controller '$controllerName'");
        }
    } else {
        include "controller/accueil.php";
        $controller = new accueil();
        $controller->render();
    }