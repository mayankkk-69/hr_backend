<?php
header('Content-Type: application/json');
require_once 'db.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$title = trim($_POST['title'] ?? '');
$shortDesc = trim($_POST['shortDesc'] ?? '');
$longDesc = trim($_POST['longDesc'] ?? '');

if (empty($title) || empty($longDesc)) {
    echo json_encode(['success' => false, 'message' => 'Title and Long Description are required']);
    exit;
}

// Optionally handle file uploads if attachment exists
$attachmentUrl = null;
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
    // In a real scenario, you'd check extension and save the file somewhere within /uploads/
    $uploadDir = '../../uploads/notices/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $filename = time() . '_' . preg_replace("/[^a-zA-Z0-9.-]/", "_", $_FILES['attachment']['name']);
    $uploadPath = $uploadDir . $filename;
    
    if (move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadPath)) {
        $attachmentUrl = '/CRM/uploads/notices/' . $filename;
    }
}

try {
    $stmt = $pdo->prepare("INSERT INTO hr_notices (title, short_desc, long_desc, attachment) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $shortDesc, $longDesc, $attachmentUrl]);

    echo json_encode(['success' => true, 'message' => 'Notice successfully broadcasted!']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
