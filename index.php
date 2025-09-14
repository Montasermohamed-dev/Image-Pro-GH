<?php
// تحميل المهام من JSON
$tasks = [];
if (file_exists("tasks.json")) {
    $json = file_get_contents("tasks.json");
    $tasks = json_decode($json, true);
}

// إضافة مهمة جديدة
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["task"])) {
    $tasks[] = ["task" => $_POST["task"], "done" => false];
    file_put_contents("tasks.json", json_encode($tasks, JSON_PRETTY_PRINT));
    header("Location: index.php"); // refresh
    exit;
}

// تغيير حالة مهمة
if (isset($_GET["toggle"])) {
    $id = (int) $_GET["toggle"];
    if (isset($tasks[$id])) {
        $tasks[$id]["done"] = !$tasks[$id]["done"];
        file_put_contents("tasks.json", json_encode($tasks, JSON_PRETTY_PRINT));
    }
    header("Location: index.php");
    exit;
}

// حذف مهمة
if (isset($_GET["delete"])) {
    $id = (int) $_GET["delete"];
    if (isset($tasks[$id])) {
        unset($tasks[$id]);
        $tasks = array_values($tasks);
        file_put_contents("tasks.json", json_encode($tasks, JSON_PRETTY_PRINT));
    }
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Todo App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="card shadow-lg rounded-3">
    <div class="card-body">
      <h1 class="card-title text-center mb-4">✅ My Todo App</h1>

      <!-- Form -->
      <form method="POST" class="d-flex mb-3">
        <input type="text" name="task" class="form-control me-2" placeholder="Enter new task..." required>
        <button type="submit" class="btn btn-primary">Add</button>
      </form>

      <!-- Tasks -->
      <ul class="list-group">
        <?php foreach ($tasks as $i => $t): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center 
              <?php echo $t["done"] ? 'list-group-item-success' : ''; ?>">
            <span class="<?php echo $t["done"] ? 'text-decoration-line-through' : ''; ?>">
              <?php echo htmlspecialchars($t["task"]); ?>
            </span>
            <div>
              <a href="?toggle=<?php echo $i; ?>" class="btn btn-sm btn-outline-success me-1">
                <?php echo $t["done"] ? 'Undo' : 'Done'; ?>
              </a>
              <a href="?delete=<?php echo $i; ?>" class="btn btn-sm btn-outline-danger">Delete</a>
            </div>
          </li>
        <?php endforeach; ?>
        <?php if (empty($tasks)): ?>
          <li class="list-group-item text-center text-muted">No tasks yet!</li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</div>
</body>
</html>
