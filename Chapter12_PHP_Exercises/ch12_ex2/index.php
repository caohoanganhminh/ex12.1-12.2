<?php
// Exercise 12-2: Task List Manager
// Start a session that lasts for 1 year.
$session_lifetime = 60 * 60 * 24 * 365;

session_set_cookie_params([
    'lifetime' => $session_lifetime,
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

// Store the array of tasks in the session instead of hidden HTML fields.
if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $task = trim($_POST['task'] ?? '');
        if ($task !== '') {
            $_SESSION['tasks'][] = $task;
        }
    } elseif ($action === 'delete') {
        $index = filter_input(INPUT_POST, 'index', FILTER_VALIDATE_INT);
        if ($index !== false && $index !== null && isset($_SESSION['tasks'][$index])) {
            array_splice($_SESSION['tasks'], $index, 1);
        }
    } elseif ($action === 'clear') {
        $_SESSION['tasks'] = [];
    }

    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task List Manager</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .box { max-width: 650px; }
        input[type="text"] { width: 400px; padding: 8px; }
        button { padding: 7px 12px; margin: 3px; }
        .task { margin: 10px 0; padding: 8px; border-bottom: 1px solid #ddd; }
        .session { margin-top: 20px; padding: 10px; background: #eee; word-break: break-all; }
    </style>
</head>
<body>
<div class="box">
    <h1>Task List Manager</h1>

    <form method="post">
        <input type="hidden" name="action" value="add">
        <input type="text" name="task" placeholder="Enter a task" required>
        <button type="submit">Add Task</button>
    </form>

    <h2>Tasks</h2>

    <?php if (empty($_SESSION['tasks'])): ?>
        <p>No tasks.</p>
    <?php else: ?>
        <?php foreach ($_SESSION['tasks'] as $index => $task): ?>
            <div class="task">
                <?php echo htmlspecialchars($task); ?>

                <form method="post" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="index" value="<?php echo $index; ?>">
                    <button type="submit">Delete</button>
                </form>
            </div>
        <?php endforeach; ?>

        <form method="post">
            <input type="hidden" name="action" value="clear">
            <button type="submit">Clear All Tasks</button>
        </form>
    <?php endif; ?>

    <div class="session">
        <strong>Session ID:</strong>
        <?php echo htmlspecialchars(session_id()); ?>
    </div>
</div>
</body>
</html>
