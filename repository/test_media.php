<?php 
    require_once __DIR__ . "/config.php";
    require_once __DIR__ . "/../model/media.php";
    require_once __DIR__ . "/db.php";
    require_once __DIR__ . "/../model/annonce.php";

    use model\Media;
    use model\Annonce;

    //simple assertion helper.
    function assertTrue($condition, $message) {
        if (!$condition) {
            throw new Exception("Assertion failed: " . $message);
        }
    }

    // create a temporary annonce record for media tests.
    function createTestAnnonce() {
        $testAnnonceData = [
            "nomOrganisme"     => "Test Organization",
            "nom"              => "Doe",
            "prenom"           => "John",
            "titre"            => "Test Ad",
            "description"      => "Sample description",
            "telephone"        => "1234567890",
            "courriel"         => "media_test@example.com",
            "site"             => "http://example.com",
            "dateDeDebutPub"   => "2025-03-18",
            "dateDeFinPub"     => "2025-04-18",
            "adresse"          => "123 Main St",
            "ville"            => "Montreal",
            "province"         => "Quebec",
            "codePostal"       => "H1A2B3",
            "mrc"              => "Test MRC"
        ];
        $annonceId = Annonce::createAnnonce($testAnnonceData);
        assertTrue(is_numeric($annonceId) && $annonceId > 0, "Failed to insert test ad.");
        echo "✅ Successfully inserted test ad with ID: $annonceId<br>";
        return $annonceId;
    }

    // delete the test annonce record.
    function deleteTestAnnonce($id) {
        $deleted = Annonce::deleteAnnonce($id);
        assertTrue($deleted > 0, "Failed to delete test ad with ID: $id.");
        echo "✅ Deleted test ad with ID: $id<br>";
    }

    //test media operations  using the given annonce ID.
    function testMediaOperations($annonceId){
        // insert a test media record.
        $mediaID = Media::saveMedia(
            $annonceId,
            "/public/uploads/test_image.jpg",
            "http://localhost/projet/public/uploads/test_image.jpg",
            "image/jpeg"
        );
        assertTrue(is_numeric($mediaID) && $mediaID > 0, "Failed to insert test media.");
        echo "✅ Successfully inserted test media with ID: $mediaID<br>";
    
        // Retrieve the media record for the annonce.
        $mediaRecords = Media::getMediaByAnnonceId($annonceId);
        assertTrue(is_array($mediaRecords) && count($mediaRecords) > 0, "No media found for annonce ID: $annonceId");
        echo "✅ Retrieved " . count($mediaRecords) . " media records for annonce ID: $annonceId<br>";

        // delete media records for the annonce.
        $deletedRows = Media::deleteMediaByAnnonceId($annonceId);
        assertTrue($deletedRows > 0, "Failed to delete media records for annonce ID: $annonceId.");
        echo "✅ Deleted $deletedRows media records for annonce ID: $annonceId<br>";
    }

    // run tests in a try-catch block.
    try {
        echo "<h2>Testing Media Operations</h2>";

        //create a test annonce record.
        $annonceId = createTestAnnonce();

        //test media operations for the created annonce.
        testMediaOperations($annonceId);

        //clean up: delete the test annonce record.
        deleteTestAnnonce($annonceId);

        echo "✅ All tests completed successfully!";
    } catch (Exception $e) {
        echo "<h3>❌ Error: " . $e->getMessage() . "</h3>";
    }

?>