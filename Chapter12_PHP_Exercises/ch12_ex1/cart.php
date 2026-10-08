<?php
// Exercise 12-1: Cart page

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

// Empty Cart: remove only cart data.
// The current session and session ID remain.
if (isset($_GET['action']) && $_GET['action'] === 'empty') {
    $_SESSION['cart'] = [];
    header('Location: cart.php');
    exit;
}

// End Session and Delete Cookie:
// remove all session data, destroy the session, and delete the cookie.
if (isset($_GET['action']) && $_GET['action'] === 'end') {
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"] ?? '',
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();

    // Start a new session so this page can continue normally.
    session_set_cookie_params([
        'lifetime' => $session_lifetime,
        'path' => '/',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
    $_SESSION['cart'] = [];

    header('Location: cart.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart - Cart</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .box { max-width: 650px; }
        li { margin: 8px 0; }
        a { display: inline-block; margin-right: 12px; padding: 7px 12px; border: 1px solid #999; text-decoration: none; color: #111; }
        .session { margin-top: 20px; padding: 10px; background: #eee; word-break: break-all; }
    </style>
</head>
<body>
<div class="box">
    <h1>Cart</h1>

    <?php if (empty($_SESSION['cart'])): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <li><?php echo htmlspecialchars($item); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <p>
        <a href="index.php">Continue Shopping</a>
        <a href="cart.php?action=empty">Empty Cart</a>
        <a href="cart.php?action=end"
           onclick="return confirm('End the session and delete the cookie?');">
            End Session and Delete Cookie
        </a>
    </p>

    <div class="session">
        <strong>Session ID:</strong>
        <?php echo htmlspecialchars(session_id()); ?>
    </div>
</div>
</body>
</html>
