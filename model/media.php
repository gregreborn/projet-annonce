<?php
namespace model;
require_once "model.php";

/**
 * Modèle représentant les fichiers média liés aux annonces.
 * Gère l'enregistrement, la récupération et la suppression des médias.
 */
class Media extends Model
{
    // Nom de la table et colonnes concernées
    protected static $table = "media";
    protected static $columns = [
        'filePath',    // Chemin local du fichier sur le serveur
        'fileUrl',     // URL accessible publiquement
        'fileType',    // Type MIME (image, vidéo, pdf, etc.)
        'annoncesId'   // Clé étrangère liée à l’annonce
    ];

    /**
     * Enregistre un fichier média lié à une annonce
     */
    public static function saveMedia($annonceId, $filePath, $fileUrl, $fileType)
    {
        $cols = implode(", ", static::$columns);
        $placeholders = implode(", ", array_fill(0, count(static::$columns), '?'));
        $query = "INSERT INTO " . static::$table . " ($cols) VALUES ($placeholders)";
        return self::add($query, [$filePath, $fileUrl, $fileType, $annonceId]);    
    }   

    /**
     * Récupère tous les fichiers média liés à une annonce
     */
    public static function getMediaByAnnonceId($annonceId)
    {
        return self::gets("SELECT * FROM " . static::$table . " WHERE annoncesId = ?", [$annonceId]);
    }

    /**
     * Supprime tous les fichiers média liés à une annonce
     */
    public static function deleteMediaByAnnonceId($annonceId)
    {
        return self::del("DELETE FROM " . static::$table . " WHERE annoncesId = ?", [$annonceId]);
    }
}
?>