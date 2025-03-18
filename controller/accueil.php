<?php

//Cette classe renferme les fonctions de la classe Accueil
class accueil extends Controller
{
	//Affichage de la page d'accueil
	function render()
	{
        $this->renderTemplate(file_get_contents(HTML_PUBLIC . "/accueil.html"), $data);
	}	
}