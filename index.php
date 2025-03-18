<?php
	ini_set('display_errors', 1);
    error_reporting(0);
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

	// "mot dans url => nom controller"
    $routes = array
    (
        "accueil" => "accueil",
        "admin" => "admin",

    );  
   
    // Ajout d'une barre oblique à la fin pour ne pas avoir de dépassement de tableau dans les traitements futurs
    $path = strtolower($_SERVER["REQUEST_URI"]);
    $i = INCREMENT;
    
    if(substr($path, -1) != "/")
        $path .= "/";
    
    //Obtient path
    $path = explode("/",$path);

    if($path[1 + $i] == "") 
        $path[1 + $i] = "accueil";

    $obj_controller = new controller();

    //Détermine si path valide
    if(array_key_exists($path[1 + $i],$routes))
    {
        include "controller/" . strtolower($routes[$path[1 + $i]]) . ".php";
       
        $controller = new $routes[$path[1 + $i]]; //Instanciation du contrôleur routé           

        // Si aucune méthode n'est spécifiée, on appelle la fonction render() du controlleur
        $method = ((count($path) == (3 + $i)) && ($path[2 + $i] == "")) || (count($path) == (2 + $i)) ? $method = "render" : $method = str_replace("-", "", $path[2 + $i]);


        if( (stristr($path[1 + $i], 'admin')  && $method != "connexion")) 
        {

           if(!isset($_SESSION["admin"]) || ($_SESSION["admin"] < 1)) //Vérifier si l'administrateur a déjà été connecté
           {
                $method = "ouvrirsession";
                $controller->$method(); 
           }
           else
           {
                if( isset($path[2 + $i]) && ( ($path[2 + $i] == "")  ) and (get_class($controller) == "admin"))
                {
                     $param1 = $path[3 + $i];
                     $controller->$method($param1);
                }
                else if(method_exists($controller,$method)) //si la méthode appelée existe
                    $controller->$method();
                else
                {
                    // La méthode appelée est non-valide. Fait afficher la page d'accueil
                   include "controller/accueil.php";
                   $controller = new accueil();
                   $controller->renderTemplate(file_get_contents("public/html/public/accueil.html"));                      
                }

           }
        }
        else if(method_exists($controller,$method)) //si la méthode appelée existe
        {
            $controller->$method();
        } 
        else
        {
            $obj_controller->renderTemplate(file_get_contents(ERREUR));             
        }
    }
    else
    {
        $obj_controller->renderTemplate(file_get_contents(ERREUR));             
    }
    