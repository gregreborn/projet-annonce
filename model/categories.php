<?php
namespace model;
require_once "model.php";

class Categories extends Model
{
   // centralized table name and columns definition
    protected static $table = "categories";
    protected static $columns = ['nom'];

    // ✅ Create a new category
    public static function createCategory($nom)
    {
        $cols = implode(", ", static::$columns);
        $placeholders = implode(", ", array_fill(0, count(static::$columns), "?"));
        $query = "INSERT INTO " . static::$table . " ($cols) VALUES ($placeholders)";
        return self::add($query, [$nom]);
    }

    // ✅ Retrieve all categories
    public static function getAllCategories()
    {
        return self::gets("SELECT * FROM " . static::$table);
    }

    // ✅ Retrieve a category by ID
    public static function getCategoryById($id)
    {
        return self::get("SELECT * FROM " . static::$table . " WHERE id = ?", [$id]);
    }

    // ✅ Update a category
    public static function updateCategory($id, $nom)
    {
        $query = "UPDATE " . static::$table . " SET nom = ? WHERE id = ?";
        return self::update($query, [$nom, $id]);
    }

    // ✅ Delete a category
    public static function deleteCategory($id)
    {
        return self::del("DELETE FROM " . static::$table . " WHERE id = ?", [$id]);
    }
}
?>
