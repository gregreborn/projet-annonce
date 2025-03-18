<?php
require_once __DIR__ . "/config.php";
require_once "db.php";
use repository\db as db;

// List of required tables
$tables = ["annonces", "categories", "media"];

echo "<h3>Checking Database Tables</h3>";

foreach ($tables as $table) {
    try {
        $query = "SHOW TABLES LIKE ?";
        $result = Db::queryFirst($query, [$table]);

        if ($result) {
            echo "✅ Table `<b>$table</b>` exists!<br>";
        } else {
            echo "❌ Table `<b>$table</b>` does NOT exist!<br>";
        }
    } catch (PDOException $e) {
        echo "❌ Error checking table `<b>$table</b>`: " . $e->getMessage() . "<br>";
    }
}
?>