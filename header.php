<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once 'connect.php';

// Fetch current user balance if logged in
$current_balance = 0;
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $balance_query = "SELECT balance FROM users WHERE id = '$uid'";
    $balance_result = mysqli_query($conn, $balance_query);
    if ($balance_result && $row = mysqli_fetch_assoc($balance_result)) {
        $current_balance = $row['balance'];
        $_SESSION['balance'] = $current_balance; // Keep session in sync
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casino 67 - Najlepsze Kasyno Online</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
</head>
<body>

<header>
    <div class="container">
        <nav>
            <a href="index.php" class="logo">CASINO<span>67</span></a>
            <ul class="nav-links">
                <li><a href="index.php"><i class="fas fa-home"></i> Start</a></li>
                <li><a href="games.php"><i class="fas fa-gamepad"></i> Gry</a></li>
                <li><a href="daily_case.php"><i class="fas fa-box-open"></i> Codzienna Skrzynia</a></li>
                <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <li><a href="wallet.php"><i class="fas fa-wallet"></i> Portfel</a></li>
                <?php endif; ?>
            </ul>
            <div class="auth-buttons">
                <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <span class="balance-display">
                        <i class="fas fa-coins"></i> <?php echo number_format($current_balance, 2); ?> SZC
                    </span>
                    <span class="user-welcome">
                        <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </span>
                    <a href="logout.php" class="btn btn-secondary">
                        <i class="fas fa-sign-out-alt"></i> Wyloguj
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-secondary">
                        <i class="fas fa-sign-in-alt"></i> Zaloguj
                    </a>
                    <a href="register.php" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Zarejestruj
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>
