php<?php
require_once '/../api/config.php';

echo "<pre>";

try {
    $pdo->beginTransaction();

    $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM services WHERE name = :name");
    $insert_stmt = $pdo->prepare(
        "INSERT INTO services (name, description, price, image_url, category, recommended_for) VALUES (:name, :description, :price, :image_url, :category, :recommended_for)"
    );

    foreach ($services_to_seed as $service) {
        // Check if the service already exists
        $check_stmt->execute([':name' => $service['name']]);
        $count = $check_stmt->fetchColumn();
 
        if ($count == 0) {
            // If it doesn't exist, insert it
            $insert_stmt->execute([
                ':name' => $service['name'],
                ':description' => $service['description'],
                ':price' => $service['price'],
                ':image_url' => $service['image_url'] ?? null,
                ':category' => $service['category'],
                ':recommended_for' => $service['recommended_for'] ?? null
            ]);
            echo "Inserted: " . htmlspecialchars($service['name']) . "\n";
        } else {
            echo "Skipped (already exists): " . htmlspecialchars($service['name']) . "\n";
        }
    }

    $pdo->commit();
    echo "\nService seeding complete!\n";

} catch (Exception $e) {
    $pdo->rollBack();
    echo "An error occurred: " . $e->getMessage();
}
echo "</pre>";