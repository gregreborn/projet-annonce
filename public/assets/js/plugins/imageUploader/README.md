# ImageUploader Plugin

ImageUploader est un plugin autonome et modulable qui permet aux utilisateurs de télécharger, recadrer et sélectionner une miniature (thumbnail) parmi jusqu'à 4 images. Il utilise CropperJS pour le recadrage et est conçu pour être intégré facilement dans votre siteweb, notamment dans vos formulaires, tout en restant compatible avec Foundation 5.5.1.

---

## Fonctionnalités

- **Téléchargement d'images**  
  Possibilité de télécharger des images via un champ `<input type="file">` ou par glisser-déposer.

- **Recadrage interactif**  
  Intégration de CropperJS permettant de recadrer chaque image.  
  - **Recadrage libre** pour les images normales.  
  - **Recadrage forcé en 1:1** pour l'image sélectionnée comme miniature.

- **Sélection de la miniature**  
  Chaque image affiche un bouton "★" permettant de la définir comme miniature. La tuile de l'image miniature est mise en évidence (bordure dorée) et le recadrage pour cette image est automatiquement limité au format 1:1.

- **Interface responsive**  
  - Sur PC, la grille affiche toujours 4 tuiles (2x2).  
  - Sur tablettes et mobiles, la grille s'affiche progressivement (initialement une seule tuile, puis deux, etc.), ce qui améliore l’ergonomie sur petit écran.

- **Injection dynamique du HTML**  
  Le plugin injecte automatiquement le code HTML nécessaire (la modale, le conteneur d'upload, etc.) dans la page, rendant son intégration « plug and play ».

- **Finalisation**  
  Un bouton "Enregistrer les modifications" permet de finaliser la sélection et de récupérer les données (les URLs des images traitées et l'indice de la miniature) pour l'intégration côté serveur.

---

## Prérequis

Pour utiliser ImageUploader, vous devez inclure dans votre projet les dépendances suivantes :

- **jQuery** (par exemple, `jquery.min.js`)
- **Foundation 5.5.1**  
  - CSS : `foundation.min.css`
  - JS : `foundation.min.js`
- **CropperJS**  
  - CSS : `cropper.min.css` (ou via CDN)
  - JS : `cropper.min.js` (ou via CDN)

---

## Installation

1. **Téléchargez** les fichiers suivants dans votre projet :
   - `imageUploader.js`
   - `imageUploader.css`

2. **Incluez** les liens vers les fichiers CSS et JS de vos dépendances dans votre fichier HTML, par exemple dans `<head>` :

   ```html
   <!-- Foundation CSS -->
   <link rel="stylesheet" href="assets/libs/foundation/css/foundation.min.css">
   <!-- CropperJS CSS -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
   <!-- CSS du plugin -->
   <link rel="stylesheet" href="assets/js/plugins/imageUploader/imageUploader.css">
   ```

3. **Ajoutez** les scripts en fin de `<body>` :

   ```html
   <!-- jQuery -->
   <script src="assets/libs/jquery.min.js"></script>
   <!-- Foundation JS -->
   <script src="assets/libs/foundation/js/foundation.min.js"></script>
   <!-- CropperJS -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
   <!-- JS du plugin -->
   <script src="assets/js/plugins/imageUploader/imageUploader.js"></script>
   ```

4. **Initialisez** Foundation et le plugin :

   ```html
   <script>
     $(document).foundation();
     ImageUploader.init();
   </script>
   ```

---

## Utilisation

Votre fichier HTML n'a besoin d'inclure qu'un simple bouton pour ouvrir le plugin. Le plugin injecte automatiquement le HTML de la modale dans le `<body>`. Par exemple :

```html
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mon Formulaire avec ImageUploader</title>
  <!-- Inclure les CSS comme indiqué ci-dessus -->
</head>
<body>
  <!-- Bouton pour ouvrir le plugin -->
  <button id="open-uploader" class="button">Ouvrir ImageUploader</button>
  
  <!-- Les scripts seront inclus en bas du body -->
  <script src="assets/libs/jquery.min.js"></script>
  <script src="assets/libs/foundation/js/foundation.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
  <script src="assets/js/plugins/imageUploader/imageUploader.js"></script>
  <script>
    $(document).foundation();
    ImageUploader.init();
    
    document.getElementById('open-uploader').addEventListener('click', function() {
      $('#image-uploader-modal').foundation('reveal', 'open');
    });
  </script>
</body>
</html>
```

---

## Personnalisation

- **CSS**  
  Vous pouvez modifier `imageUploader.css` pour adapter les couleurs, la typographie et la disposition à votre charte graphique.

- **Options JavaScript**  
  Vous pouvez ajuster les paramètres (nombre maximum d'images, dimensions, etc.) directement dans le code JS du plugin.

---

## Intégration côté serveur

Le plugin ne gère que la partie client (sélection, recadrage, et finalisation des images). Lors de la soumission du formulaire, vous devez récupérer les données finalisées (par exemple, via un champ caché ou un événement personnalisé) et les envoyer au serveur pour être enregistrées (stockage des fichiers et mise à jour des URL dans la base de données).

---

## Conclusion

ImageUploader offre une solution moderne, responsive et autonome pour l'upload et le recadrage d'images, avec sélection de miniature. En suivant ces instructions, vous pouvez intégrer facilement le plugin dans vos projets. Pour toute question ou contribution, veuillez consulter la documentation ou contacter l'auteur.
```

---

## Auteur

Gregory Ronald St Facile
tel: 514-224-3490
email: gregorystfa023@gmail.com


