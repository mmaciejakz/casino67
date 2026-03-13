<?php
session_start();
include 'connect.php';

if (!isset($_SESSION["logged_in"]) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;

if ($amount <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid amount']);
    exit;
}

// Check if user can claim daily bonus again to prevent double claiming
$check_sql = "SELECT last_daily_claim FROM users WHERE id = $user_id";
$res = mysqli_query($conn, $check_sql);
$user = mysqli_fetch_assoc($res);

if ($user['last_daily_claim'] !== null) {
    $last_claim = strtotime($user['last_daily_claim']);
    $now = time();
    $diff = $now - $last_claim;
    if ($diff < 86400) {
        echo json_encode(['success' => false, 'message' => 'Already claimed today']);
        exit;
    }
}

// Update balance and last claim time
$update_sql = "UPDATE users SET balance = balance + $amount, last_daily_claim = NOW() WHERE id = $user_id";
if (mysqli_query($conn, $update_sql)) {
    // Record transaction
    $trans_sql = "INSERT INTO transactions (user_id, amount, type, description) VALUES ($user_id, $amount, 'daily', 'Codzienna skrzynia')";
    mysqli_query($conn, $trans_sql);
    
    // Fetch new balance
    $res = mysqli_query($conn, "SELECT balance FROM users WHERE id = $user_id");
    $new_data = mysqli_fetch_assoc($res);
    $new_balance = number_format($new_data['balance'], 2);
    $_SESSION['balance'] = $new_data['balance'];
    
    echo json_encode(['success' => true, 'new_balance' => $new_balance]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
