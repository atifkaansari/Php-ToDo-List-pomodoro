<?php
require_once 'db.php';

if ($_POST && !empty($_POST['id']) && !empty($_POST['task'])) {
    $id = (int)$_POST['id'];
    $task = trim($_POST['task']);
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
    $priority = !empty($_POST['priority']) ? $_POST['priority'] : 'medium';
    
    if ($due_date) {
        $due_date = date('Y-m-d H:i:s', strtotime($due_date));
    }
    
    if (!empty($task)) {
        $stmt = $db->prepare("UPDATE todos SET task = ?, due_date = ?, priority = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$task, $due_date, $priority, $id]);
    }
}

$redirect = 'index.php';
if (!empty($_SERVER['HTTP_REFERER'])) {
    $referer = parse_url($_SERVER['HTTP_REFERER']);
    if (!empty($referer['query'])) {
        $redirect .= '?' . $referer['query'];
    }
}

header('Location: ' . $redirect);
exit;
?>