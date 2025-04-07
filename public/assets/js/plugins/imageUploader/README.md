Below is an updated version of your README that reflects the changes made to support the AJAX upload endpoint and related configuration:

---

# ImageUploader Plugin

ImageUploader est un plugin autonome et modulable qui permet aux utilisateurs de télécharger, recadrer et sélectionner une miniature (thumbnail) parmi jusqu'à 4 images. Il utilise CropperJS pour le recadrage et est conçu pour être intégré facilement dans votre site web (notamment dans vos formulaires) tout en restant compatible avec Foundation 5.5.1.

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

- **Finalisation & AJAX Upload**  
  Un bouton "Enregistrer les modifications" finalise la sélection des images et, via AJAX, envoie chaque image traitée à un point de terminaison configurable. Ce point de terminaison enregistre les fichiers sur le serveur et renvoie les URLs permanentes, qui sont ensuite placées dans un champ caché pour l'intégration côté serveur.

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

2. **Placez** le fichier `upload_endpoint.php` (fourni avec le plugin) dans un dossier accessible publiquement. Par exemple, dans `public/endpoints/upload_endpoint.php`.  
   **Note :** Ce script gère l'enregistrement des images traitées via AJAX dans le dossier `public/uploads`.

3. **Incluez** les liens vers les fichiers CSS et JS de vos dépendances dans votre fichier HTML, par exemple dans `<head>` :

   ```html
   <!-- Foundation CSS -->
   <link rel="stylesheet" href="assets/libs/foundation/css/foundation.min.css">
   <!-- CropperJS CSS -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
   <!-- CSS du plugin -->
   <link rel="stylesheet" href="assets/js/plugins/imageUploader/imageUploader.css">
   ```

4. **Ajoutez** les scripts en fin de `<body>` :

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

5. **Initialisez** Foundation et le plugin en configurant l’endpoint d’upload :
   
   Si vous utilisez un moteur de templates comme Mustache, par exemple dans votre `base.mustache`, vous pouvez ajouter :

   ```html
   <script>
     document.addEventListener("DOMContentLoaded", function () {
       if (typeof ImageUploader !== 'undefined') {
         ImageUploader.init({
           uploadEndpoint: '{{PUBLIC_ABSOLUTE_PATH}}/endpoints/upload_endpoint.php'
         });
         var openUploaderBtn = document.getElementById('open-uploader');
         if (openUploaderBtn) {
           openUploaderBtn.addEventListener('click', function () {
             $('#image-uploader-modal').foundation('reveal', 'open');
           });
         }
       }
     });
   </script>
   ```

   Ceci permet de configurer dynamiquement l’URL de l’endpoint en utilisant une variable Mustache, ce qui rend le plugin réutilisable sur toute votre application.

---

## Utilisation

Votre fichier HTML n'a besoin d'inclure qu'un simple bouton pour ouvrir le plugin. Le plugin injecte automatiquement le HTML de la modale dans le `<body>` et utilise AJAX pour enregistrer les images traitées. Par exemple :

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
  
  <!-- Le champ caché qui recevra le JSON final avec les infos des images -->
  <input type="hidden" name="uploadedImages" id="uploadedImages">
  
  <!-- Les scripts seront inclus en bas du body -->
  <script src="assets/libs/jquery.min.js"></script>
  <script src="assets/libs/foundation/js/foundation.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
  <script src="assets/js/plugins/imageUploader/imageUploader.js"></script>
  <script>
    $(document).foundation();
    ImageUploader.init({
      uploadEndpoint: '{{PUBLIC_ABSOLUTE_PATH}}/endpoints/upload_endpoint.php'
    });
    
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
  Vous pouvez ajuster les paramètres (nombre maximum d'images, dimensions, etc.) directement dans le code JS du plugin. Par ailleurs, le point d'upload AJAX est configurable via l'option `uploadEndpoint`.

---

## Intégration côté serveur

Le plugin gère uniquement la partie client (sélection, recadrage et finalisation des images). Lors de la soumission du formulaire, le plugin place dans le champ caché `uploadedImages` un JSON contenant un tableau d'objets pour chaque image. Chaque objet inclut les clés suivantes :

- **filePath** : Chemin du fichier sur le serveur (retourné par l’endpoint).
- **fileUrl** : URL publique de l'image (retournée par l’endpoint).
- **fileType** : Type MIME de l'image.
- **isThumbnail** : Indique si l'image est la miniature sélectionnée (défini par le client).

Sur le serveur, dans votre contrôleur (par exemple, `annonceController.php`), vous décoderez ce JSON et enregistrerez chaque image dans votre base de données via votre modèle Media. Vous pouvez ainsi lier ces images à l'annonce correspondante.

Le fichier `upload_endpoint.php` fourni s'occupe de :
- Recevoir le fichier via AJAX (dans le champ `file`).
- Valider le type de fichier.
- Enregistrer le fichier dans le dossier `public/uploads` (ou le dossier de votre choix).
- Retourner un JSON contenant `filePath`, `fileUrl` et `fileType`.

---

## Conclusion

ImageUploader offre une solution moderne, responsive et autonome pour l'upload, le recadrage et la sélection de miniatures d'images, avec prise en charge d'un endpoint AJAX configurable pour l'enregistrement des fichiers. En suivant ces instructions, vous pouvez intégrer facilement le plugin dans vos projets. Pour toute question ou contribution, veuillez consulter la documentation ou contacter l'auteur.

---

## Auteur

Gregory Ronald St Facile  
tel: 514-224-3490  
email: gregorystfa023@gmail.com

