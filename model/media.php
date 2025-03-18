<?php
namespace model;
require_once "model.php";

class Media extends Model
{
    //Centralize configuration for the media table
    protected static $table = "media";
    protected static $columns = [
        'filePath', 'fileUrl', 'fileType', 'annoncesId'
    ];

    // ✅ save media file linked to an annonce
    public static function saveMedia($annonceId, $filePath, $fileUrl, $fileType)
    {
        $cols = implode(", ", static::$columns);
        $placeholders = implode(", ", array_fill(0, count(static::$columns), '?'));
        $query = "INSERT INTO " . static::$table . " ($cols) VALUES ($placeholders)";
        return self::add($query, [$filePath, $fileUrl, $fileType, $annonceId]);    
    }

    // ✅ Retrieve media files by annonce ID
    public static function getMediaByAnnonceId($annonceId)
    {
        return self::gets("SELECT * FROM " . static::$table . " WHERE annoncesId = ?", [$annonceId]);
    }

    // ✅ Delete media files by annonce ID
    public static function deleteMediaByAnnonceId($annonceId)
    {
        return self::del("DELETE FROM " . static::$table . " WHERE annoncesId = ?", [$annonceId]);
    }
}
?>