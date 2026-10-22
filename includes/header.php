<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>To-Do List</title>
  <!-- Bootstrap via CDN (needs internet access on the machine viewing the site) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4" style="max-width: 800px;">
  <h1 class="mb-4">📝 To-Do List</h1>
  <?php if (!empty($_SESSION['flash'])): ?>
    <div class="alert alert-<?= e($_SESSION['flash']['type']) ?>">
      <?= e($_SESSION['flash']['message']) ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>
