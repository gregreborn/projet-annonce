<?php
namespace model;
require_once "model.php";

/**
 * Modèle représentant les catégories.
 * Permet de gérer la table "categories" (CRUD).
 */
class Categories extends Model
{
    // Nom de la table et colonnes concernées
    protected static $table = "categories";
    protected static $columns = ['nom'];

    /**
     * Crée une nouvelle catégorie
     */
    public static function createCategory($nom)
    {
        $cols = implode(", ", static::$columns);
        $placeholders = implode(", ", array_fill(0, count(static::$columns), "?"));
        $query = "INSERT INTO " . static::$table . " ($cols) VALUES ($placeholders)";
        return self::add($query, [$nom]);
    }

    /**
     * Récupère toutes les catégories
     */
    public static function getAllCategories()
    {
        return self::gets("SELECT * FROM " . static::$table);
    }

    /**
     * Récupère une catégorie selon son ID
     */
    public static function getCategoryById($id)
    {
        return self::get("SELECT * FROM " . static::$table . " WHERE id = ?", [$id]);
    }


    /**
     * Met à jour une catégorie existante
     */
    public static function updateCategory($id, $nom)
    {
        $query = "UPDATE " . static::$table . " SET nom = ? WHERE id = ?";
        return self::update($query, [$nom, $id]);
    }

    
    /**
     * Supprime une catégorie par ID
     */
    public static function deleteCategory($id)
    {
        return self::del("DELETE FROM " . static::$table . " WHERE id = ?", [$id]);
    }
}
?>
