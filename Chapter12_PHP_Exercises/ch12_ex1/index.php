<?php
// Exercise 12-1: Shopping Cart
// Session cookie persists for 3 years.
$session_lifetime = 60 * 60 * 24 * 365 * 3;
session_set_cookie_params([
    'lifetime' => $session_lifetime,
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item = trim($_POST['item'] ?? '');
    if ($item !== '') {
        $_SESSION['cart'][] = $item;
    }
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart - Products</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .box { max-width: 650px; }
        form { margin: 10px 0; }
        button, a { padding: 7px 12px; }
        .session { margin-top: 20px; padding: 10px; background: #eee; }
    </style>
</head>
<body>
<div class="box">
    <h1>Shopping Cart</h1>
    <p>Select an item to add to the cart.</p>

    <form method="post">
        <input type="hidden" name="item" value="Laptop">
        <button type="submit">Add Laptop</button>
    </form>

    <form method="post">
        <input type="hidden" name="item" value="Keyboard">
        <button type="submit">Add Keyboard</button>
    </form>

    <form method="post">
        <input type="hidden" name="item" value="Mouse">
        <button type="submit">Add Mouse</button>
    </form>

    <form method="post">
        <input type="hidden" name="item" value="Headset">
        <button type="submit">Add Headset</button>
    </form>

    <p><a href="cart.php">View Cart (<?php echo count($_SESSION['cart']); ?>)</a></p>

    <div class="session">
        <strong>Session ID:</strong>
        <?php echo htmlspecialchars(session_id()); ?>
    </div>
</div>
</body>
</html>
