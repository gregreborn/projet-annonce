<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/../model/annonces.php";
require_once __DIR__ . "/../model/categories.php";
require_once __DIR__ . "/db.php";

use model\Annonce;
use model\Categories;

echo "<h2>Testing CRUD for Annonces</h2>";

function assertTrue($condition, $message) {
    if (!$condition) {
        throw new Exception("Assertion failed: " . $message);
    }
}

// Ensure a test category exists and return its ID.
function testEnsureCategoryExists() {
    // Try to retrieve a category with ID 1.
    $category = Categories::getCategoryById(1);
    if (!$category) {
        // Create a new test category if not found.
        $newCategoryId = Categories::createCategory("Test Category for Annonce");
        echo "✅ Created test category with ID: $newCategoryId<br>";
        return $newCategoryId;
    }
    return 1;
}

// ✅ 1. Insert a new annonce
function testCreateAnnonce() {
    $categoryId = testEnsureCategoryExists();  // Ensure valid category exists.
    $testAnnonceData = [
        "nomOrganisme"     => "Test Organization",
        "nom"              => "Doe",
        "prenom"           => "John",
        "titre"            => "Test Ad",
        "description"      => "Sample description",
        "telephone"        => "1234567890",
        "courriel"         => "test@example.com",
        "site"             => "http://example.com",
        "dateDeDebutPub"   => "2025-03-18",
        "dateDeFinPub"     => "2025-04-18",
        "adresse"          => "123 Main St",
        "ville"            => "Montreal",
        "province"         => "Quebec",
        "codePostal"       => "H1A2B3",
        "mrc"              => "Test MRC",
        "categoriesId"     => $categoryId
    ];
    $insertedId = Annonce::createAnnonce($testAnnonceData);
    assertTrue(is_numeric($insertedId) && $insertedId > 0, "Failed to insert test ad.");
    echo "✅ Successfully inserted test ad with ID: $insertedId<br>";
    return $insertedId;
}

function testGetAllAnnonces() {
    $annonces = Annonce::getAllAnnonces();
    assertTrue(is_array($annonces), "getAllAnnonces did not return an array.");
    echo "✅ Retrieved " . count($annonces) . " annonces!<br>";
    return $annonces;
}

function testUpdateAnnonce($id) {
    // Retrieve the original record to get the current categoriesId
    $originalAnnonce = Annonce::getAnnonceById($id);
    $originalCategoryId = $originalAnnonce['categoriesId'];
    
    $updateData = [
        "nomOrganisme"     => "Updated Organization",
        "nom"              => "Smith",
        "prenom"           => "Jane",
        "titre"            => "Updated Test Ad",
        "description"      => "Updated description",
        "telephone"        => "9876543210",
        "courriel"         => "updated@example.com",
        "site"             => "http://updated.com",
        "dateDeDebutPub"   => "2025-04-01",
        "dateDeFinPub"     => "2025-05-01",
        "adresse"          => "456 Updated St",
        "ville"            => "Toronto",
        "province"         => "Ontario",
        "codePostal"       => "M5A1A1",
        "mrc"              => "Updated MRC",
        "statut"          => "0", // Set status to 0 (inactive)
        "categoriesId"     => $originalCategoryId  // Reuse the valid category id
    ];
    $updatedRows = Annonce::updateAnnonce($id, $updateData);
    assertTrue($updatedRows > 0, "Failed to update ad with ID: $id.");
    echo "✅ Updated ad with ID: $id<br>";
}


function testGetAnnonceById($id) {
    $annonce = Annonce::getAnnonceById($id);
    assertTrue($annonce !== null, "Failed to retrieve updated annonce with ID: $id.");
    echo "✅ Retrieved updated annonce:<br><pre>";
    print_r($annonce);
    echo "</pre>";
    return $annonce;
}

function testDeleteAnnonce($id) {
    $deletedRows = Annonce::deleteAnnonce($id);
    assertTrue($deletedRows > 0, "Failed to delete test ad with ID: $id.");
    echo "✅ Deleted test ad with ID: $id<br>";
}

// Run all tests in sequence.
try {
    $insertedId = testCreateAnnonce();
    testGetAllAnnonces();
    testUpdateAnnonce($insertedId);
    testGetAnnonceById($insertedId);
    testDeleteAnnonce($insertedId);
    echo "<h3>All tests passed!</h3>";
} catch (Exception $e) {
    echo "<h3>Test failed: " . $e->getMessage() . "</h3>";
}
?>
