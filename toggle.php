<?php
/**
 * toggle.php  -  UPDATE (one-click shortcut: Completed <-> Not Started)
 * With three statuses, this is only a shortcut. "In Progress" is set from the Edit page.
 */
require __DIR__ . '/includes/db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);

// CASE flips the status inside SQL: a completed task is reopened as 'Not Started',
// any other task (Not Started or In Progress) becomes 'Completed'.
$stmt = $pdo->prepare(
    "UPDATE dbo.Tasks
     SET Status = CASE WHEN Status = 'Completed' THEN 'Not Started' ELSE 'Completed' END
     WHERE TaskID = ?"
);
$stmt->execute([$id]);

redirect_with('success', 'Task status changed.');