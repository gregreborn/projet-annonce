<?php
namespace model;
require_once "model.php";

class Localite extends Model
{
    protected static $table = "localites";

    // ✅ Get all localités (e.g., for a dropdown list)
    public static function getAllLocalites()
    {
        return self::gets("SELECT * FROM " . static::$table . " ORDER BY nom ASC");
    }

    public static function getAll() {
        return self::gets("SELECT * FROM localites ORDER BY nom ASC");
    }
    
    // ✅ Get a localité by id
    public static function getById($id)
    {
        return self::get("SELECT * FROM " . static::$table . " WHERE id = ?", [$id]);
    }

    // ✅ Get a localité by name (ville)
    public static function getByVille($ville)
    {
        return self::get("SELECT * FROM " . static::$table . " WHERE nom = ?", [$ville]);
    }

    // ✅ Get the prefix of a localité
    public static function getPrefixByVille($ville)
    {
        $result = self::get("SELECT prefixe_postal FROM " . static::$table . " WHERE nom = ?", [$ville]);
        return $result['prefixe_postal'] ?? null;
    }

    public static function getByName($name) {
        return self::get("SELECT * FROM localites WHERE nom = ?", [$name]);
    }
    

}
?>
