<?php
namespace model;
require_once "model.php";

/**
 * Modèle représentant les annonces.
 * Gère les opérations CRUD sur la table "annonces".
 */
class Annonce extends Model
{
    // Nom de la table et colonnes utilisées
    protected static $table = "annonces";
    protected static $columns = [
        'nomOrganisme', 'nom', 'prenom', 'titre', 'description',
        'telephone', 'courriel', 'site', 'dateDeDebutPub', 'dateDeFinPub',
        'adresse', 'ville', 'province', 'codePostal', 'mrc', 'type', 'categoriesId'
    ];
    

    /**
     * Crée une nouvelle annonce dans la base de données
     */    
    public static function createAnnonce($data)
    {
        $cols = implode(", ", static::$columns);
        $placeholders = implode(", ", array_fill(0, count(static::$columns), "?"));
        $query = "INSERT INTO " . static::$table . " ($cols) VALUES ($placeholders)";
        
        // Ordonne les données selon les colonnes définies
        $orderedData =[];
        foreach (static::$columns as $col) {
            $orderedData[] = $data[$col] ?? null;
        }
        return self::add($query, $orderedData);
    }

    /**
     * Récupère toutes les annonces
     */
    public static function getAllAnnonces()
    {
        return self::gets("SELECT * FROM " . static::$table);
    }

     /**
     * Récupère toutes les annonces avec le nom de la catégorie associée
     */
    public static function getAllAnnoncesWithCategories()
    {
        return self::gets("SELECT a.*, c.nom as categorie FROM " . static::$table . " a JOIN categories c ON a.categoriesId = c.id");
    }

    /**
     * Récupère les 5 annonces les plus récentes
     */
    public static function recentAnnonces()
    {
        return self::gets("SELECT * FROM " . static::$table . " ORDER BY id DESC LIMIT 5");
    }

    /**
     * Récupère une annonce selon son ID
     */
    public static function getAnnonceById($id)
    {
        return self::get("SELECT * FROM " . static::$table . " WHERE id = ?", [$id]);
    }


    /**
     * Récupère les annonces selon leur type ("offre" ou "besoin")
     */    
    public static function getAnnoncesByType($type)
    {
        return self::gets("SELECT * FROM " . static::$table . " WHERE type = ?", [$type]);
    }


    /**
     * Récupère les annonces associées à une catégorie spécifique
     */
    public static function getAnnoncesByCategoryId($categoryId)
    {
        return self::gets("SELECT * FROM " . static::$table . " WHERE categoriesId = ?", [$categoryId]);
    }

    /**
     * Compte le nombre d’annonces pour une catégorie spécifique
     */
    public static function countAnnoncesByCategoryId($categoryId)
    {
        return self::get("SELECT COUNT(*) as total FROM " . static::$table . " WHERE categoriesId = ?", [$categoryId]);
    }

    /**
     * Met à jour une annonce existante selon son ID
     */    
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

    /**
     * Supprime une annonce selon son ID
     */    
    public static function deleteAnnonce($id)
    {
        return self::del("DELETE FROM " . static::$table . " WHERE id = ?", [$id]);
    }
}
?>
