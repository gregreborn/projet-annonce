<?php
include 'libs/mustache/src/Mustache/Autoloader.php';
Mustache_Autoloader::register();
use \model\model as model;


/**
 * Classe parente de tous les contrôleurs
 * Fournit des méthodes génériques pour l'affichage et la gestion des vues, fichiers et messages
 */
class Controller
{
 	/**
     * Rend un template HTML à l’aide de Mustache (sans base)
     */
	function renderTemplate($_pageContent, $_data = []) {
        $_data["PUBLIC_ABSOLUTE_PATH"] = PUBLIC_ABSOLUTE_PATH;
        $_data["SERVER_ABSOLUTE_PATH"] = SERVER_ABSOLUTE_PATH;

        $_data["informations"] = self::getInformations();
        $message = self::getMessage();
        $_data["message"] = $message["message"] ?? "";
        $_data["erreur"] = $message["erreur"] ?? false;

        $partialsPath = str_replace("http://localhost/projet-annonce", __DIR__ . "/..", HTML_PUBLIC_PARTIALS_FS);

        if (!is_dir($partialsPath)) {
            die("❌ ERROR: Partials directory not found: " . $partialsPath);
        }

        $requiredPartials = ['entete', 'footer', 'message'];
        foreach ($requiredPartials as $partial) {
            $filePath = "$partialsPath/$partial.mustache";
            if (!file_exists($filePath) || !is_readable($filePath)) {
                die("❌ ERROR: Missing or unreadable partial file: " . $filePath);
            }
        }

        $mustache = new Mustache_Engine([
            'cache' => __DIR__ . '/../cache/mustache',
            'loader' => new Mustache_Loader_StringLoader(),
            'partials_loader' => new Mustache_Loader_FilesystemLoader($partialsPath, ['extension' => '.mustache'])
        ]);

        try {
            echo $mustache->render($_pageContent, $_data);
        } catch (Exception $e) {
            die("❌ ERROR Rendering Template: " . $e->getMessage());
        }
    }

	/**
     * Rend une page HTML intégrée dans le template de base
     */
	function renderWithBase($_content, $_data = [])
	{
		// Make sure to pass in the absolute path variables!
		$_data["PUBLIC_ABSOLUTE_PATH"] = PUBLIC_ABSOLUTE_PATH;
		$_data["SERVER_ABSOLUTE_PATH"] = SERVER_ABSOLUTE_PATH;
		
		$baseTemplatePath = HTML_PUBLIC_PARTIALS_FS . "/base.mustache";
		if (!file_exists($baseTemplatePath)) {
			die("❌ ERROR: Base template not found at: " . $baseTemplatePath);
		}
		$baseTemplate = file_get_contents($baseTemplatePath);

        // Définit un titre par défaut si aucun n’est fourni
		if (!isset($_data['title'])) {$_data['title'] = "Mon Site";}
		$_data['content'] = $_content;
		$_data['currentYear'] = date('Y');

		$partialsPath = HTML_PUBLIC_PARTIALS_FS;
		if (!is_dir($partialsPath)) {
			die("❌ ERROR: Partials directory not found: " . $partialsPath);
		}

		$mustache = new Mustache_Engine([
			'cache' => __DIR__ . '/../cache/mustache',
			'loader' => new Mustache_Loader_StringLoader(),
			'partials_loader' => new Mustache_Loader_FilesystemLoader($partialsPath, ['extension' => '.mustache'])
		]);

		try {
			echo $mustache->render($baseTemplate, $_data);
		} catch (Exception $e) {
			die("❌ ERROR Rendering Base Template: " . $e->getMessage());
		}
	}


    //Afficher la page demandée pour l'Admin
	//$_pageContent : la page à templater pour Moustache
	//$_data : tableau de données à passer à la vue
    function renderTemplateAdmin($_pageContent, $_data = null)
    {			
		// Ajout des constantes de chemin absolu
        $_data["PUBLIC_ABSOLUTE_PATH"] = PUBLIC_ABSOLUTE_PATH;
        $_data["SERVER_ABSOLUTE_PATH"] = SERVER_ABSOLUTE_PATH;

        $mustache = new Mustache_Engine();		
		$_data["message"] = self::getMessage();
		$_data["informations"] = self::getInformations();

		$_data["publicMessage"] = $mustache->render(file_get_contents(HTML_PUBLIC_BASE . "/message.html"),$_data);
		$_data["adminEntete"] = $mustache->render(file_get_contents(HTML_ADMIN . "/entete.html"),$_data);
		$_data["adminMenu"] = $mustache->render(file_get_contents(HTML_ADMIN . "/menu.html"),$_data);
		$_data["adminFooter"] = $mustache->render(file_get_contents(HTML_ADMIN . "/footer.html"),$_data);

        echo $mustache->render($_pageContent, $_data);
    }

