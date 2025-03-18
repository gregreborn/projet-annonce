<?php
use \model\comptes as comptes;
use \model\model as model;

//Cette classe renferme les fonctions de la classe Admin
class admin extends Controller
{
	//Affichage de la page d'accueil de l'administration
	function render()
	{
        $this->renderTemplateAdmin(file_get_contents(HTML_ADMIN . "/accueil.html"), $data);
	}

	//Affichage de la page de connexion de l'administration
	function ouvrirsession()
	{		
		$this->renderTemplateAdmin(file_get_contents(HTML_ADMIN . "/ouvrirsession.html"));
	}
	
	//Se connecter à l'administration
	function connexion()
	{
		/*
		$prefix = "$2a$07$"; //préfixe du mot de passe
			$saltreset =  $this->genererCode(16) .  $this->genererCode(16); //encryption
			$salt = $prefix  . $saltreset . "$"; //clé d'encryption
			
			if (CRYPT_BLOWFISH == 1) 
				$password =  crypt("cult!ure_360$", $salt);//mot de passe encrypté
						
			model::add("INSERT INTO comptes (courriel, password , crypt, niveau, nom, prenom, organisme) VALUES(?,?,?,?,?,?,?)", array('ngirard@rcrcq.ca',$password, $salt, "1", "Myriam", "Lortie", "Culture Mauricie" )); //id du membe généré
			die();
		*/
		//$admin = comptes::getByCourriel($_POST["courriel"]); //tableau des données de l'administrateur

		if($admin["niveau"] == 1)
		{

			if($admin["courriel"] == $_POST["courriel"])
			{
				if(trim($admin["password"]) == trim(crypt($_POST["password"], $admin["crypt"])))
				{
					$_SESSION["admin"] = $admin["id"];	

					header('Location: ' . SERVER_ABSOLUTE_PATH . "/admin");			
				}
				else 
				{
					$_SESSION["rcrcq_message"] = "Mot de passe invalide pour cet administrateur.";
					$_SESSION["rcrcq_erreur"] = 1;

					header('Location: ' . SERVER_ABSOLUTE_PATH . "/admin/ouvrirsession");		
				}				
			}
			else 
			{
				$_SESSION["rcrcq_message"] = "Mot de passe invalide pour cet administrateur.";
				$_SESSION["rcrcq_erreur"] = 1;
				
				header('Location: ' . SERVER_ABSOLUTE_PATH . "/admin/ouvrirsession");		
			}
		}
		else
		{
			$_SESSION["rcrcq_message"] = "Vous n'avez pas accès à l'administration.";
			$_SESSION["rcrcq_erreur"] = 1;

			header('Location: ' . SERVER_ABSOLUTE_PATH . "/admin/ouvrirsession");		
		}	
	}

	//Se déconnecter de l'admin
	function deconnexion()
	{
		$_SESSION["rcrcq_admin"] = "0";

		unset($_SESSION["rcrcq_admin"]); 

		header('Location: ' . SERVER_ABSOLUTE_PATH . '/admin');
	}
}