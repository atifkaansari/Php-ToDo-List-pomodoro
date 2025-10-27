<?php
require_once 'db.php';

if ($_POST && !empty($_POST['id'])) {
    $id = (int)$_POST['id'];
    
    $stmt = $db->prepare("DELETE FROM todos WHERE id = ?");
    $stmt->execute([$id]);
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    echo json_encode(['success' => true]);
    exit;
}

header('Location: index.php');
exit;
?>