(function (window, document, Mustache) {
  'use strict';

  // Global FileExplorer object
  var FileExplorer = {
    config: {
      endpoint: null,      // Absolute URL to explorer_endpoint.php
      templateBase: null,  // Absolute URL to templates directory (with trailing slash)
      container: null,     // CSS selector for main container
      rootFolderId: null   // ID of the "root" folder (null or number)
    },
    templates: {
      tree: '',
      grid: '',
      list:        '',
      breadcrumbs: ''
    },

    isListView: false,   // par défaut : mode « Grille »
    lastFolders: [],     // contiendra data.folders après chaque loadFolder
    lastFiles: [],       // contiendra data.files après chaque loadFolder

    init: function (options) {
      // Merge user config (must include endpoint and templateBase)
      this.config = Object.assign(this.config, options);

      if (!this.config.endpoint || !this.config.templateBase) {
        console.error('FileExplorer: endpoint and templateBase are required.');
        return;
      }

      // Load Mustache templates and start
      this.loadTemplates();
    },

    loadTemplates: function () {
    var self = this;
    var basePath = this.config.templateBase;

    Promise.all([
      fetch(basePath + 'folderNodeList.mustache').then(r=>r.text()),
      fetch(basePath + 'fileGrid.mustache').then(r=>r.text()),
      fetch(basePath + 'fileList.mustache').then(r=>r.text()), 
      fetch(basePath + 'breadcrumbs.mustache').then(r=>r.text())
    ])
    .then(function (tpls) {
      self.templates.tree        = tpls[0];
      self.templates.grid        = tpls[1];
      self.templates.list        = tpls[2]; 
      self.templates.breadcrumbs = tpls[3];

      // 1) on construit l’UI
      self.buildSkeleton();

      // 2) on bind aussitôt les toggles de vue
      self.bindViewToggle();

      // 3) on charge les données
      self.loadTree();
      self.loadFolder(self.config.rootFolderId);

      // 4) puis tous les autres events (drag/drop, mkdir, upload…)
      self.bindEvents();
    })
    .catch(function (err) {
      console.error('FileExplorer: Error loading templates:', err);
    });
  },

buildSkeleton: function () {
  var container = document.querySelector(this.config.container);
  container.innerHTML = `
    <div class="row">
      <!-- Arborescence -->
      <div class="large-3 columns" id="fe-tree"></div>

      <!-- Zone principale -->
      <div class="large-9 columns">

        <!-- Breadcrumbs -->
        <nav class="breadcrumbs" id="fe-breadcrumbs"></nav>

        <!-- Toolbar -->
        <div id="fe-toolbar" class="grid-x grid-padding-x align-middle">
          <!-- Grille / Liste -->
          <div class="cell auto button-group">
            <button id="fe-view-grid" class="button primary">Grille</button>
            <button id="fe-view-list" class="button secondary hollow">Liste</button>
          </div>

          <!-- hidden file inputs -->
          <input type="file" id="fe-file-input" style="display:none">
          <input type="file" id="fe-folder-input" webkitdirectory directory style="display:none">

          <!-- Actions CRUD (updated labels) -->
          <div class="cell auto button-group">
            <button id="fe-new-folder"  class="button secondary">Nouveau dossier</button>
            <button id="fe-upload-file" class="button secondary">Ajouter un fichier</button>
            <button id="fe-upload-folder" class="button secondary">Ajouter un dossier</button>
          </div>

          <!-- Recherche -->
         <div class="cell large-4">
            <div class="input-group">
              <!-- input first… -->
              <input class="input-group-field" id="fe-search" type="search" placeholder="Rechercher…">
              <!-- …then the icon -->
              <span class="input-group-label">
                <i class="material-icons">search</i>
              </span>
            </div>
         </div>
        </div>

        <!-- your combined grid/list container -->
        <div id="fe-grid" class="row"></div>
      </div>
    </div>
  `;
},


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
        console.log('FileExplorer.loadTree data:', data);
        var html = Mustache.render(self.templates.tree, { folders: data.folders });
        var treeEl = document.getElementById('fe-tree');
        if (treeEl) treeEl.innerHTML = html;
      }).catch(function (err) {
        console.error('FileExplorer: loadTree error', err);
      });
    },

  loadFolder: function(folderId) {
    var self = this;
    this.fetchList(folderId).then(function(data) {
      // 1) Mémoriser les données reçues pour le toggle Grille/Liste
      self.lastFolders    = data.folders;
      self.lastFiles      = data.files;

      // 2) Mémoriser l’id courant
      self.currentFolderId = folderId;

      // 3) Mettre à jour les breadcrumbs
      self.renderBreadcrumbs(data.path);

      // 4) Afficher soit la grille soit la liste
      self.renderView();

      // 5) Surligner le dossier actif dans l’arborescence
      if (self.currentFolderId !== null) {
        document
          .querySelectorAll('#fe-tree .folder-node')
          .forEach(function(li) {
            var isActive = li.getAttribute('data-id') === String(self.currentFolderId);
            li.classList.toggle('active', isActive);
          });
      }
    }).catch(function(err) {
      console.error('FileExplorer: loadFolder error', err);
    });
  },



    renderBreadcrumbs: function (pathArray) {
      var html = Mustache.render(this.templates.breadcrumbs, { path: pathArray });
      var bc = document.getElementById('fe-breadcrumbs');
      if (bc) bc.innerHTML = html;
    },

    renderGrid: function (folders, files) {
      var html = Mustache.render(this.templates.grid, { folders: folders, files: files });
      var grid = document.getElementById('fe-grid');
      if (grid) grid.innerHTML = html;
    },

  bindViewToggle: function() {
      var container = document.querySelector(this.config.container);
      var btnGrid   = document.getElementById('fe-view-grid');
      var btnList   = document.getElementById('fe-view-list');

    btnGrid.addEventListener('click', ()=>{
      this.isListView = false;
      btnGrid.classList.add('primary');
      btnGrid.classList.remove('secondary','hollow');
      btnList.classList.add('secondary','hollow');
      btnList.classList.remove('primary');
      this.renderView();
    });

    btnList.addEventListener('click', ()=>{
      this.isListView = true;
      btnList.classList.add('primary');
      btnList.classList.remove('secondary','hollow');
      btnGrid.classList.add('secondary','hollow');
      btnGrid.classList.remove('primary');
      this.renderView();
    });
  },

