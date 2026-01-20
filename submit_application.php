<?php
header('Content-Type: application/json');
session_start();
require_once '../includes/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to apply']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $internship_id = $_POST['internship_id'];
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $qualifications = trim($_POST['qualifications']);
    $cover_letter = trim($_POST['cover_letter']);

    // Validate required fields
    if (empty($internship_id) || empty($full_name) || empty($email) || empty($qualifications)) {
        echo json_encode(['success' => false, 'message' => 'Please fill all required fields']);
        exit;
    }

    // Check if already applied
    try {
        $stmt = $pdo->prepare("SELECT id FROM applications WHERE user_id = ? AND internship_id = ?");
        $stmt->execute([$user_id, $internship_id]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'You have already applied for this internship']);
            exit;
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        exit;
    }

    // Handle file upload
    $resume_path = null;
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['resume'];
        $allowed_types = ['application/pdf'];
        $max_size = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowed_types)) {
            echo json_encode(['success' => false, 'message' => 'Only PDF files are allowed']);
            exit;
        }

        if ($file['size'] > $max_size) {
            echo json_encode(['success' => false, 'message' => 'File size must be less than 5MB']);
            exit;
        }

        // Create uploads directory if it doesn't exist
        $upload_dir = '../uploads/resumes/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Generate unique filename
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_filename = 'resume_' . $user_id . '_' . time() . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;

        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            $resume_path = 'uploads/resumes/' . $new_filename;
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload resume']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Resume is required']);
        exit;
    }

    // Insert application
    try {
        $stmt = $pdo->prepare("INSERT INTO applications (user_id, internship_id, full_name, email, phone, qualifications, resume_path, cover_letter) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt->execute([$user_id, $internship_id, $full_name, $email, $phone, $qualifications, $resume_path, $cover_letter])) {
            echo json_encode(['success' => true, 'message' => 'Application submitted successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to submit application']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
