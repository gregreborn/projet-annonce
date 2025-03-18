<?php
require_once __DIR__ . "/config.php";
require_once "db.php";
use repository\db as db;

//Test database connection
try{
    $query = "SELECT DATABASE() AS db_name";
    $result = db::queryFirst($query);
    if($result){
        echo "Connection to database successful. Database name: ".$result['db_name'];
    }
    else{
        echo "Connection to database failed.";
    }
} catch (PDOException $e) {
    echo "Connection to database failed. Error: ".$e->getMessage();
}

?>