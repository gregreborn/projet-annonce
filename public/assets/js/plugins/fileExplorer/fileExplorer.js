(function (window, document, Mustache) {
  'use strict';

  /**********************************************
   * 1. Fonction utilitaire
   **********************************************/
  // Convertit une taille en octets en une chaîne lisible (ex. "1.5 MB")
  function formatSize(bytes) {
    if (bytes === 0) return '0 B';
    var k = 1024;
    var sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
    var i = Math.floor(Math.log(bytes) / Math.log(k));
    if (i >= sizes.length) i = sizes.length - 1;
    var size = bytes / Math.pow(k, i);
    var rounded = size < 10 ? size.toFixed(1) : Math.round(size);
    return rounded + ' ' + sizes[i];
  }

  /**********************************************
   * 2. Déclaration de l'objet FileExplorer
   **********************************************/
  var FileExplorer = {
    // Configuration de base
    config: {
      endpoint: null,      // URL de l'API
      templateBase: null,  // Chemin vers les templates Mustache (terminé par /)
      container: null,     // Sélecteur du conteneur de l'explorateur
      rootFolderId: null   // ID du dossier racine
    },
    // Templates à charger
    templates: {
      tree: '',
      grid: '',
      list: '',
      breadcrumbs: ''
    },
    // État de l'affichage
    isListView: true,
    lastFolders: [],
    lastFiles: [],

    /**********************************************
     * 3. Initialisation et chargement des templates
     **********************************************/
    init: function (options) {
      this.config = Object.assign(this.config, options);
      if (!this.config.endpoint || !this.config.templateBase) {
        console.error('FileExplorer : endpoint et templateBase sont requis.');
        return;
      }
      this.loadTemplates();
    },

    loadTemplates: function () {
      var self = this;
      var basePath = this.config.templateBase;
      Promise.all([
        fetch(basePath + 'folderNodeList.mustache').then(r => r.text()),
        fetch(basePath + 'fileGrid.mustache').then(r => r.text()),
        fetch(basePath + 'fileList.mustache').then(r => r.text()),
        fetch(basePath + 'breadcrumbs.mustache').then(r => r.text())
      ])
      .then(function (tpls) {
        self.templates.tree        = tpls[0];
        self.templates.grid        = tpls[1];
        self.templates.list        = tpls[2];
        self.templates.breadcrumbs = tpls[3];

        self.buildSkeleton();
        self.bindViewToggle();

        // Initialisation de l'affichage
        self.loadTree();
        self.loadFolder(self.config.rootFolderId);

        self.bindEvents();
      })
      .catch(function (err) {
        console.error('Erreur lors du chargement des templates :', err);
      });
    },

    /**********************************************
     * 4. Construction de l'interface utilisateur (UI)
     **********************************************/
    buildSkeleton: function () {
      var container = document.querySelector(this.config.container);
      container.innerHTML = `
        <div class="row">
          <!-- Arborescence -->
          <div class="large-3 columns" id="fe-tree"></div>

          <!-- Zone principale -->
          <div class="large-9 columns">
            <!-- Fil d'Ariane -->
            <nav class="breadcrumbs" id="fe-breadcrumbs"></nav>
            <!-- Barre d'outils -->
            <div id="fe-toolbar" class="grid-x grid-padding-x align-middle">
              <!-- Boutons grille / liste -->
              <div class="cell auto button-group">
                <button id="fe-view-grid" class="button primary">Grille</button>
                <button id="fe-view-list" class="button secondary hollow">Liste</button>
              </div>
              <!-- Inputs cachés pour upload -->
              <input type="file" id="fe-file-input" style="display:none">
              <input type="file" id="fe-folder-input" webkitdirectory directory style="display:none">
              <!-- Actions CRUD -->
              <div class="cell auto button-group">
                <button id="fe-new-folder" class="button secondary">Nouveau dossier</button>
                <button id="fe-upload-file" class="button secondary">Ajouter un fichier</button>
                <button id="fe-upload-folder" class="button secondary">Ajouter un dossier</button>
              </div>
              <!-- Recherche -->
              <div class="cell large-4">
                <div class="input-group">
                  <input class="input-group-field" id="fe-search" type="search" placeholder="Rechercher…">
                  <span class="input-group-label">
                    <i class="material-icons">search</i>
                  </span>
                </div>
              </div>
            </div>
            <!-- Zone d'affichage (grille ou liste) -->
            <div id="fe-grid" class="row"></div>
          </div>
        </div>
      `;
    },

    /**********************************************
     * 5. Chargement des données (arborescence et dossier)
     **********************************************/
    fetchList: function (folderId) {
      var url = this.config.endpoint + '?action=list';
      if (folderId !== null && folderId !== undefined) {
        url += '&folderId=' + encodeURIComponent(folderId);
      }
      console.log('FileExplorer.fetchList ->', url);
      return fetch(url).then(function (r) { return r.json(); });
    },

    loadTree: function () {
      var self = this;
      this.fetchList(null).then(function (data) {
        var html = Mustache.render(self.templates.tree, { folders: data.folders });
        var treeEl = document.getElementById('fe-tree');
        if (treeEl) treeEl.innerHTML = html;
      }).catch(function (err) {
        console.error('Erreur lors du chargement de l\'arborescence', err);
      });
    },

    loadFolder: function (folderId) {
      var self = this;
      this.fetchList(folderId).then(function (data) {
        // Conversion de la taille et génération du type d'affichage pour chaque fichier
        data.files.forEach(function(f) {
          f.size_bytes = formatSize(f.size_bytes);
          const mt = f.mime_type || '';
          if (mt.startsWith('image/')) {
            const subtype = mt.split('/')[1].toUpperCase();
            f.displayType = subtype + ' Image';
          }
          else if (mt.startsWith('video/')) {
            const subtype = mt.split('/')[1].toUpperCase();
            f.displayType = subtype + ' Video';
          }
          else if (mt === 'application/pdf') {
            f.displayType = 'PDF Document';
          }
          else if (mt === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
            f.displayType = 'Word Document';
          }
          else if (mt === 'application/vnd.openxmlformats-officedocument.presentationml.presentation') {
            f.displayType = 'PowerPoint Presentation';
          }
          else if (mt === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            f.displayType = 'Excel Spreadsheet';
          }
          else if (mt.startsWith('application/')) {
            const rawSub = mt.split('/')[1] || '';
            const pieces = rawSub.split(/[.\-]/).map(
              part => part.charAt(0).toUpperCase() + part.slice(1).toLowerCase()
            );
            f.displayType = pieces.join(' ') + ' File';
          }
          else {
            f.displayType = mt || '—';
          }
        });

        self.lastFolders = data.folders;
        self.lastFiles = data.files;
        self.currentFolderId = folderId;
        self.renderBreadcrumbs(data.path);
        self.renderView();

        // Surligner le dossier actif dans l'arborescence
        if (self.currentFolderId !== null) {
          document.querySelectorAll('#fe-tree .folder-node').forEach(function (li) {
            var isActive = li.getAttribute('data-id') === String(self.currentFolderId);
            li.classList.toggle('active', isActive);
          });
        }
      }).catch(function (err) {
        console.error('Erreur lors du chargement du dossier', err);
      });
    },

    renderBreadcrumbs: function (pathArray) {
      var html = Mustache.render(this.templates.breadcrumbs, { path: pathArray });
      var bc = document.getElementById('fe-breadcrumbs');
      if (bc) bc.innerHTML = html;
    },

    /**********************************************
     * 6. Affichage : Grille ou Liste
     **********************************************/
    renderGrid: function (folders, files) {
      var html = Mustache.render(this.templates.grid, { folders: folders, files: files });
      var grid = document.getElementById('fe-grid');
      if (grid) grid.innerHTML = html;
    },

    renderView: function () {
      var gridEl = document.getElementById('fe-grid');
      if (this.isListView) {
        gridEl.classList.remove('row');
        gridEl.innerHTML = Mustache.render(this.templates.list, {
          folders: this.lastFolders,
          files: this.lastFiles
        });
      } else {
        gridEl.classList.add('row');
        gridEl.innerHTML = Mustache.render(this.templates.grid, {
          folders: this.lastFolders,
          files: this.lastFiles
        });
      }
    },

    /**********************************************
     * 7. Bascule grille / liste
     **********************************************/
    bindViewToggle: function () {
      var btnGrid = document.getElementById('fe-view-grid');
      var btnList = document.getElementById('fe-view-list');

      btnGrid.addEventListener('click', () => {
        this.isListView = false;
        btnGrid.classList.add('primary');
        btnGrid.classList.remove('secondary','hollow');
        btnList.classList.add('secondary','hollow');
        btnList.classList.remove('primary');
        this.renderView();
      });

      btnList.addEventListener('click', () => {
        this.isListView = true;
        btnList.classList.add('primary');
        btnList.classList.remove('secondary','hollow');
        btnGrid.classList.add('secondary','hollow');
        btnGrid.classList.remove('primary');
        this.renderView();
      });
    },

    /**********************************************
     * 8. Gestion des événements divers
     **********************************************/
    bindEvents: function () {
      var self = this;
      var treeEl = document.getElementById('fe-tree');
      var gridEl = document.getElementById('fe-grid');

      // Recherche : bouton, icône et touche "Entrée"
      var searchBtn = document.querySelector('#fe-toolbar .search');
      var searchInput = document.getElementById('fe-search');
      function doSearch() {
        var q = searchInput.value.trim();
        if (!q) return self.loadFolder(self.currentFolderId);
        fetch(self.config.endpoint + '?action=search&q=' + encodeURIComponent(q))
          .then(r => r.json())
          .then(data => {
            self.renderGrid([], data.results);
            document.getElementById('fe-breadcrumbs').innerHTML = `Résultats pour “${q}”`;
          });
      }
      if (searchBtn) searchBtn.addEventListener('click', doSearch);
      var searchIcon = document.querySelector('#fe-toolbar .input-group-label');
      if (searchIcon) searchIcon.addEventListener('click', doSearch);
      searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') doSearch();
      });

      // Navigation dans le fil d'Ariane
      var bc = document.getElementById('fe-breadcrumbs');
      if (bc) {
        bc.addEventListener('click', function(e) {
          var link = e.target.closest('a[data-id]');
          if (!link) return;
          e.preventDefault();
          var raw = link.getAttribute('data-id');
          var fid = raw === '' ? null : raw;
          self.loadFolder(fid);
        });
      }

      // Expansion/repli de l'arborescence
      treeEl.addEventListener('click', function (e) {
        var toggle = e.target.closest('.toggle');
        if (!toggle) return;
        var li = toggle.closest('li.folder-node');
        var fid = li.getAttribute('data-id');
        if (li.classList.contains('expanded')) {
          li.classList.remove('expanded');
          var childUl = li.querySelector('ul');
          if (childUl) childUl.remove();
          toggle.querySelector('i').classList.replace('fi-minus', 'fi-plus');
        } else {
          li.classList.add('expanded');
          toggle.querySelector('i').classList.replace('fi-plus', 'fi-minus');
          self.fetchList(fid).then(function (data) {
            var html = Mustache.render(self.templates.tree, { folders: data.folders });
            li.insertAdjacentHTML('beforeend', html);
          });
        }
      });

      // Double-clic pour ouvrir un dossier
      gridEl.addEventListener('dblclick', function (e) {
        var item = e.target.closest('.file-item');
        if (!item) return;
        if (item.getAttribute('data-type') === 'folder') {
          self.loadFolder(item.getAttribute('data-id'));
        }
      });

      // Création d'un nouveau dossier
      document.getElementById('fe-new-folder').addEventListener('click', function () {
        var name = prompt('Nom du nouveau dossier :');
        if (!name) return;
        var fd = new FormData();
        fd.append('action', 'mkdir');
        fd.append('parentId', self.currentFolderId || '');
        fd.append('name', name);
        fetch(self.config.endpoint, { method: 'POST', body: fd })
          .then(r => r.json())
          .then(function (data) {
            self.loadTree();
            self.loadFolder(self.currentFolderId);
          })
          .catch(function (err) {
            console.error('Erreur lors de la création du dossier', err);
          });
      });

      // Upload par glisser-déposer
      gridEl.addEventListener('dragover', function (e) { e.preventDefault(); });
      gridEl.addEventListener('drop', function (e) {
        e.preventDefault();
        var files = e.dataTransfer.files;
        Array.from(files).forEach(function (file) {
          var fd = new FormData();
          fd.append('action', 'upload');
          fd.append('folderId', self.currentFolderId);
          fd.append('file', file);
          fetch(self.config.endpoint, { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
              self.loadFolder(self.currentFolderId);
            });
        });
      });

      // Bouton d'upload de fichier
      const fileBtn = document.getElementById('fe-upload-file');
      const fileInput = document.getElementById('fe-file-input');
      fileBtn.addEventListener('click', () => {
        if (!self.currentFolderId && self.config.rootFolderId === null) {
          return alert('Sélectionnez d’abord un dossier !');
        }
        fileInput.click();
      });

      // Bouton d'upload de dossier
      const folderBtn = document.getElementById('fe-upload-folder');
      const folderInput = document.getElementById('fe-folder-input');
      folderBtn.addEventListener('click', () => folderInput.click());

      folderInput.addEventListener('change', async function(e) {
        const files = Array.from(e.target.files);
        if (!files.length) return;
        const dirSet = new Set();
        files.forEach(f => {
          const segments = f.webkitRelativePath.split('/');
          segments.pop();
          dirSet.add(segments.join('/'));
        });
        const dirs = Array.from(dirSet).filter(d => d).sort((a,b) => a.split('/').length - b.split('/').length);
        const folderIdMap = { '': self.currentFolderId };
        for (let path of dirs) {
          const parts = path.split('/');
          const name = parts.pop();
          const parent = parts.join('/');
          const parentId = folderIdMap[parent] || '';
          const form = new FormData();
          form.append('action', 'mkdir');
          form.append('parentId', parentId);
          form.append('name', name);
          const res = await fetch(self.config.endpoint, { method: 'POST', body: form });
          const json = await res.json();
          if (!json.success) {
            console.error('Échec de la création pour', path, json.error);
            alert(`Échec création dossier “${path}” : ${json.error}`);
            return;
          }
          folderIdMap[path] = json.id.toString();
        }
        for (let file of files) {
          const segments = file.webkitRelativePath.split('/');
          segments.pop();
          const dir = segments.join('/');
          const fid = folderIdMap[dir] || '';
          const up = new FormData();
          up.append('action', 'upload');
          up.append('folderId', fid);
          up.append('file', file);
          const r = await fetch(self.config.endpoint, { method: 'POST', body: up });
          const j = await r.json();
          if (!j.success) {
            console.error('Échec d’upload pour', file.name, j.error);
            alert(`Échec upload fichier “${file.name}” : ${j.error}`);
          }
        }
        self.loadTree();
        self.loadFolder(self.currentFolderId);
        e.target.value = '';
      });
    
      fileInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        if (!files.length) return;
        files.forEach(file => {
          const fd = new FormData();
          fd.append('action', 'upload');
          fd.append('folderId', self.currentFolderId);
          fd.append('file', file);
          fetch(self.config.endpoint, { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
              if (!data.success) {
                alert('Erreur upload : ' + (data.error || 'inconnue'));
              }
            })
            .catch(err => {
              console.error('Erreur réseau lors de l’upload', err);
              alert('Erreur réseau lors de l’envoi');
            })
            .finally(() => {
              self.loadFolder(self.currentFolderId);
            });
        });
        e.target.value = '';
      });
    
      // Renommer un élément
      document.body.addEventListener('click', function(e) {
        if (!e.target.classList.contains('fe-rename')) return;
        const item = e.target.closest('.file-item, .folder-node');
        const id   = item.getAttribute('data-id');
        const type = item.dataset.type || 'folder';
        const oldName = item.querySelector('.name').textContent;
        const newName = prompt(`Nouveau nom pour ce ${type} :`, oldName);
        if (!newName || newName === oldName) return;
        const fd = new FormData();
        fd.append('action', 'rename');
        fd.append('type', type);
        fd.append('id', id);
        fd.append('newName', newName);
        fetch(self.config.endpoint, { method:'POST', body:fd })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              self.loadTree();
              self.loadFolder(self.currentFolderId);
            } else {
              alert('Échec du renommage : ' + (data.error || 'inconnu'));
            }
          });
      });
    
      // Supprimer un élément
      document.body.addEventListener('click', function(e) {
        if (!e.target.classList.contains('fe-delete')) return;
        const item = e.target.closest('.file-item, .folder-node');
        const id   = item.getAttribute('data-id');
        const type = item.dataset.type || 'folder';
        if (!confirm(`Supprimer définitivement ce ${type} ?`)) return;
        const fd = new FormData();
        fd.append('action', 'delete');
        fd.append('type', type);
        fd.append('id', id);
        fetch(self.config.endpoint, { method:'POST', body:fd })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              self.loadTree();
              if (type === 'folder' && id === String(self.currentFolderId)) {
                self.currentFolderId = null;
              }
              self.loadFolder(self.currentFolderId);
            } else {
              alert('Échec de la suppression : ' + (data.error || 'inconnue'));
            }
          });
      });
    
      // Recherche via la touche "Entrée"
      document.getElementById('fe-search').addEventListener('keypress', function(e) {
        if (e.key !== 'Enter') return;
        const q = e.target.value.trim();
        if (!q) return self.loadFolder(self.currentFolderId);
        fetch(self.config.endpoint + '?action=search&q=' + encodeURIComponent(q))
          .then(r => r.json())
          .then(data => {
            self.renderGrid([], data.results);
            document.getElementById('fe-breadcrumbs').innerHTML = `Résultats pour “${q}”`;
          });
      });
    }
    
  };

  /**********************************************
   * 9. Exposition de l'objet FileExplorer
   **********************************************/
  window.FileExplorer = FileExplorer;

})(window, document, Mustache);