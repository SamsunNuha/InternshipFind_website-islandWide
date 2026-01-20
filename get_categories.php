<?php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

try {
    $stmt = $pdo->query("SELECT * FROM categories");
    $results = $stmt->fetchAll();
    echo json_encode($results);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
