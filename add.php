<?php
/**
 * add.php  -  CREATE
 * Receives the form from index.php and inserts one row into dbo.Tasks.
 */
require __DIR__ . '/includes/db_config.php';

// Only accept POST requests (not someone typing the URL in the browser).
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$name = trim($_POST['task_name'] ?? '');

// Server-side validation (never trust the browser only).
if ($name === '' || mb_strlen($name) > 255) {
    redirect_with('danger', 'Task must be between 1 and 255 characters.');
}

// Prepared statement: the ? is filled in separately, so input cannot alter the SQL.
$stmt = $pdo->prepare("INSERT INTO dbo.Tasks (TaskName) VALUES (?)");
$stmt->execute([$name]);

redirect_with('success', 'Task added.');
