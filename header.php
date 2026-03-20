<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once 'connect.php';

$current_balance = 0;
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $balance_query = "SELECT balance FROM users WHERE id = '$uid'";
    $balance_result = mysqli_query($conn, $balance_query);
    if ($balance_result && $row = mysqli_fetch_assoc($balance_result)) {
        $current_balance = $row['balance'];
        $_SESSION['balance'] = $current_balance;
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casino 67</title>
    <link rel="stylesheet" href="header-style.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
    </style>
</head>
<body>
<header>
    <div class="container1">
        <nav>
            
            <a href="index.php" class="logo1">CASINO<span>67</span></a>
            
            <ul class="nav-links1">

                <li><a href="index.php"><i class="fas fa-home"></i> Strona główna</a></li>
                <li><a href="games.php"><i class="fas fa-gamepad"></i>Gry</a></li>

                <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <li><a href="wallet.php"><i class="fas fa-ticket-alt"></i>Portfel</a></li>
                <?php endif; ?>

                <li><a href="daily_case.php"><i class="fas fa-box-open"></i> Codzienna Skrzynia</a></li>
            </ul>

            <div class="auth-buttons1">
                <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <span class="balance-display">
                        <img src="sztywny.png" alt="sztywny"><?php echo number_format($current_balance, 2); ?> SZC
                    </span>
                    <span class="user-welcome1" style="display: flex; align-items: center; gap: 5px; margin-right: 10px;">
                        <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </span>
                    <a href="logout.php" class="btn1 btn-primary1">
                        <i class="fas fa-sign-out-alt"></i> Wyloguj
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn1 btn-secondary1">
                        <i class="fas fa-sign-in-alt"></i> Zaloguj
                    </a>
                    <a href="register.php" class="btn1 btn-primary1">
                        <i class="fas fa-user-plus"></i> Zarejestruj
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>