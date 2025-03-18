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
