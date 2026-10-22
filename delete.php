<?php
/**
 * delete.php  -  DELETE
 * POST-only (a link/GET could be triggered by crawlers or prefetching).
 * The browser's confirm() prompt in index.php is the "safety check".
 */
require __DIR__ . '/includes/db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);

$stmt = $pdo->prepare("DELETE FROM dbo.Tasks WHERE TaskID = ?");
$stmt->execute([$id]);

redirect_with('success', 'Task deleted.');
