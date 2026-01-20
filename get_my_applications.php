<?php
header('Content-Type: application/json');
session_start();
require_once '../includes/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to view applications']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("
        SELECT 
            a.*,
            i.title as job_title,
            i.company,
            i.location,
            i.stipend,
            i.duration,
            c.name as category_name,
            c.icon as category_icon
        FROM applications a
        JOIN internships i ON a.internship_id = i.id
        LEFT JOIN categories c ON i.category_id = c.id
        WHERE a.user_id = ?
        ORDER BY a.applied_at DESC
    ");
    
    $stmt->execute([$user_id]);
    $applications = $stmt->fetchAll();
    
    echo json_encode(['success' => true, 'applications' => $applications]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
