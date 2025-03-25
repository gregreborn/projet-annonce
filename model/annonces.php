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
        'adresse', 'ville', 'latitude', 'longitude', 'codePostal', 'mrc',
        'type', 'categoriesId'
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

    // ✅ Retrieve all annonces with category details
    public static function getAllAnnoncesWithCategories()
    {
        return self::gets("SELECT a.*, c.nom as categorie FROM " . static::$table . " a JOIN categories c ON a.categoriesId = c.id");
    }

    public static function getAnnoncesWithFirstImageByType($type)
    {
        $sql = "
            SELECT a.*, c.nom AS categorie,
                (SELECT m.fileUrl 
                FROM media m 
                WHERE m.annoncesId = a.id 
                ORDER BY m.id ASC LIMIT 1) AS thumbnail
            FROM annonces a
            JOIN categories c ON a.categoriesId = c.id
            WHERE a.type = ?
        ";
        return self::gets($sql, [$type]);
    }

    public static function getAnnoncesWithFirstImageByTypeSorted($type, $sort = null)
    {
        $order = "a.id DESC"; // default: recent
        if ($sort === "alpha") $order = "a.titre ASC";
        if ($sort === "ville") $order = "a.ville ASC";

        $sql = "
            SELECT a.*, c.nom AS categorie,
            (SELECT m.fileUrl FROM media m WHERE m.annoncesId = a.id ORDER BY m.id ASC LIMIT 1) AS thumbnail
            FROM annonces a
            JOIN categories c ON a.categoriesId = c.id
            WHERE a.type = ?
            ORDER BY $order
        ";
        return self::gets($sql, [$type]);
    }


    public static function searchAnnoncesByTypeAndQuery($type, $q = null, $sort = null, $category = null)
    {
        $order = "a.id DESC";
        if ($sort === "alpha") $order = "a.titre ASC";
        if ($sort === "ville") $order = "a.ville ASC";
    
        $sql = "
            SELECT a.*, c.nom AS categorie,
            (SELECT m.fileUrl FROM media m WHERE m.annoncesId = a.id ORDER BY m.id ASC LIMIT 1) AS thumbnail
            FROM annonces a
            JOIN categories c ON a.categoriesId = c.id
            WHERE a.type = ?
        ";
    
        $params = [$type];
    
        if ($q) {
            $sql .= " AND (a.titre LIKE ? OR a.ville LIKE ? OR a.description LIKE ?)";
            $like = "%$q%";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }
    
        if ($category) {
            $sql .= " AND a.categoriesId = ?";
            $params[] = $category;
        }
    
        $sql .= " ORDER BY $order";
    
        return self::gets($sql, $params);
    }
    
    
    
    // ✅ recent annonces
    public static function recentAnnonces()
    {
        return self::gets("SELECT * FROM " . static::$table . " ORDER BY id DESC LIMIT 5");
    }

    // ✅ Retrieve an annonce by ID
    public static function getAnnonceById($id)
    {
        return self::get("SELECT * FROM " . static::$table . " WHERE id = ?", [$id]);
    }

    // ✅ Retrieve annonces by type (offre or besoin)
    public static function getAnnoncesByType($type)
    {
        return self::gets("SELECT * FROM " . static::$table . " WHERE type = ?", [$type]);
    }

    public static function getAnnoncesByTypeWithCategory($type)
    {
        return self::gets("SELECT a.*, c.nom as categorie FROM annonces a JOIN categories c ON a.categoriesId = c.id WHERE a.type = ?", [$type]);
    }
    
    // ✅ Retrieve annonces by category ID
    public static function getAnnoncesByCategoryId($categoryId)
    {
        return self::gets("SELECT * FROM " . static::$table . " WHERE categoriesId = ?", [$categoryId]);
    }
    

    // ✅ count annonces by category ID
    public static function countAnnoncesByCategoryId($categoryId)
    {
        return self::get("SELECT COUNT(*) as total FROM " . static::$table . " WHERE categoriesId = ?", [$categoryId]);
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
