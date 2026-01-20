<?php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

$query = "SELECT i.*, c.name as category_name 
          FROM internships i 
          LEFT JOIN categories c ON i.category_id = c.id 
          WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (i.title LIKE ? OR i.company LIKE ? OR i.location LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($category)) {
    $query .= " AND i.category_id = ?";
    $params[] = $category;
}

$query .= " ORDER BY i.posted_date DESC";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $results = $stmt->fetchAll();
    echo json_encode($results);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
