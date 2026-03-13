<?php 
session_start();
include 'connect.php';

if (!isset($_SESSION["logged_in"]) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['amount'])) {
    $amount = floatval($_POST['amount']);
    if ($amount > 0) {
        $update_sql = "UPDATE users SET balance = balance + $amount WHERE id = $user_id";
        if (mysqli_query($conn, $update_sql)) {
            $trans_sql = "INSERT INTO transactions (user_id, amount, type, description) VALUES ($user_id, $amount, 'deposit', 'Doładowanie portfela')";
            mysqli_query($conn, $trans_sql);
            $success = "Portfel został doładowany kwotą " . number_format($amount, 2) . " SZC!";
        }
    }
}

// Fetch current balance again to be sure
$res = mysqli_query($conn, "SELECT balance FROM users WHERE id = $user_id");
$user_data = mysqli_fetch_assoc($res);
$balance = $user_data['balance'];
$_SESSION['balance'] = $balance;

include 'header.php';
?>

<div class="container" style="padding: 4rem 0;">
    <h1 style="text-align: center; margin-bottom: 3rem; font-size: 2.5rem; color: var(--secondary-blue);">
        Twój Portfel
    </h1>
    
    <?php if(!empty($success)): ?>
        <div class="alert alert-success" style="max-width: 600px; margin: 0 auto 2rem;">
            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
        </div>
    <?php endif; ?>
    
    <div class="wallet-card" style="max-width: 600px; margin: 0 auto;">
        <p style="color: var(--text-gray); font-size: 1.1rem;">Aktualne Saldo</p>
        <div class="balance-large">
            <i class="fas fa-coins"></i> <?php echo number_format($balance, 2); ?> SZC
        </div>
        
        <div style="margin-top: 3rem; border-top: 1px solid #333; padding-top: 2rem;">
            <h3 style="color: var(--text-light); margin-bottom: 1.5rem;">Doładuj Portfel</h3>
            <p style="color: var(--text-gray); margin-bottom: 2rem;">Wybierz kwotę doładowania (to tylko symulacja):</p>
            
            <div class="topup-grid">
                <form action="" method="POST" style="display: contents;">
                    <button type="submit" name="amount" value="100" class="topup-option" style="background: none; color: inherit; font: inherit;">
                        <h4>100 SZC</h4>
                        <p style="color: var(--text-gray); font-size: 0.8rem; margin-top: 0.5rem;">Niby za 10 zł</p>
                    </button>
                    <button type="submit" name="amount" value="500" class="topup-option" style="background: none; color: inherit; font: inherit;">
                        <h4>500 SZC</h4>
                        <p style="color: var(--text-gray); font-size: 0.8rem; margin-top: 0.5rem;">Niby za 45 zł</p>
                    </button>
                    <button type="submit" name="amount" value="1000" class="topup-option" style="background: none; color: inherit; font: inherit;">
                        <h4>1000 SZC</h4>
                        <p style="color: var(--text-gray); font-size: 0.8rem; margin-top: 0.5rem;">Niby za 80 zł</p>
                    </button>
                    <button type="submit" name="amount" value="5000" class="topup-option" style="background: none; color: inherit; font: inherit;">
                        <h4>5000 SZC</h4>
                        <p style="color: var(--text-gray); font-size: 0.8rem; margin-top: 0.5rem;">Niby za 350 zł</p>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div style="max-width: 800px; margin: 4rem auto 0;">
        <h3 style="color: var(--secondary-blue); margin-bottom: 1.5rem; text-align: center;">Ostatnie Transakcje</h3>
        <table style="width: 100%; border-collapse: collapse; background: var(--secondary-dark); border-radius: 8px; overflow: hidden;">
            <thead>
                <tr style="background: var(--primary-blue); color: white; text-align: left;">
                    <th style="padding: 1rem;">Data</th>
                    <th style="padding: 1rem;">Typ</th>
                    <th style="padding: 1rem;">Opis</th>
                    <th style="padding: 1rem;">Kwota</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $trans_query = "SELECT * FROM transactions WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 10";
                $trans_res = mysqli_query($conn, $trans_query);
                if (mysqli_num_rows($trans_res) > 0) {
                    while ($t = mysqli_fetch_assoc($trans_res)) {
                        $color = ($t['type'] == 'win' || $t['type'] == 'deposit' || $t['type'] == 'daily') ? 'var(--success)' : 'var(--error)';
                        $sign = ($t['type'] == 'win' || $t['type'] == 'deposit' || $t['type'] == 'daily') ? '+' : '-';
                        ?>
                        <tr style="border-bottom: 1px solid #333; color: var(--text-gray);">
                            <td style="padding: 1rem;"><?php echo $t['created_at']; ?></td>
                            <td style="padding: 1rem; text-transform: capitalize;"><?php echo $t['type']; ?></td>
                            <td style="padding: 1rem;"><?php echo $t['description']; ?></td>
                            <td style="padding: 1rem; color: <?php echo $color; ?>; font-weight: bold;">
                                <?php echo $sign . number_format($t['amount'], 2); ?> SZC
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='4' style='padding: 2rem; text-align: center; color: var(--text-gray);'>Brak transakcji w historii.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
