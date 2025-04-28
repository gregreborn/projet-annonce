# MediaUploader Plugin

Un plugin JavaScript/PHP pour gérer l'upload de médias (vidéo, documents, PDF) avec découpage en chunks et modal intégrée.

---

## 📦 Installation

1. **Copiez les fichiers** dans votre projet, par exemple sous `assets/js/plugins/mediaUploader/` :
   - `mediaUploader.js`
   - `mediaUploader.css`
   - `media_endpoint.php`
   - (optionnel) vos pages de test `plugin_test.html` ou `mediaUploader.html`

2. **Dépendances**
   - jQuery (compatible 1.11+)
   - Foundation Reveal (ou tout plugin modal équivalent)
   - Material Icons (pour les icônes)

3. **Incluez** dans votre page :
   ```html
   <!-- CSS -->
   <link rel="stylesheet" href="/chemin/vers/foundation.css">
   <link rel="stylesheet" href="/assets/js/plugins/mediaUploader/mediaUploader.css">
   <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

   <!-- JS en fin de `<body>` -->
   <script src="/assets/libs/jquery.min.js"></script>
   <script src="/assets/libs/foundation/js/foundation.min.js"></script>
   <script src="/assets/js/plugins/mediaUploader/mediaUploader.js"></script>
   ```

---

## ⚙️ Configuration

Le plugin expose une méthode d'initialisation :

```js
MediaUploader.init({
  uploadEndpoint: 'assets/js/plugins/mediaUploader/media_endpoint.php'
});
```

- **uploadEndpoint** (string) : URL vers votre script PHP de traitement (<code>media_endpoint.php</code> par défaut).

> **Astuce** : Si votre structure de dossier diffère, modifiez ce chemin ou placez `media_endpoint.php` à l’emplacement souhaité.

---

## 🔧 Personnalisation

- **Types acceptés**
  - Par défaut dans `mediaUploader.js`:<br>
    <code>video/mp4, video/webm, video/ogg, application/pdf, application/msword, ...</code>
  - Pour en ajouter ou retirer, modifiez le tableau <code>allowedTypes</code> dans la fonction <code>handleFiles()</code>.

- **Taille maximale / chunk size**
  - Constantes en tête de `<code>mediaUploader.js</code>` :
    ```js
    const MAX_FILE_SIZE = 15 * 1024 * 1024;
    const CHUNK_SIZE   = 15 * 1024 * 1024;
    ```
  - Ajustez selon vos besoins et votre configuration serveur.

- **Styles**
  - Modifiez les règles CSS dans `mediaUploader.css` pour correspondre à votre charte graphique.
  - Les classes principales :
    - <code>.media-dropzone</code>
    - <code>.media-tile</code>, <code>.media-progress-bar</code>
    - <code>.remove-media</code>

---

## 🚀 Utilisation

1. **Ouvrir la modale**
   ```js
   document.getElementById('open-uploader-btn').addEventListener('click', function() {
     $('#media-uploader-modal').foundation('reveal','open');
   });
   ```
2. **Sélection / drag & drop**
   - Cliquez sur le bouton `Sélectionner un fichier` ou faites glisser vos médias.
   - Le plugin génère une tuile avec icône, nom, barre de progression.

3. **Lancer l’upload**
   - Cliquez sur `Télécharger`.
   - En mode chunk, tous les morceaux sont envoyés puis réassemblés côté serveur.

4. **Suppression**
   - Cliquez sur la croix `<code>&times;</code>` sur une tuile pour supprimer le média (serveur et interface).

---

## 🔌 Endpoint PHP

Le fichier `media_endpoint.php` gère :

1. **Suppression** (`POST delete=true, fileId`)
2. **Finalisation** (`POST finalize=true, fileId, fileName`)
3. **Upload direct** (un seul chunk)
4. **Upload en chunks** (stockage temporaire, puis assemblage)

### Adapter à votre serveur

- **Chemins** : modifiez la variable `$publicDir` si votre dossier `public/` n’est pas à 4 niveaux.
- **URL publique** : la variable `$publicUrl` génère le lien HTTP vers `/uploads/`.

---

## 🛠 Tests & débogage

- Rechargez simplement votre page de test (`plugin_test.html`) après chaque modification JS/PHP.
- Ouvrez la console pour voir les logs `console.log('Upload...', ...)` ou erreurs Ajax.
- Vérifiez que le dossier `uploads/` et `uploads/temp/` ont les droits d’écriture.

---

## Auteur

Gregory Ronald St Facile  
tel: 514-224-3490  
email: gregorystfa023@gmail.com


