<?php
// Explorer Endpoint for FileExplorer Plugin
// Uses repository\Db for all DB operations

// DEBUG: temporarily show errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Load DB wrapper and config
require_once __DIR__ . '/../../../../../repository/config.php';
require_once __DIR__ . '/../../../../../repository/db.php';
use repository\Db;

$action = $_REQUEST['action'] ?? 'list';
try {
    switch ($action) {
        case 'list':   listAction();   break;
        case 'mkdir':  mkdirAction();  break;
        case 'upload': uploadAction(); break;
        case 'rename': renameAction(); break;
        case 'delete': deleteAction(); break;
        case 'move':   moveAction();   break;
        case 'search': searchAction(); break;
        default:
            echo json_encode(['error' => "Unknown action: $action"]);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
exit;

function listAction() {
    // Determine parent ID (null => root)
    $fid = isset($_GET['folderId']) && $_GET['folderId'] !== ''
         ? intval($_GET['folderId'])
         : null;

    // Breadcrumb path
    $path = buildPath($fid);

    // Query folders
    if ($fid === null) {
        $folders = Db::query("SELECT id,name FROM folders WHERE parent_id IS NULL ORDER BY name");
    } else {
        $folders = Db::query("SELECT id,name FROM folders WHERE parent_id = ? ORDER BY name", [$fid]);
    }

    // Query files
    $files = Db::query(
        "SELECT id, file_name AS name, file_path AS path, mime_type, size_bytes"
      . " FROM files WHERE folder_id = ? ORDER BY file_name",
        [$fid]
    );

    echo json_encode(['path'=>$path,'folders'=>$folders,'files'=>$files]);
}

function buildPath($fid) {
    $path = [];
    while ($fid) {
        $row = Db::queryFirst(
            "SELECT id,name,parent_id FROM folders WHERE id = ?", [$fid]
        );
        if (!$row) break;
        array_unshift($path, ['id'=>$row['id'],'name'=>$row['name']]);
        $fid = $row['parent_id'];
    }
    return $path;
}

function mkdirAction() {
    $parentId = isset($_POST['parentId']) && $_POST['parentId'] !== ''
              ? intval($_POST['parentId']) : null;
    $name = trim($_POST['name'] ?? '');
    if ($name === '') {
        echo json_encode(['success'=>false,'error'=>'Missing folder name']);
        return;
    }
    $newId = Db::createLastID(
        "INSERT INTO folders (name,parent_id) VALUES (?,?)",
        [$name, $parentId]
    );
    if ($newId < 0) {
        echo json_encode(['success'=>false,'error'=>'DB insert failed']);
        return;
    }
    // Mirror on disk
    $base = __DIR__ . '/../../../../../public/uploads/explorer';
    $dir  = "$base/$newId";
    if (!is_dir($dir)) mkdir($dir,0755,true);

    echo json_encode(['success'=>true,'id'=>$newId]);
}

function uploadAction() {
    if (empty($_POST['folderId']) || empty($_FILES['file'])) {
        echo json_encode(['success'=>false,'error'=>'Missing parameters']);
        return;
    }
    $fid = intval($_POST['folderId']);
    $f = $_FILES['file'];
    if ($f['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success'=>false,'error'=>'Upload error']);
        return;
    }
    $orig = $f['name'];
    $ext  = pathinfo($orig, PATHINFO_EXTENSION);
    $uniq = uniqid() . ($ext?".$ext":'');
    $destDir = __DIR__ . '/../../../../../public/uploads/explorer/' . $fid;
    if (!is_dir($destDir)) mkdir($destDir,0755,true);
    move_uploaded_file($f['tmp_name'],"$destDir/$uniq");

    $fileId = Db::createLastID(
        "INSERT INTO files (folder_id,file_name,file_path,mime_type,size_bytes)"
      . " VALUES (?,?,?,?,?)",
        [$fid,$orig,"uploads/explorer/$fid/$uniq",$f['type'],$f['size']]
    );
    if ($fileId < 0) {
        echo json_encode(['success'=>false,'error'=>'DB insert failed']);
        return;
    }
    echo json_encode([
      'success'=>true,
      'file'=>['id'=>$fileId,'name'=>$orig,'path'=>"uploads/explorer/$fid/$uniq",'mime'=>$f['type'],'size'=>$f['size']]
    ]);
}

function renameAction() {
    $type = $_POST['type'] ?? '';
    $id   = intval($_POST['id'] ?? 0);
    $new  = trim($_POST['newName'] ?? '');
    if (!$id || !$new || !in_array($type,['folder','file'])) {
        echo json_encode(['success'=>false,'error'=>'Bad parameters']);return;
    }
    $table = $type==='folder'?'folders':'files';
    $field = $type==='folder'?'name':'file_name';
    $count = Db::execute(
        "UPDATE $table SET $field = ? WHERE id = ?",
        [$new,$id]
    );
    echo json_encode(['success'=>$count>=0]);
}

function deleteAction() {
    $type = $_POST['type'] ?? '';
    $id   = intval($_POST['id'] ?? 0);
    if (!$id||!in_array($type,['folder','file'])) {
        echo json_encode(['success'=>false,'error'=>'Bad parameters']);return;
    }
    if ($type==='folder') {
        rrmdir(__DIR__.'/../../../../../public/uploads/explorer/'.$id);
        Db::execute("DELETE FROM folders WHERE id = ?",[$id]);
    } else {
        $row = Db::queryFirst("SELECT file_path FROM files WHERE id = ?",[$id]);
        @unlink(__DIR__.'/../../../../../public/'.$row['file_path']);
        Db::execute("DELETE FROM files WHERE id = ?",[$id]);
    }
    echo json_encode(['success'=>true]);
}

function moveAction() {
    $type = $_POST['type'] ?? '';
    $id   = intval($_POST['id'] ?? 0);
    $pid  = intval($_POST['newParentId'] ?? 0);
    if (!$id||!in_array($type,['folder','file'])) {
        echo json_encode(['success'=>false,'error'=>'Bad parameters']);return;
    }
    $field = $type==='folder'?'parent_id':'folder_id';
    Db::execute("UPDATE " . ($type==='folder'?'folders':'files') . " SET $field = ? WHERE id = ?",[$pid,$id]);
    echo json_encode(['success'=>true]);
}

function searchAction() {
    $q = '%' . ($_GET['q'] ?? '') . '%';
    $out = Db::query("SELECT id,name,'folder' AS type FROM folders WHERE name LIKE ?",[$q]);
    $files = Db::query("SELECT id,file_name AS name,'file' AS type FROM files WHERE file_name LIKE ?",[$q]);
    $out = array_merge($out,$files);
    echo json_encode(['results'=>$out]);
}

function rrmdir($dir) {
    if (!is_dir($dir)) return;
    foreach (scandir($dir) as $f) {
        if ($f==='.'||$f==='..') continue;
        is_dir("$dir/$f")?rrmdir("$dir/$f"):@unlink("$dir/$f");
    }
    @rmdir($dir);
}
