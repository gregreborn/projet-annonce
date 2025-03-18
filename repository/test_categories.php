<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/../model/categories.php";
require_once __DIR__ . "/db.php";

use model\Categories;

echo "<h2>Testing CRUD for Categories</h2>";

function assertTrue($condition, $message) {
    if (!$condition) {
        throw new Exception("Assertion failed: " . $message);
    }
}

// ✅ 1. Insert a new category
function testCreateCategory() {
    $categoryName = "Test Category" . time();
    $insertedId = Categories::createCategory($categoryName);
    assertTrue(is_numeric($insertedId) && $insertedId > 0, "Failed to insert test category.");
    echo "✅ Successfully inserted test category with ID: $insertedId<br>";
    return $insertedId;
}

// ✅ 2. Retrieve all categories
function testGetAllCategories() {
    $categories = Categories::getAllCategories();
    assertTrue(is_array($categories), "getAllCategories did not return an array.");
    echo "✅ Retrieved " . count($categories) . " categories!<br>";
    return $categories;
}

// ✅ 3. Update category
function testUpdateCategory($id) {
    $updatedName = "Updated Category " . time();
    $updatedRows = Categories::updateCategory($id, $updatedName);
    $category = Categories::getCategoryById($id);
    $actualName = $category && isset($category['nom']) ? $category['nom'] : null;
    
    // Debug output to help troubleshoot what value is actually in the DB:
    if ($actualName !== $updatedName) {
        echo "DEBUG: Expected: '$updatedName' but got: '$actualName'<br>";
    }
    
    assertTrue($actualName === $updatedName, "Failed to update category name. Expected '$updatedName', got '$actualName'");
    echo "✅ Updated category with ID: $id<br>";
}


// ✅ 4. Retrieve category by ID
function testGetCategoryById($id) {
    $category = Categories::getCategoryById($id);
    assertTrue($category !== null, "Failed to retrieve updated category with ID: $id.");
    echo "✅ Retrieved updated category:<br><pre>";
    print_r($category);
    echo "</pre>";
    return $category;
}

// ✅ 5. Delete the category
function testDeleteCategory($id) {
    $deletedRows = Categories::deleteCategory($id);
    assertTrue($deletedRows > 0, "Failed to delete category with ID: $id.");
    echo "✅ Deleted test category with ID: $id<br>";
}

// Run all tests in sequence
try {
    $categoryId = testCreateCategory();
    testGetAllCategories();
    testUpdateCategory($categoryId);
    testGetCategoryById($categoryId);
    testDeleteCategory($categoryId);
    echo "<h3>All Category tests passed!</h3>";
} catch (Exception $e) {
    echo "<h3>Test failed: " . $e->getMessage() . "</h3>";
}
?>
