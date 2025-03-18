<?php
namespace model;
require_once "model.php";

class Annonce extends Model
{
    // Define the table name and columns to keep consistency
    protected static $table = "annonces";
    protected static $columns = [
        'nomOrganisme', 'nom', 'prenom', 'titre', 'description',
        'telephone', 'courriel', 'site', 'dateDeDebutPub', 'dateDeFinPub',
        'adresse', 'ville', 'province', 'codePostal', 'mrc'
    ];

    // ✅ Insert a new annonce
    public static function createAnnonce($data)
    {
        $cols = implode(", ", static::$columns);
        $placeholders = implode(", ", array_fill(0, count(static::$columns), "?"));
   
        $query = "INSERT INTO " . static::$table . " ($cols) VALUES ($placeholders)";
        // Ensure the data is in the same order as the columns
        $orderedData =[];
        foreach (static::$columns as $col) {
            $orderedData[] = $data[$col] ?? null;
        }
        return self::add($query, $orderedData);
    }

    // ✅ Retrieve all annonces
    public static function getAllAnnonces()
    {
        return self::gets("SELECT * FROM " . static::$table);
    }

    // ✅ Retrieve an annonce by ID
    public static function getAnnonceById($id)
    {
        return self::get("SELECT * FROM " . static::$table . " WHERE id = ?", [$id]);
    }

    // ✅ Update an annonce
    public static function updateAnnonce($id, $data)
    {
        
        $setClause=[];
        $orderedData = [];
        foreach(static::$columns as $col){
            $setClause[] = "$col = ?";
            $orderedData[] = $data[$col] ?? null;
        }
        $setClause = implode(", ", $setClause);
        $query = "UPDATE " . static::$table . " SET $setClause WHERE id = ?";
        $orderedData[] = $id;
        return self::update($query, $orderedData);
    }

    // ✅ Delete an annonce
    public static function deleteAnnonce($id)
    {
        return self::del("DELETE FROM " . static::$table . " WHERE id = ?", [$id]);
    }
}
?>
