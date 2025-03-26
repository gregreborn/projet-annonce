<?php
require_once __DIR__ . "/repository/db.php"; // ou le chemin correct

set_time_limit(300); // 5 minutes
echo "🚀 Script started<br>";

$pdo = new PDO("mysql:host=localhost;dbname=projet-babillard", "gregory", "TYq1R!c4vm9BC(N0");

$apiKey = "cb696619a4da43e2b890d0466ee5023d";

$stmt = $pdo->query("SELECT id, nom, mrc FROM localites WHERE latitude = 0 OR longitude = 0");
$localites = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($localites as $localite) {
    $query = urlencode("{$localite['nom']}, {$localite['mrc']}, Lanaudière, Québec, Canada");
    $url = "https://api.opencagedata.com/geocode/v1/json?q=$query&key=$apiKey&language=fr&limit=1";

    $response = file_get_contents($url);
    $json = json_decode($response, true);

    if (!empty($json['results'])) {
        $lat = $json['results'][0]['geometry']['lat'];
        $lng = $json['results'][0]['geometry']['lng'];

        $update = $pdo->prepare("UPDATE localites SET latitude = ?, longitude = ? WHERE id = ?");
        $update->execute([$lat, $lng, $localite['id']]);

        echo "✅ {$localite['nom']} mis à jour avec lat: $lat, lng: $lng\n";
    } else {
        echo "❌ Pas de résultat pour {$localite['nom']} ({$localite['mrc']})\n";
    }

    sleep(1); // Respect API rate limit
}
?>
