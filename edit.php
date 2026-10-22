<?php
/**
 * edit.php  -  UPDATE (change task text and status)
 * GET  : load the task and show a pre-filled form.
 * POST : save the changes.
 */
require __DIR__ . '/includes/db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int)($_POST['id'] ?? 0);
    $name   = trim($_POST['task_name'] ?? '');
    $status = $_POST['status'] ?? '';

    if ($id <= 0 || $name === '' || mb_strlen($name) > 255
        || !in_array($status, ['Pending', 'Completed'], true)) {
        redirect_with('danger', 'Invalid input.');
    }

    $stmt = $pdo->prepare("UPDATE dbo.Tasks SET TaskName = ?, Status = ? WHERE TaskID = ?");
    $stmt->execute([$name, $status, $id]);
    redirect_with('success', 'Task updated.');
}

// GET: fetch the task to edit
$id   = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT TaskID, TaskName, Status FROM dbo.Tasks WHERE TaskID = ?");
$stmt->execute([$id]);
$task = $stmt->fetch();

if (!$task) {
    redirect_with('danger', 'Task not found.');
}

require __DIR__ . '/includes/header.php';
?>

<form action="edit.php" method="post" class="card card-body">
  <input type="hidden" name="id" value="<?= (int)$task['TaskID'] ?>">

  <label class="form-label">Task</label>
  <input type="text" name="task_name" class="form-control mb-3"
         value="<?= e($task['TaskName']) ?>" maxlength="255" required>

  <label class="form-label">Status</label>
  <select name="status" class="form-select mb-3">
    <?php foreach (['Pending', 'Completed'] as $s): ?>
      <option value="<?= $s ?>" <?= $task['Status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
    <?php endforeach; ?>
  </select>

  <div>
    <button class="btn btn-primary" type="submit">Save</button>
    <a href="index.php" class="btn btn-link">Cancel</a>
  </div>
</form>

<?php require __DIR__ . '/includes/footer.php'; ?>
