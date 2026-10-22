<?php
/**
 * index.php  -  READ (list all tasks) + the form for CREATE.
 * Table used: dbo.Tasks
 */
require __DIR__ . '/includes/db_config.php';

// READ: pending tasks first, then newest first. No user input, but still prepared.
$stmt = $pdo->prepare(
    "SELECT TaskID, TaskName, Status,
            CONVERT(varchar(16), CreatedAt, 120) AS CreatedAt
     FROM dbo.Tasks
     ORDER BY CASE WHEN Status = 'Pending' THEN 0 ELSE 1 END, CreatedAt DESC"
);
$stmt->execute();
$tasks = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<!-- CREATE form: sends the new task to add.php using POST -->
<form action="add.php" method="post" class="input-group mb-4">
  <input type="text" name="task_name" class="form-control"
         placeholder="What needs to be done?" maxlength="255" required>
  <button class="btn btn-primary" type="submit">Add Task</button>
</form>

<!-- READ: one table row per task -->
<table class="table table-striped bg-white align-middle">
  <thead>
    <tr><th>Task</th><th>Status</th><th>Created</th><th class="text-end">Actions</th></tr>
  </thead>
  <tbody>
  <?php if (!$tasks): ?>
    <tr><td colspan="4" class="text-center text-muted">No tasks yet.</td></tr>
  <?php endif; ?>

  <?php foreach ($tasks as $t): ?>
    <tr>
      <!-- e() escapes output so a task like <script> cannot run -->
      <td class="<?= $t['Status'] === 'Completed' ? 'text-decoration-line-through text-muted' : '' ?>">
        <?= e($t['TaskName']) ?>
      </td>
      <td>
        <span class="badge bg-<?= $t['Status'] === 'Completed' ? 'success' : 'warning text-dark' ?>">
          <?= e($t['Status']) ?>
        </span>
      </td>
      <td><?= e($t['CreatedAt']) ?></td>
      <td class="text-end">
        <!-- UPDATE (toggle status) -->
        <form action="toggle.php" method="post" class="d-inline">
          <input type="hidden" name="id" value="<?= (int)$t['TaskID'] ?>">
          <button class="btn btn-sm btn-outline-success">
            <?= $t['Status'] === 'Pending' ? 'Complete' : 'Reopen' ?>
          </button>
        </form>
        <!-- UPDATE (edit text) -->
        <a href="edit.php?id=<?= (int)$t['TaskID'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
        <!-- DELETE (POST + confirmation prompt) -->
        <form action="delete.php" method="post" class="d-inline"
              onsubmit="return confirm('Delete this task?');">
          <input type="hidden" name="id" value="<?= (int)$t['TaskID'] ?>">
          <button class="btn btn-sm btn-outline-danger">Delete</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<?php require __DIR__ . '/includes/footer.php'; ?>
