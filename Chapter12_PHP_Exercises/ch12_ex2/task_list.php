<?php
// Exercise 12-2: task_list.php
// The task array is stored in the session.
// It is NOT stored as a series of hidden HTML fields.

$session_lifetime = 60 * 60 * 24 * 365;

session_set_cookie_params([
    'lifetime' => $session_lifetime,
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

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

    header('Location: task_list.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task List</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .box { max-width: 650px; }
        .task { margin: 10px 0; padding: 8px; border-bottom: 1px solid #ddd; }
        button { padding: 7px 12px; }
        .session { margin-top: 20px; padding: 10px; background: #eee; word-break: break-all; }
    </style>
</head>
<body>
<div class="box">
    <h1>Task List</h1>

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
    <?php endif; ?>

    <p><a href="index.php">Back to Task Manager</a></p>

    <div class="session">
        <strong>Session ID:</strong>
        <?php echo htmlspecialchars(session_id()); ?>
    </div>
</div>
</body>
</html>
