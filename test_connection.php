<!DOCTYPE html>
<html>
<head>
    <title>Connection Test - LankaIntern</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3>LankaIntern - System Diagnostics</h3>
            </div>
            <div class="card-body">
                <?php
                echo "<h5>✓ PHP is Working</h5>";
                echo "<p>PHP Version: " . phpversion() . "</p>";
                echo "<hr>";
                
                // Test database connection
                try {
                    require_once 'includes/db_connect.php';
                    echo "<h5 class='text-success'>✓ Database Connected Successfully</h5>";
                    
                    // Check if users table exists
                    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
                    if ($stmt->rowCount() > 0) {
                        echo "<p class='text-success'>✓ Users table exists</p>";
                    } else {
                        echo "<p class='text-danger'>✗ Users table not found. Please import database.sql</p>";
                    }
                    
                    // Check if internships table exists
                    $stmt = $pdo->query("SHOW TABLES LIKE 'internships'");
                    if ($stmt->rowCount() > 0) {
                        echo "<p class='text-success'>✓ Internships table exists</p>";
                    } else {
                        echo "<p class='text-danger'>✗ Internships table not found</p>";
                    }
                    
                    // Check if categories table exists
                    $stmt = $pdo->query("SHOW TABLES LIKE 'categories'");
                    if ($stmt->rowCount() > 0) {
                        echo "<p class='text-success'>✓ Categories table exists</p>";
                    } else {
                        echo "<p class='text-danger'>✗ Categories table not found</p>";
                    }
                    
                } catch (Exception $e) {
                    echo "<h5 class='text-danger'>✗ Database Connection Failed</h5>";
                    echo "<p class='text-danger'>Error: " . $e->getMessage() . "</p>";
                    echo "<p><strong>Fix:</strong> Make sure MySQL is running in XAMPP and the database 'internship_db' exists.</p>";
                }
                ?>
                <hr>
                <h5>Next Steps:</h5>
                <ol>
                    <li>If all checks pass, go to: <a href="signup.html">Sign Up Page</a></li>
                    <li>If database connection failed, import <code>sql/database.sql</code> in phpMyAdmin</li>
                    <li>Make sure to access via <code>http://localhost</code>, not by double-clicking files</li>
                </ol>
            </div>
        </div>
    </div>
</body>
</html>
