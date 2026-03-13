<?php 
session_start();
include 'connect.php';

if (!isset($_GET['id'])) {
    header("Location: games.php");
    exit;
}

$game_id = intval($_GET['id']);
$sql = "SELECT * FROM games WHERE id = $game_id";
$result = mysqli_query($conn, $sql);
$game = mysqli_fetch_assoc($result);

if (!$game) {
    header("Location: games.php");
    exit;
}

include 'header.php';
?>

<div class="container" style="padding: 4rem 0;">
    <div style="display: flex; gap: 3rem; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 300px;">
            <div style="background: var(--secondary-dark); border-radius: 12px; padding: 2rem; border: 1px solid #333;">
                <h1 style="color: var(--secondary-blue); margin-bottom: 1rem;"><?php echo $game['name']; ?></h1>
                <p style="color: var(--text-gray); margin-bottom: 2rem;"><?php echo $game['description']; ?></p>
                
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-light);">Kwota zakładu (SZC):</label>
                    <input type="number" id="bet-amount" value="10" min="1" step="1" 
                           style="width: 100%; padding: 0.8rem; background: var(--primary-dark); border: 1px solid #333; border-radius: 6px; color: white; font-size: 1.2rem;">
                </div>

                <?php if ($game['type'] == 'coinflip'): ?>
                    <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
                        <button class="btn btn-secondary side-btn" data-side="heads" style="flex: 1; padding: 1.5rem;">
                            <i class="fas fa-coins" style="color: var(--gold);"></i> Orzeł
                        </button>
                        <button class="btn btn-secondary side-btn" data-side="tails" style="flex: 1; padding: 1.5rem;">
                            <i class="fas fa-coins" style="color: silver;"></i> Reszka
                        </button>
                    </div>
                    <button id="play-btn" class="btn btn-primary" style="width: 100%; padding: 1.2rem; font-size: 1.2rem;" disabled>
                        <i class="fas fa-play"></i> Graj
                    </button>
                <?php else: ?>
                    <button id="play-btn" class="btn btn-primary" style="width: 100%; padding: 1.2rem; font-size: 1.2rem;">
                        <i class="fas fa-play"></i> Graj
                    </button>
                <?php endif; ?>

                <div id="game-result" style="margin-top: 2rem; text-align: center; font-size: 1.5rem; font-weight: bold; min-height: 2.5rem;"></div>
            </div>
        </div>
        
        <div style="flex: 1; min-width: 300px; display: flex; align-items: center; justify-content: center; background: #0a0a0a; border-radius: 12px; border: 1px solid #333; min-height: 400px; position: relative; overflow: hidden;">
            <div id="game-animation" style="text-align: center;">
                <i class="fas fa-gamepad" style="font-size: 10rem; color: #1e293b;"></i>
                <p style="color: #1e293b; margin-top: 1rem; font-weight: bold;">OBSZAR GRY</p>
            </div>
            
            <?php if ($game['type'] == 'coinflip'): ?>
                <div id="coin" style="display: none; width: 150px; height: 150px; border-radius: 50%; background: var(--gold); border: 10px solid #d97706; box-shadow: 0 0 20px rgba(251, 191, 36, 0.5); position: relative; transition: transform 2s cubic-bezier(0.15, 0, 0.15, 1); transform-style: preserve-3d;">
                    <div style="position: absolute; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; backface-visibility: hidden;">
                        <i class="fas fa-eagle" style="font-size: 4rem; color: #d97706;"></i>
                    </div>
                    <div style="position: absolute; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; backface-visibility: hidden; transform: rotateY(180deg); background: silver; border-radius: 50%; border: 10px solid #94a3b8;">
                        <i class="fas fa-coins" style="font-size: 4rem; color: #94a3b8;"></i>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const playBtn = document.getElementById('play-btn');
    const betInput = document.getElementById('bet-amount');
    const resultDiv = document.getElementById('game-result');
    const sideBtns = document.querySelectorAll('.side-btn');
    const coin = document.getElementById('coin');
    const gameAnim = document.getElementById('game-animation');
    
    let selectedSide = null;
    
    sideBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            sideBtns.forEach(b => b.classList.replace('btn-primary', 'btn-secondary'));
            this.classList.replace('btn-secondary', 'btn-primary');
            selectedSide = this.dataset.side;
            playBtn.disabled = false;
        });
    });

    playBtn.addEventListener('click', function() {
        const bet = parseFloat(betInput.value);
        if (isNaN(bet) || bet <= 0) {
            alert('Podaj poprawną kwotę zakładu');
            return;
        }

        playBtn.disabled = true;
        resultDiv.innerText = 'Gramy...';
        resultDiv.style.color = 'var(--text-gray)';
        
        if (coin) {
            gameAnim.style.display = 'none';
            coin.style.display = 'block';
            coin.style.transform = 'rotateY(0deg)';
        }

        // Send play request to server
        fetch('play_game.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `game_id=<?php echo $game_id; ?>&bet=${bet}&side=${selectedSide}`
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert(data.message);
                playBtn.disabled = false;
                resultDiv.innerText = '';
                return;
            }

            // Animation logic
            if (coin) {
                const rotations = 10;
                const finalRotation = data.result === 'heads' ? rotations * 360 : (rotations * 360) + 180;
                coin.style.transform = `rotateY(${finalRotation}deg)`;
            }

            setTimeout(() => {
                if (data.win) {
                    resultDiv.innerText = `WYGRANA! +${data.win_amount} SZC`;
                    resultDiv.style.color = 'var(--success)';
                } else {
                    resultDiv.innerText = `PRZEGRANA! -${bet} SZC`;
                    resultDiv.style.color = 'var(--error)';
                }
                
                // Update balance in header
                const balanceDisplay = document.querySelector('.balance-display');
                if (balanceDisplay) {
                    balanceDisplay.innerHTML = `<i class="fas fa-coins"></i> ${data.new_balance} SZC`;
                }
                
                playBtn.disabled = false;
            }, coin ? 2000 : 500);
        });
    });
});
</script>

<?php include 'footer.php'; ?>
