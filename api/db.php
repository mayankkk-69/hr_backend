<?php
// ============================================
// db.php — Shared Database Connection
// ============================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // Change if your XAMPP MySQL user is different
define('DB_PASS', '');           // Change if you have a password set
define('DB_NAME', 'crm');        // Actual database name

// ── PDO connection (used by most API files) ──
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database Connection Failed: ' . $e->getMessage()]);
    exit;
}

// ── mysqli connection helper (optional legacy use) ──
function getDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
        exit;
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}
