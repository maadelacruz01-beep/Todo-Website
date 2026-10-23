<?php
/**
 * index.php  -  READ (list all tasks) + the form for CREATE.
 * Table used: dbo.Tasks
 */
require __DIR__ . '/includes/db_config.php';

// READ: unfinished tasks first, then the earliest deadline first (tasks with
// no deadline go last), then newest first. No user input, but still prepared.
// CONVERT(..., 23) returns the deadline as YYYY-MM-DD.
$stmt = $pdo->prepare(
    "SELECT TaskID, TaskName, Description, Status,
            CONVERT(varchar(10), Deadline, 23)   AS Deadline,
            CONVERT(varchar(16), CreatedAt, 120) AS CreatedAt
     FROM dbo.Tasks
     ORDER BY CASE WHEN Status = 'Completed' THEN 1 ELSE 0 END,
              CASE WHEN Deadline IS NULL THEN 1 ELSE 0 END,
              Deadline,
              CreatedAt DESC"
);
$stmt->execute();
$tasks = $stmt->fetchAll();

$today = date('Y-m-d'); // used to flag overdue tasks

// Badge colour for each status (Bootstrap classes)
$badge = [
    'Not Started' => 'secondary',
    'In Progress' => 'primary',
    'Completed'   => 'success',
];

require __DIR__ . '/includes/header.php';
?>

<!-- CREATE form: sends the new task to add.php using POST -->
<form action="add.php" method="post" class="card card-body mb-4">
  <label class="form-label" for="task_name">Task name</label>
  <input type="text" id="task_name" name="task_name" class="form-control mb-3"
         placeholder="What needs to be done?" maxlength="255" required>

  <label class="form-label" for="description">Description (optional)</label>
  <textarea id="description" name="description" class="form-control mb-3"
            rows="3" maxlength="1000" placeholder="Add more detail"></textarea>

  <label class="form-label" for="deadline">Deadline (optional)</label>
  <input type="date" id="deadline" name="deadline" class="form-control mb-3">

  <div>
    <button class="btn btn-primary" type="submit">Add Task</button>
  </div>
</form>

<!-- READ: one table row per task -->
<table class="table table-striped bg-white align-middle">
  <thead>
    <tr>
      <th>Task</th><th>Status</th><th>Deadline</th><th>Created</th>
      <th class="text-end">Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php if (!$tasks): ?>
    <tr><td colspan="5" class="text-center text-muted">No tasks yet.</td></tr>
  <?php endif; ?>

  <?php foreach ($tasks as $t): ?>
    <?php
      $done    = $t['Status'] === 'Completed';
      $overdue = !$done && $t['Deadline'] !== null && $t['Deadline'] < $today;
    ?>
    <tr>
      <!-- e() escapes output so a task like <script> cannot run -->
      <td>
        <div class="<?= $done ? 'text-decoration-line-through text-muted' : 'fw-semibold' ?>">
          <?= e($t['TaskName']) ?>
        </div>
        <?php if ($t['Description'] !== null && $t['Description'] !== ''): ?>
          <!-- nl2br keeps line breaks; e() runs first so the text stays escaped -->
          <div class="small text-muted"><?= nl2br(e($t['Description'])) ?></div>
        <?php endif; ?>
      </td>
      <td>
        <span class="badge bg-<?= e($badge[$t['Status']] ?? 'secondary') ?>">
          <?= e($t['Status']) ?>
        </span>
      </td>
      <td>
        <?php if ($t['Deadline'] === null): ?>
          <span class="text-muted">None</span>
        <?php else: ?>
          <?= e($t['Deadline']) ?>
          <?php if ($overdue): ?><span class="badge bg-danger">Overdue</span><?php endif; ?>
        <?php endif; ?>
      </td>
      <td><?= e($t['CreatedAt']) ?></td>
      <td class="text-end">
        <!-- UPDATE (shortcut: complete / reopen) -->
        <form action="toggle.php" method="post" class="d-inline">
          <input type="hidden" name="id" value="<?= (int)$t['TaskID'] ?>">
          <button class="btn btn-sm btn-outline-success">
            <?= $done ? 'Reopen' : 'Complete' ?>
          </button>
        </form>
        <!-- UPDATE (edit all fields, including In Progress) -->
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