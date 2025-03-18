<?php
//Cette classe renferme les fonctions de la classe Erreur
class erreur extends Controller
{
	//Afficher la page d'erreur
	function render()
	{		
        $this->renderTemplate(file_get_contents(HTML_PUBLIC . "/404.html"));
	}
}