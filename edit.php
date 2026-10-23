<?php
/**
 * edit.php  -  UPDATE (change name, description, deadline and status)
 * GET  : load the task and show a pre-filled form.
 * POST : validate and save the changes.
 */
require __DIR__ . '/includes/db_config.php';

// The only status values the app accepts (must match the CHECK constraint in SQL).
$STATUSES = ['Not Started', 'In Progress', 'Completed'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = (int)($_POST['id'] ?? 0);
    $name        = trim($_POST['task_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $deadline    = trim($_POST['deadline'] ?? '');
    $status      = $_POST['status'] ?? '';

    // Server-side validation (never trust the browser only).
    if ($id <= 0 || $name === '' || mb_strlen($name) > 255
        || !in_array($status, $STATUSES, true)) {
        redirect_with('danger', 'Invalid input.');
    }
    if (mb_strlen($description) > 1000) {
        redirect_with('danger', 'Description must be 1000 characters or fewer.');
    }

    // Optional fields: an empty value is stored as NULL.
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

    // Prepared statement: input cannot alter the SQL.
    $stmt = $pdo->prepare(
        "UPDATE dbo.Tasks
         SET TaskName = ?, Description = ?, Deadline = ?, Status = ?
         WHERE TaskID = ?"
    );
    $stmt->execute([$name, $description, $deadline, $status, $id]);
    redirect_with('success', 'Task updated.');
}

// GET: fetch the task to edit. CONVERT(..., 23) returns the date as YYYY-MM-DD,
// the format the date picker needs.
$id   = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare(
    "SELECT TaskID, TaskName, Description, Status,
            CONVERT(varchar(10), Deadline, 23) AS Deadline
     FROM dbo.Tasks WHERE TaskID = ?"
);
$stmt->execute([$id]);
$task = $stmt->fetch();

if (!$task) {
    redirect_with('danger', 'Task not found.');
}

require __DIR__ . '/includes/header.php';
?>

<form action="edit.php" method="post" class="card card-body">
  <input type="hidden" name="id" value="<?= (int)$task['TaskID'] ?>">

  <label class="form-label" for="task_name">Task name</label>
  <input type="text" id="task_name" name="task_name" class="form-control mb-3"
         value="<?= e($task['TaskName']) ?>" maxlength="255" required>

  <label class="form-label" for="description">Description (optional)</label>
  <textarea id="description" name="description" class="form-control mb-3"
            rows="3" maxlength="1000"><?= e($task['Description'] ?? '') ?></textarea>

  <label class="form-label" for="deadline">Deadline (optional)</label>
  <input type="date" id="deadline" name="deadline" class="form-control mb-3"
         value="<?= e($task['Deadline'] ?? '') ?>">

  <label class="form-label" for="status">Status</label>
  <select id="status" name="status" class="form-select mb-3">
    <?php foreach ($STATUSES as $s): ?>
      <option value="<?= e($s) ?>" <?= $task['Status'] === $s ? 'selected' : '' ?>><?= e($s) ?></option>
    <?php endforeach; ?>
  </select>

  <div>
    <button class="btn btn-primary" type="submit">Save</button>
    <a href="index.php" class="btn btn-link">Cancel</a>
  </div>
</form>

<?php require __DIR__ . '/includes/footer.php'; ?>