<?php
	// Base de données
    define("DBNAME", "projet-babillard");    //Nom de la bd
    define("HOST", "localhost");      //Le serveur
    define("USERNAME", "root");  //Le nom de l'utilisateur
    define("PASSWORD", "");  //Le mot de passe de l'utilisateur

    //Nom du dossier qui contient le code source (repository/, public/, sass/, js/ etc.)
    define("SERVER_ROOT_DIRECTORY", "");

    //Avec cette constante, on peut déterminer à partir de quel endroit on effectue le routage. Le routage se fait
    //toujours en commençant par le SERVER_ROOT_DIRECTORY
    define("SERVER_PROJECT_PATH", "");

    //Cette constante est appelée lorsque l'on tente d'accéder à une url.
    define("SERVER_ABSOLUTE_PATH", "http://localhost:80/projet");

    //Cette constante est appelée lorsqu'on veut charger une ressource publique, tel qu'une image, un fichier css etc.
    define("PUBLIC_ABSOLUTE_PATH", "http://localhost:80/projet/public");
	
    //Cette constante est appelée lorsqu'on veut charger un fichier html public.
    define("HTML_PUBLIC", "http://localhost:80/projet/public/html/public");

    //Cette constante est appelée lorsqu'on veut charger un fichier html admin.
    define("HTML_ADMIN", "http://localhost:80/projet/public/html/admin");

    define("HOME_PAGE", 'public/html/accueil.html');
    
    define("ADMIN_HEADER",'location:'. SERVER_ABSOLUTE_PATH .'/admin');

    define("HOME_HEADER",'location:'. SERVER_ABSOLUTE_PATH . '/accueil');
        
    define("NO_IMG", PUBLIC_ABSOLUTE_PATH . '/assets/no_image.jpg');

    define("ERREUR", PUBLIC_ABSOLUTE_PATH . '/html/public/404.html');

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
    define('ERR_PROVINCE_REQUIRED', 'La province est obligatoire.');
    define('ERR_POSTAL_REQUIRED', 'Le code postal est obligatoire.');