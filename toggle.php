<?php
/**
 * toggle.php  -  UPDATE (one-click Pending <-> Completed)
 */
require __DIR__ . '/includes/db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);

// CASE flips the status inside SQL, so no extra read is needed.
$stmt = $pdo->prepare(
    "UPDATE dbo.Tasks
     SET Status = CASE WHEN Status = 'Pending' THEN 'Completed' ELSE 'Pending' END
     WHERE TaskID = ?"
);
$stmt->execute([$id]);

redirect_with('success', 'Task status changed.');
