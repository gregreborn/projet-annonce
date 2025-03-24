<?php
	// Base de données
    define("DBNAME", "projet-babillard");    //Nom de la bd
    define("HOST", "localhost");      //Le serveur
    define("USERNAME", "gregory");  //Le nom de l'utilisateur
    define("PASSWORD", "TYq1R!c4vm9BC(N0");  //Le mot de passe de l'utilisateur

    //Nom du dossier qui contient le code source (repository/, public/, sass/, js/ etc.)
    define("SERVER_ROOT_DIRECTORY", "");

    //Avec cette constante, on peut déterminer à partir de quel endroit on effectue le routage. Le routage se fait
    //toujours en commençant par le SERVER_ROOT_DIRECTORY
    define("SERVER_PROJECT_PATH", "");

    //Cette constante est appelée lorsque l'on tente d'accéder à une url.
    define("SERVER_ABSOLUTE_PATH", "http://localhost/projet-annonce");

    //Cette constante est appelée lorsqu'on veut charger une ressource publique, tel qu'une image, un fichier css etc.
    define("PUBLIC_ABSOLUTE_PATH", "http://localhost/projet-annonce/public");
    define("HTML_PUBLIC_BASE", "http://localhost/projet-annonce/public/templates/public");
    define("HTML_ADMIN", "http://localhost/projet-annonce/public/templates/admin");

    // Filesystem constants (for loading template files)
    define("HTML_PUBLIC_BASE_FS", $_SERVER['DOCUMENT_ROOT'] . "/projet-annonce/public/templates/public");
    define("HTML_ADMIN_FS", $_SERVER['DOCUMENT_ROOT'] . "/projet-annonce/public/templates/admin");

    // Subfolder filesystem constants for public templates
    define("HTML_PUBLIC_PAGES_FS", HTML_PUBLIC_BASE_FS . "/pages");
    define("HTML_PUBLIC_FORMS_FS", HTML_PUBLIC_BASE_FS . "/forms");
    define("HTML_PUBLIC_LISTINGS_FS", HTML_PUBLIC_BASE_FS . "/listings");
    define("HTML_PUBLIC_PARTIALS_FS", HTML_PUBLIC_BASE_FS . "/partials");
    define("HTML_PUBLIC_ERRORS_FS", HTML_PUBLIC_BASE_FS . "/errors");

    define("HOME_PAGE", "public/templates/public/pages/accueil.html");
    
    define("ADMIN_HEADER",'location:'. SERVER_ABSOLUTE_PATH .'/admin');

    define("HOME_HEADER",'location:'. SERVER_ABSOLUTE_PATH . '/accueil');
        
    
    define("NO_IMG", PUBLIC_ABSOLUTE_PATH . '/assets/images/no_image.jpg');

    define("ERREUR", PUBLIC_ABSOLUTE_PATH . '/templates/public/errors/404.html');

    define("INDICE_DEBUT", 0);
    define("INDICE_FIN", 23);
    define("INCREMENT", 1);

    //Constantes pour les messages d'erreurs
    define('ERR_ORGANISATION_REQUIRED', 'Le nom de l\'organisation est obligatoire.');
    define('ERR_LAST_NAME_REQUIRED', 'Le nom de famille est obligatoire.');
    define('ERR_FIRST_NAME_REQUIRED', 'Le prénom est obligatoire.');
    define('ERR_TITLE_REQUIRED', 'Le titre est obligatoire.');
    define('ERR_DESCRIPTION_REQUIRED', 'La description est obligatoire.');
    define('ERR_INVALID_EMAIL', 'Format d\'e-mail invalide.');
    define('ERR_PHONE_NUMERIC', 'Le numéro de téléphone doit être numérique.');
    define('ERR_INVALID_URL', 'URL du site web invalide.');
    define('ERR_CITY_REQUIRED', 'La ville est obligatoire.');
    define('ERR_POSTAL_REQUIRED', 'Le code postal est obligatoire.');
    define('ERR_CATEGORY_REQUIRED', 'Veuillez sélectionner une catégorie.');