renderView: function() {
  var gridEl = document.getElementById('fe-grid');

  if (this.isListView) {
    // drop the Foundation row so the table spans full width
    gridEl.classList.remove('row');
    gridEl.innerHTML = Mustache.render(this.templates.list, {
      folders: this.lastFolders,
      files:   this.lastFiles
    });
  } else {
    // make it a row again so each .columns inside fileGrid lines up
    gridEl.classList.add('row');
    gridEl.innerHTML = Mustache.render(this.templates.grid, {
      folders: this.lastFolders,
      files:   this.lastFiles
    });
  }
},


  bindEvents: function () {
    var self = this;
    var treeEl = document.getElementById('fe-tree');
    var gridEl = document.getElementById('fe-grid');
  
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
      const searchIcon = document.querySelector('#fe-toolbar .input-group-label');
      if (searchIcon) searchIcon.addEventListener('click', doSearch);
      searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') doSearch();
      });

      // 🔄 Breadcrumbs click
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
      // Expand/collapse tree
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
    
      // Double-click to navigate into folder
      gridEl.addEventListener('dblclick', function (e) {
        var item = e.target.closest('.file-item');
        if (!item) return;
        if (item.getAttribute('data-type') === 'folder') {
          self.loadFolder(item.getAttribute('data-id'));
        }
      });
    
      // New folder
      document.getElementById('fe-new-folder').addEventListener('click', function () {
        var name = prompt('Nom du nouveau dossier :');
        if (!name) return;
        var fd = new FormData();
        fd.append('action', 'mkdir');
        fd.append('parentId', self.currentFolderId || '');
        fd.append('name', name);
        fetch(self.config.endpoint, { method: 'POST', body: fd })
          .then(function (r) { return r.json(); })
          .then(function (data) {
            self.loadTree();
            self.loadFolder(self.currentFolderId);
          })
          .catch(function (err) {
            console.error('FileExplorer.mkdir error:', err);
          });
      });
    
      // Drag & drop upload
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
    
      // Upload file button
      const fileBtn   = document.getElementById('fe-upload-file');
      const fileInput = document.getElementById('fe-file-input');
    
      fileBtn.addEventListener('click', () => {
        if (!self.currentFolderId && self.config.rootFolderId === null) {
          return alert('Sélectionnez d’abord un dossier !');
        }
        fileInput.click();
      });
    
      const folderBtn   = document.getElementById('fe-upload-folder');
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
    
        const dirs = Array.from(dirSet)
          .filter(d => d)
          .sort((a,b) => a.split('/').length - b.split('/').length);
    
        const folderIdMap = { '': self.currentFolderId };
    
        for (let path of dirs) {
          const parts    = path.split('/');
          const name     = parts.pop();
          const parent   = parts.join('/');
          const parentId = folderIdMap[parent] || '';
    
          const form = new FormData();
          form.append('action', 'mkdir');
          form.append('parentId', parentId);
          form.append('name', name);
    
          const res  = await fetch(self.config.endpoint, { method: 'POST', body: form });
          const json = await res.json();
          if (!json.success) {
            console.error('mkdir failed for', path, json.error);
            alert(`Échec création dossier “${path}”: ${json.error}`);
            return;
          }
          folderIdMap[path] = json.id.toString();
        }
    
        for (let file of files) {
          const segments = file.webkitRelativePath.split('/');
          segments.pop(); // filename
          const dir = segments.join('/');
          const fid = folderIdMap[dir] || '';
    
          const up = new FormData();
          up.append('action', 'upload');
          up.append('folderId', fid);
          up.append('file', file);
    
          const r = await fetch(self.config.endpoint, { method: 'POST', body: up });
          const j = await r.json();
          if (!j.success) {
            console.error('upload failed for', file.name, j.error);
            alert(`Échec upload fichier “${file.name}”: ${j.error}`);
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
          fd.append('action',   'upload');
          fd.append('folderId', self.currentFolderId);
          fd.append('file',     file);
    
          fetch(self.config.endpoint, { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
              if (!data.success) {
                alert('Erreur upload : ' + (data.error || 'inconnue'));
              }
            })
            .catch(err => {
              console.error('Upload failed', err);
              alert('Erreur réseau lors de l’envoi');
            })
            .finally(() => {
              self.loadFolder(self.currentFolderId);
            });
        });
    
        e.target.value = '';
      });
    
      document.getElementById('fe-breadcrumbs').addEventListener('click', function (e) {
        var link = e.target.closest('a[data-id]');
        if (!link) return;
        e.preventDefault();
        var id = link.getAttribute('data-id');
        self.loadFolder(id);
      });
    
      document.body.addEventListener('click', function(e) {
        if (!e.target.classList.contains('fe-rename')) return;
        const item = e.target.closest('.file-item, .folder-node');
        const id   = item.getAttribute('data-id');
        const type = item.dataset.type || 'folder';
        const oldName = item.querySelector('.name').textContent;
        const newName = prompt(`Nouveau nom pour ce ${type}:`, oldName);
        if (!newName || newName === oldName) return;
        
        const fd = new FormData();
        fd.append('action',   'rename');
        fd.append('type',     type);
        fd.append('id',       id);
        fd.append('newName',  newName);
        
        fetch(self.config.endpoint, { method:'POST', body:fd })
          .then(r=>r.json())
          .then(data=>{
            if (data.success) {
              self.loadTree();
              self.loadFolder(self.currentFolderId);
            } else {
              alert('Rename failed: ' + (data.error||'unknown'));
            }
          });
      });
    
      document.body.addEventListener('click', function(e) {
        if (!e.target.classList.contains('fe-delete')) return;
        const item = e.target.closest('.file-item, .folder-node');
        const id   = item.getAttribute('data-id');
        const type = item.dataset.type || 'folder';
        if (!confirm(`Supprimer définitivement ce ${type}?`)) return;
        
        const fd = new FormData();
        fd.append('action', 'delete');
        fd.append('type',   type);
        fd.append('id',     id);
        
        fetch(self.config.endpoint, { method:'POST', body:fd })
          .then(r=>r.json())
          .then(data=>{
            if (data.success) {
              self.loadTree();
              if (type === 'folder' && id === String(self.currentFolderId)) {
                self.currentFolderId = null;
              }
              self.loadFolder(self.currentFolderId);
            } else {
              alert('Delete failed: ' + (data.error||'unknown'));
            }
          });
      });
    
      document.getElementById('fe-search').addEventListener('keypress', function(e) {
        if (e.key !== 'Enter') return;
        const q = e.target.value.trim();
        if (!q) return self.loadFolder(self.currentFolderId);
        
        fetch(self.config.endpoint + '?action=search&q=' + encodeURIComponent(q))
          .then(r=>r.json())
          .then(data=>{
            self.renderGrid([], data.results);
            document.getElementById('fe-breadcrumbs').innerHTML = `Résultats pour “${q}”`;
          });
      });
  }
    
  };

  // Expose to global
  window.FileExplorer = FileExplorer;

})(window, document, Mustache);