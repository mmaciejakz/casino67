<?php
session_start();
include 'connect.php';

if (!isset($_SESSION["logged_in"]) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Musisz być zalogowany, aby grać!']);
    exit;
}

$user_id = $_SESSION['user_id'];
$game_id = intval($_POST['game_id']);
$bet = floatval($_POST['bet']);
$side = isset($_POST['side']) ? $_POST['side'] : '';

if ($bet <= 0) {
    echo json_encode(['success' => false, 'message' => 'Podaj poprawną kwotę zakładu!']);
    exit;
}

// Fetch user balance
$res = mysqli_query($conn, "SELECT balance FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($res);
if ($user['balance'] < $bet) {
    echo json_encode(['success' => false, 'message' => 'Masz za mało SZC w portfelu!']);
    exit;
}

// Fetch game type
$res = mysqli_query($conn, "SELECT type, name FROM games WHERE id = $game_id");
$game = mysqli_fetch_assoc($res);

$win = false;
$win_amount = 0;
$result_label = '';

if ($game['type'] == 'coinflip') {
    $flip = (rand(0, 1) == 0) ? 'heads' : 'tails';
    $result_label = $flip;
    if ($side == $flip) {
        $win = true;
        $win_amount = $bet * 1.9; // 1.9x payout
    }
} elseif ($game['type'] == 'slots') {
    // Simple slots logic
    $roll = rand(1, 100);
    if ($roll <= 10) { // 10% chance to win big
        $win = true;
        $win_amount = $bet * 5;
    } elseif ($roll <= 30) { // 20% chance to win small
        $win = true;
        $win_amount = $bet * 2;
    }
} elseif ($game['type'] == 'roulette') {
    // Simple roulette logic (50/50 red/black for simplicity)
    if (rand(0, 1) == 0) {
        $win = true;
        $win_amount = $bet * 2;
    }
}

// Process results
if ($win) {
    $net_change = $win_amount - $bet;
    $update_sql = "UPDATE users SET balance = balance + $net_change WHERE id = $user_id";
    $trans_sql = "INSERT INTO transactions (user_id, amount, type, description) VALUES ($user_id, $win_amount, 'win', 'Wygrana w " . $game['name'] . "')";
} else {
    $update_sql = "UPDATE users SET balance = balance - $bet WHERE id = $user_id";
    $trans_sql = "INSERT INTO transactions (user_id, amount, type, description) VALUES ($user_id, $bet, 'bet', 'Zakład w " . $game['name'] . "')";
}

if (mysqli_query($conn, $update_sql)) {
    mysqli_query($conn, $trans_sql);
    
    // Fetch new balance
    $res = mysqli_query($conn, "SELECT balance FROM users WHERE id = $user_id");
    $new_data = mysqli_fetch_assoc($res);
    $new_balance = number_format($new_data['balance'], 2);
    $_SESSION['balance'] = $new_data['balance'];
    
    echo json_encode([
        'success' => true, 
        'win' => $win, 
        'win_amount' => number_format($win_amount, 2),
        'result' => $result_label,
        'new_balance' => $new_balance
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Błąd bazy danych!']);
}
?>
