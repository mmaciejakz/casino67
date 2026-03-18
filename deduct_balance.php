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

// Sprawdź czy użytkownik ma wystarczające środki
$check_sql = "SELECT balance FROM users WHERE id = $user_id";
$res = mysqli_query($conn, $check_sql);
$user = mysqli_fetch_assoc($res);

if ($user['balance'] < $amount) {
    echo json_encode(['success' => false, 'message' => 'Insufficient funds']);
    exit;
}

// Odejmij środki
$update_sql = "UPDATE users SET balance = balance - $amount WHERE id = $user_id";
if (mysqli_query($conn, $update_sql)) {
    // Zapisz transakcję - używamy -$amount żeby pokazać wydatek
    $trans_sql = "INSERT INTO transactions (user_id, amount, type, description) VALUES ($user_id, -$amount, 'game', 'Opłata za zakręcenie skrzynką')";
    mysqli_query($conn, $trans_sql);
    
    // Pobierz nowe saldo
    $res = mysqli_query($conn, "SELECT balance FROM users WHERE id = $user_id");
    $new_data = mysqli_fetch_assoc($res);
    $new_balance = $new_data['balance']; // Nie formatuj tutaj
    $_SESSION['balance'] = $new_data['balance'];
    
    echo json_encode(['success' => true, 'new_balance' => $new_balance]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>