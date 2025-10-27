<?php
require_once 'db.php';

if ($_POST && !empty($_POST['task'])) {
    $task = trim($_POST['task']);
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
    $priority = !empty($_POST['priority']) ? $_POST['priority'] : 'medium';
    
    if ($due_date) {
        $due_date = date('Y-m-d H:i:s', strtotime($due_date));
    }
    
    if (!empty($task)) {
        $stmt = $db->prepare("INSERT INTO todos (task, completed, due_date, priority, created_at) VALUES (?, 0, ?, ?, NOW())");
        $stmt->execute([$task, $due_date, $priority]);
    }
}

$redirect = 'index.php';
if (!empty($_GET['view'])) {
    $redirect .= '?view=' . urlencode($_GET['view']);
} elseif (!empty($_GET['date'])) {
    $redirect .= '?date=' . urlencode($_GET['date']);
}

header('Location: ' . $redirect);
exit;
?>