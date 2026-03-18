<?php
session_start();
include 'connect.php';

header('Content-Type: application/json'); // Wymuśmy JSON

if (!isset($_SESSION["logged_in"]) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Musisz być zalogowany, aby grać!']);
    exit;
}

$user_id = $_SESSION['user_id'];
$game_id = intval($_POST['game_id']);
$bet = floatval($_POST['bet']);
$side = isset($_POST['side']) ? $_POST['side'] : '';
$number = isset($_POST['number']) ? intval($_POST['number']) : null;

if ($bet <= 0) {
    echo json_encode(['success' => false, 'message' => 'Podaj poprawną kwotę zakładu!']);
    exit;
}

// Sprawdź czy użytkownik istnieje i ma środki
$res = mysqli_query($conn, "SELECT balance FROM users WHERE id = $user_id");
if (!$res || mysqli_num_rows($res) == 0) {
    echo json_encode(['success' => false, 'message' => 'Nie znaleziono użytkownika!']);
    exit;
}

$user = mysqli_fetch_assoc($res);
if ($user['balance'] < $bet) {
    echo json_encode(['success' => false, 'message' => 'Masz za mało SZC w portfelu!']);
    exit;
}

// Pobierz dane gry - usuńmy config z zapytania jeśli nie ma takiej kolumny
$res = mysqli_query($conn, "SELECT type, name FROM games WHERE id = $game_id");
if (!$res || mysqli_num_rows($res) == 0) {
    echo json_encode(['success' => false, 'message' => 'Nie znaleziono gry!']);
    exit;
}

$game = mysqli_fetch_assoc($res);

$win = false;
$win_amount = 0;
$result_label = '';
$details = [];

// Funkcja dla kolorów w ruletce
function getRouletteColor($number) {
    if ($number == 0) return 'green';
    $reds = [1, 3, 5, 7, 9, 12, 14, 16, 18, 19, 21, 23, 25, 27, 30, 32, 34, 36];
    return in_array($number, $reds) ? 'red' : 'black';
}

try {
    if ($game['type'] == 'coinflip') {
        // COINFLIP - 2x wygrana
        $flip = (rand(0, 1) == 0) ? 'heads' : 'tails';
        $result_label = $flip;
        $details['result'] = $flip;
        $details['side'] = $side;
        
        if ($side == $flip) {
            $win = true;
            $win_amount = $bet * 2;
        }
        
    } elseif ($game['type'] == 'slots') {
        // SLOTS
        $symbols = ['🍒', '🍋', '🍊', '🍇', '💎', '7️⃣', '🎰', '⭐'];
        $reels = [];
        
        for ($i = 0; $i < 3; $i++) {
            $reels[] = $symbols[array_rand($symbols)];
        }
        
        $details['reels'] = $reels;
        $result_label = implode(' - ', $reels);
        
        if ($reels[0] == $reels[1] && $reels[1] == $reels[2]) {
            $win = true;
            if ($reels[0] == '7️⃣') {
                $win_amount = $bet * 50;
            } elseif ($reels[0] == '💎') {
                $win_amount = $bet * 20;
            } elseif ($reels[0] == '🎰') {
                $win_amount = $bet * 15;
            } elseif ($reels[0] == '⭐') {
                $win_amount = $bet * 10;
            } else {
                $win_amount = $bet * 5;
            }
        } elseif ($reels[0] == $reels[1] || $reels[1] == $reels[2] || $reels[0] == $reels[2]) {
            $win = true;
            $win_amount = $bet * 2;
        }
        
    } elseif ($game['type'] == 'roulette') {
        // ROULETTE
        $roulette_result = rand(0, 36);
        $result_label = $roulette_result;
        $details['result'] = $roulette_result;
        $details['color'] = getRouletteColor($roulette_result);
        $details['parity'] = ($roulette_result > 0 && $roulette_result % 2 == 0) ? 'even' : (($roulette_result > 0) ? 'odd' : 'none');
        $details['range'] = ($roulette_result >= 1 && $roulette_result <= 18) ? '1-18' : (($roulette_result >= 19 && $roulette_result <= 36) ? '19-36' : 'none');
        
        if ($side == 'number' && $number !== null && $number == $roulette_result) {
            $win = true;
            $win_amount = $bet * 36;
        } elseif ($side == 'red' && getRouletteColor($roulette_result) == 'red') {
            $win = true;
            $win_amount = $bet * 2;
        } elseif ($side == 'black' && getRouletteColor($roulette_result) == 'black') {
            $win = true;
            $win_amount = $bet * 2;
        } elseif ($side == 'even' && $roulette_result > 0 && $roulette_result % 2 == 0) {
            $win = true;
            $win_amount = $bet * 2;
        } elseif ($side == 'odd' && $roulette_result > 0 && $roulette_result % 2 == 1) {
            $win = true;
            $win_amount = $bet * 2;
        } elseif ($side == '1-18' && $roulette_result >= 1 && $roulette_result <= 18) {
            $win = true;
            $win_amount = $bet * 2;
        } elseif ($side == '19-36' && $roulette_result >= 19 && $roulette_result <= 36) {
            $win = true;
            $win_amount = $bet * 2;
        }
    }

    // Aktualizacja bazy danych
    mysqli_begin_transaction($conn);
    
    if ($win) {
        $net_change = $win_amount;
        $update_sql = "UPDATE users SET balance = balance + ($win_amount - $bet) WHERE id = $user_id";
        $trans_sql = "INSERT INTO transactions (user_id, amount, type, description) VALUES ($user_id, $win_amount, 'win', 'Wygrana w " . mysqli_real_escape_string($conn, $game['name']) . "')";
    } else {
        $net_change = -$bet;
        $update_sql = "UPDATE users SET balance = balance - $bet WHERE id = $user_id";
        $trans_sql = "INSERT INTO transactions (user_id, amount, type, description) VALUES ($user_id, $bet, 'bet', 'Zakład w " . mysqli_real_escape_string($conn, $game['name']) . "')";
    }

    if (!mysqli_query($conn, $update_sql)) {
        throw new Exception('Błąd aktualizacji salda: ' . mysqli_error($conn));
    }
    
    if (!mysqli_query($conn, $trans_sql)) {
        throw new Exception('Błąd zapisu transakcji: ' . mysqli_error($conn));
    }
    
    mysqli_commit($conn);
    
    // Pobierz nowe saldo
    $res = mysqli_query($conn, "SELECT balance FROM users WHERE id = $user_id");
    $new_data = mysqli_fetch_assoc($res);
    $new_balance = number_format($new_data['balance'], 2, '.', '');
    $_SESSION['balance'] = $new_data['balance'];
    
    echo json_encode([
        'success' => true,
        'win' => $win,
        'win_amount' => $win ? number_format($win_amount, 2, '.', '') : '0.00',
        'result' => $result_label,
        'details' => $details,
        'new_balance' => $new_balance,
        'bet' => $bet
    ]);
    
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo json_encode(['success' => false, 'message' => 'Błąd: ' . $e->getMessage()]);
}
?>