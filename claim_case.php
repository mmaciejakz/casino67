<?php
session_start();
include 'connect.php';

$user_id = $_SESSION['user_id'];

$prizes = [
    ['val' => 50, 'weight' => 60],
    ['val' => 100, 'weight' => 20],
    ['val' => 200, 'weight' => 10],
    ['val' => 500, 'weight' => 7],
    ['val' => 1000, 'weight' => 2],
    ['val' => 2500, 'weight' => 1]
];

function getPrize($prizes) {
    $total = array_sum(array_column($prizes, 'weight'));
    $rand = rand(1, $total);

    foreach ($prizes as $p) {
        if ($rand <= $p['weight']) {
            return $p['val'];
        }
        $rand -= $p['weight'];
    }
}

$win = getPrize($prizes);

// dodaj do salda
mysqli_query($conn, "UPDATE users SET balance = balance + $win WHERE id = $user_id");

// pobierz nowe saldo
$res = mysqli_query($conn, "SELECT balance FROM users WHERE id = $user_id");
$newBalance = mysqli_fetch_assoc($res)['balance'];

echo json_encode([
    'success' => true,
    'win' => $win,
    'new_balance' => $newBalance
]);