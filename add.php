<?php
/**
 * add.php  -  CREATE
 * Receives the form from index.php and inserts one row into dbo.Tasks.
 * New tasks always start as 'Not Started' (the column default).
 */
require __DIR__ . '/includes/db_config.php';

// Only accept POST requests (not someone typing the URL in the browser).
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$name        = trim($_POST['task_name'] ?? '');
$description = trim($_POST['description'] ?? '');
$deadline    = trim($_POST['deadline'] ?? '');

// Server-side validation (never trust the browser only).
if ($name === '' || mb_strlen($name) > 255) {
    redirect_with('danger', 'Task name must be between 1 and 255 characters.');
}
if (mb_strlen($description) > 1000) {
    redirect_with('danger', 'Description must be 1000 characters or fewer.');
}

// Description and deadline are optional: an empty value is stored as NULL.
if ($description === '') {
    $description = null;
}

if ($deadline === '') {
    $deadline = null;
} else {
    // The date picker sends YYYY-MM-DD. Check it is a real calendar date.
    $d = DateTime::createFromFormat('Y-m-d', $deadline);
    if (!$d || $d->format('Y-m-d') !== $deadline) {
        redirect_with('danger', 'Deadline must be a valid date.');
    }
}

// Prepared statement: the ? values are sent separately, so input cannot alter the SQL.
$stmt = $pdo->prepare(
    "INSERT INTO dbo.Tasks (TaskName, Description, Deadline) VALUES (?, ?, ?)"
);
$stmt->execute([$name, $description, $deadline]);

redirect_with('success', 'Task added.');