	static function getMessage() {
        if(isset($_SESSION["rcrcq_message"])) {
            $message = [];
            $message["message"] = $_SESSION["rcrcq_message"];
            $message["erreur"] = isset($_SESSION["rcrcq_erreur"]) && ($_SESSION["rcrcq_erreur"] == 1);
            unset($_SESSION["rcrcq_message"]);
            if(isset($_SESSION["rcrcq_erreur"]))
                unset($_SESSION["rcrcq_erreur"]);
        } else {
            $message = 0;
        }
        return $message;
    }

    static function getInformations() {
        return isset($_SESSION["rcrcq_informations"]) ? $_SESSION["rcrcq_informations"] : 1;
    }
    //Générer un code
	//$_longueur : longueur du code
	function genererCode($_longueur = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"; //tableau des caractères
        $result = ""; //résultat
		
        for ($i = 0; $i < $_longueur; $i++) 
            $result .= $chars[rand(0, strlen($chars) - 1)];
        return $result;
    }

//Mettre à jour un fichier ou en ajouter un nouveau dans le serveur
	//$file : fichier uploadé
	//$cheminupload : chemin de destination du fichier
	//$filedelete : fichier à supprimer du serveur
	//$_indice : indice du fichier
	function setFile($file, $cheminupload,  $filedelete = -1, $_indice = -1, $_largeur = -1)
	{
		$fichier = null; //chemin du nouveau fichier
		
		if(isset($file) and ($file["name"] != '')) //si nouveau fichier
		{
			if($_indice != -1)
				$name_base = pathinfo($file['name'][$_indice], PATHINFO_BASENAME);//nom de base
			else
				$name_base = pathinfo($file['name'], PATHINFO_BASENAME);//nom de base

			$name_extension = pathinfo($name_base, PATHINFO_EXTENSION); //extension
								
			$name_base = str_replace(".". $name_extension, "", $name_base);

			$name_final = $name_base . "_" . date("YmdHms") . "." . $name_extension; //nom final		

			$upload = PUBLIC_ABSOLUTE_PATH . $cheminupload;  //dossier destination
			$fichier = $upload . $name_final;
			
			if($_indice != -1)
				$test = move_uploaded_file($file['tmp_name'][$_indice], "public" . $cheminupload . $name_final);
			else
				$test = move_uploaded_file($file['tmp_name'], "public" . $cheminupload . $name_final);
			
			
			if( ($_indice != -1) and ($file["name"][$_indice] == ''))
				$fichier = null;
			

			if( ($filedelete != -1) and ($fichier != null) and ($fichier != "") )
			{
				unlink(substr(str_replace(SERVER_ABSOLUTE_PATH, "", $filedelete ),1));
			}

    		if($_largeur != -1)
    			self::fct_redim_image($_largeur, 0, $_SERVER['DOCUMENT_ROOT'] . "/public" . $cheminupload, "",  $_SERVER['DOCUMENT_ROOT'] . "/public" . $cheminupload, $name_final);
		}
		
		return $fichier;
	}


	//Supprimer les fichiers pdf et les images du serveur
	//$_item : élément à supprimer
	function deleteFile($_item)
	{
		$filedelete = null;
		
		if(isset($_item["img"]) and ($_item["img"] != null)) //si l'image existe, la supprimer
			$filedelete = model::updateFichier($_item["img"]);	
		if(isset($_item["pdf"]) and ($_item["pdf"] != null) ) //si le fichier pdf existe, le supprimer
			$filedelete = model::updateFichier($_item["pdf"]);	
		if(isset($_item["lien"]) and ($_item["lien"] != null) ) //si le fichier pdf existe, le supprimer
			$filedelete = model::updateFichier($_item["lien"]);	

		if(isset($filedelete))
			unlink(substr(str_replace(SERVER_ABSOLUTE_PATH, "", $filedelete ),1));

	}
}