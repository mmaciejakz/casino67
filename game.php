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

// ========== DODANE PRZEKIEROWANIE DLA GRY CASE ==========
if ($game['type'] == 'case') {
    header("Location: case.php");
    exit;
}
// =======================================================

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

                <!-- COINFLIP INTERFACE -->
                <?php if ($game['type'] == 'coinflip'): ?>
                    <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
                        <button class="btn btn-secondary coin-side" data-side="heads" style="flex: 1; padding: 1.5rem;">
                            <i class="fas fa-coins" style="color: var(--gold);"></i> Orzeł
                        </button>
                        <button class="btn btn-secondary coin-side" data-side="tails" style="flex: 1; padding: 1.5rem;">
                            <i class="fas fa-coins" style="color: silver;"></i> Reszka
                        </button>
                    </div>
                    <button id="play-coinflip" class="btn btn-primary" style="width: 100%; padding: 1.2rem; font-size: 1.2rem;" disabled>
                        <i class="fas fa-play"></i> Graj
                    </button>
                <?php endif; ?>

                <!-- ROULETTE INTERFACE -->
                <?php if ($game['type'] == 'roulette'): ?>
                    <div style="margin-bottom: 2rem;">
                        <!-- Proste zakłady zewnętrzne -->
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; margin-bottom: 1rem;">
                            <button class="btn btn-secondary roulette-simple" data-bet="red" style="background: #dc2626; color: white; border: 1px solid #991b1b;">
                                <i class="fas fa-circle"></i> Czerwony (x2)
                            </button>
                            <button class="btn btn-secondary roulette-simple" data-bet="black" style="background: #1f2937; color: white; border: 1px solid #111827;">
                                <i class="fas fa-circle"></i> Czarny (x2)
                            </button>
                            <button class="btn btn-secondary roulette-simple" data-bet="even">Parzyste (x2)</button>
                            <button class="btn btn-secondary roulette-simple" data-bet="odd">Nieparzyste (x2)</button>
                            <button class="btn btn-secondary roulette-simple" data-bet="1-18">1-18 (x2)</button>
                            <button class="btn btn-secondary roulette-simple" data-bet="19-36">19-36 (x2)</button>
                        </div>

                        <!-- Siatka numerów -->
                        <div style="margin-top: 1.5rem;">
                            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-light);">Lub wybierz konkretny numer (x36):</label>
                            <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 0.3rem; margin-bottom: 0.5rem;">
                                <?php for($i = 0; $i <= 36; $i++): 
                                    $color = 'black';
                                    if($i == 0) $color = '#059669';
                                    else {
                                        $reds = [1,3,5,7,9,12,14,16,18,19,21,23,25,27,30,32,34,36];
                                        $color = in_array($i, $reds) ? '#dc2626' : '#1f2937';
                                    }
                                ?>
                                    <button class="btn btn-secondary roulette-number" data-number="<?php echo $i; ?>" 
                                            style="background: <?php echo $color; ?>; color: white; padding: 0.5rem; font-size: 0.9rem; border: 1px solid <?php echo $i == 0 ? '#047857' : ($color == '#dc2626' ? '#991b1b' : '#111827'); ?>;">
                                        <?php echo $i; ?>
                                    </button>
                                    <?php if($i % 6 == 0 && $i > 0): ?>
                                        </div><div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 0.3rem; margin-bottom: 0.3rem;">
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <!-- Podgląd wybranego zakładu -->
                        <div style="margin-top: 1rem; padding: 1rem; background: var(--primary-dark); border-radius: 6px; text-align: center;" id="bet-preview">
                            <span style="color: var(--text-gray);">Nie wybrano zakładu</span>
                        </div>
                    </div>
                    <button id="play-roulette" class="btn btn-primary" style="width: 100%; padding: 1.2rem; font-size: 1.2rem;" disabled>
                        <i class="fas fa-play"></i> Graj
                    </button>
                <?php endif; ?>

                <!-- SLOTS INTERFACE -->
                <?php if ($game['type'] == 'slots'): ?>
                    <div style="text-align: center; margin-bottom: 2rem;">
                        <p style="color: var(--text-gray); margin-bottom: 1rem;">Wylosuj 3 symbole i wygraj!</p>
                        <div style="display: flex; gap: 1rem; justify-content: center; font-size: 3rem; margin: 1rem 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1rem; border-radius: 10px;">
                            <span id="slot1">🍒</span>
                            <span id="slot2">🍒</span>
                            <span id="slot3">🍒</span>
                        </div>
                        <p style="color: var(--text-gray); font-size: 0.9rem;">
                            3x7️⃣ = x50 | 3x💎 = x20 | 3x🎰 = x15 | 3x⭐ = x10 | 3xowoce = x5 | 2x = x2
                        </p>
                    </div>
                    <button id="play-slots" class="btn btn-primary" style="width: 100%; padding: 1.2rem; font-size: 1.2rem;">
                        <i class="fas fa-play"></i> Graj
                    </button>
                <?php endif; ?>

                <div id="game-result" style="margin-top: 2rem; text-align: center; font-size: 1.5rem; font-weight: bold; min-height: 2.5rem;"></div>
            </div>
        </div>
        
        <!-- GAME AREA -->
        <div style="flex: 1; min-width: 300px; display: flex; align-items: center; justify-content: center; background: #0a0a0a; border-radius: 12px; border: 1px solid #333; min-height: 400px; position: relative; overflow: hidden;">
            
            <!-- COINFLIP ANIMATION -->
            <?php if ($game['type'] == 'coinflip'): ?>
                <div id="coin-container" style="text-align: center;">
                    <div id="coin" style="display: none; width: 150px; height: 150px; border-radius: 50%; position: relative; transition: transform 1.5s cubic-bezier(0.15, 0, 0.15, 1); transform-style: preserve-3d; margin: 0 auto;">
                        <!-- Przód monety (Orzeł) -->
                        <div style="position: absolute; width: 100%; height: 100%; border-radius: 50%; background: var(--gold); border: 10px solid #d97706; box-shadow: 0 0 20px rgba(251, 191, 36, 0.5); display: flex; align-items: center; justify-content: center; backface-visibility: hidden;">
                            <i class="fas fa-eagle" style="font-size: 4rem; color: #d97706;"></i>
                        </div>
                        <!-- Tył monety (Reszka) -->
                        <div style="position: absolute; width: 100%; height: 100%; border-radius: 50%; background: silver; border: 10px solid #94a3b8; box-shadow: 0 0 20px rgba(148, 163, 184, 0.5); display: flex; align-items: center; justify-content: center; backface-visibility: hidden; transform: rotateY(180deg);">
                            <i class="fas fa-coins" style="font-size: 4rem; color: #94a3b8;"></i>
                        </div>
                    </div>
                    <p id="coin-status" style="color: var(--text-gray); margin-top: 1rem;">Wybierz stronę i kliknij Graj</p>
                </div>
            <?php endif; ?>

            <!-- ROULETTE ANIMATION - WERSJA SVG -->
            <?php if ($game['type'] == 'roulette'): ?>
                <div id="roulette-container" style="text-align: center;">
                    <div style="position: relative; width: 450px; height: 450px; margin: 0 auto;">
                        
                        <!-- KOŁO RULETKI JAKO SVG -->
                        <svg id="roulette-wheel" width="450" height="450" viewBox="0 0 450 450" style="transform: rotate(0deg); transition: transform 3s cubic-bezier(0.2, 0.9, 0.3, 1);">
                            
                            <?php
                            // Prawidłowa kolejność numerów w ruletce europejskiej
                            $numbers = [0, 32, 15, 19, 4, 21, 2, 25, 17, 34, 6, 27, 13, 36, 11, 30, 8, 23, 10, 5, 24, 16, 33, 1, 20, 14, 31, 9, 22, 18, 29, 7, 28, 12, 35, 3, 26];
                            $reds = [1, 3, 5, 7, 9, 12, 14, 16, 18, 19, 21, 23, 25, 27, 30, 32, 34, 36];
                            
                            $centerX = 225;
                            $centerY = 225;
                            $radius = 200;
                            $innerRadius = 140;
                            $anglePerSegment = 360 / 37;
                            $startAngle = 0;
                            
                            for($i = 0; $i < 37; $i++):
                                $number = $numbers[$i];
                                $endAngle = $startAngle + $anglePerSegment;
                                $midAngle = $startAngle + ($anglePerSegment / 2);
                                
                                // Określenie koloru
                                if($number == 0) {
                                    $color = '#2e7d32'; // Zielony
                                } else {
                                    $color = in_array($number, $reds) ? '#d32f2f' : '#212121'; // Czerwony lub czarny
                                }
                                
                                // Konwersja na radiany dla obliczeń
                                $startRad = deg2rad($startAngle - 90);
                                $endRad = deg2rad($endAngle - 90);
                                $midRad = deg2rad($midAngle - 90);
                                
                                // Współrzędne punktów
                                $x1 = $centerX + $radius * cos($startRad);
                                $y1 = $centerY + $radius * sin($startRad);
                                $x2 = $centerX + $radius * cos($endRad);
                                $y2 = $centerY + $radius * sin($endRad);
                                
                                // Ścieżka SVG dla segmentu
                                $largeArc = ($anglePerSegment > 180) ? 1 : 0;
                                ?>
                                <!-- Segment koła -->
                                <path d="M <?php echo $centerX; ?>,<?php echo $centerY; ?> L <?php echo $x1; ?>,<?php echo $y1; ?> A <?php echo $radius; ?>,<?php echo $radius; ?> 0 <?php echo $largeArc; ?>,1 <?php echo $x2; ?>,<?php echo $y2; ?> Z" 
                                    fill="<?php echo $color; ?>" 
                                    stroke="#d4af37" 
                                    stroke-width="1.5"
                                    opacity="0.95" />
                                
                                <!-- Numer -->
                                <?php
                                $textX = $centerX + ($innerRadius + 30) * cos($midRad);
                                $textY = $centerY + ($innerRadius + 30) * sin($midRad);
                                ?>
                                <text x="<?php echo $textX; ?>" y="<?php echo $textY; ?>" 
                                    fill="white" 
                                    font-size="14" 
                                    font-weight="bold" 
                                    text-anchor="middle" 
                                    dominant-baseline="middle"
                                    transform="rotate(<?php echo $midAngle - 90; ?>, <?php echo $textX; ?>, <?php echo $textY; ?>)">
                                    <?php echo $number; ?>
                                </text>
                                
                            <?php 
                                $startAngle = $endAngle;
                            endfor; 
                            ?>
                            
                            <!-- Środek koła -->
                            <circle cx="225" cy="225" r="50" fill="#d4af37" stroke="#996515" stroke-width="3"/>
                            <circle cx="225" cy="225" r="40" fill="#0a0a0a" stroke="#d4af37" stroke-width="2"/>
                            <text x="225" y="235" id="roulette-display" fill="white" font-size="24" font-weight="bold" text-anchor="middle">0</text>
                            
                            <!-- Złote akcenty -->
                            <circle cx="225" cy="225" r="55" fill="none" stroke="#d4af37" stroke-width="2" stroke-dasharray="5,5"/>
                        </svg>
                        
                        <!-- WSKAŹNIK (stały na górze) -->
                        <div style="position: absolute; top: -20px; left: 50%; transform: translateX(-50%); z-index: 50;">
                            <div style="width: 0; height: 0; border-left: 25px solid transparent; border-right: 25px solid transparent; border-top: 50px solid #d4af37; filter: drop-shadow(0 0 15px gold);"></div>
                            <div style="width: 12px; height: 12px; background: white; border-radius: 50%; margin: -38px auto 0; box-shadow: 0 0 20px white;"></div>
                        </div>
                    </div>
                    
                    <p id="roulette-status" style="color: var(--text-gray); margin-top: 2rem; font-size: 1.2rem;">Wybierz zakład i kliknij Graj</p>
                </div>

                <style>
                #roulette-wheel {
                    transform: rotate(0deg);
                    filter: drop-shadow(0 0 20px rgba(0,0,0,0.5));
                }
                
                .roulette-number-highlight {
                    animation: pulse 0.5s ease infinite;
                }
                
                @keyframes pulse {
                    0% { r: 15; opacity: 1; }
                    50% { r: 20; opacity: 0.7; }
                    100% { r: 15; opacity: 1; }
                }
                </style>
            <?php endif; ?>

            <!-- SLOTS ANIMATION -->
            <?php if ($game['type'] == 'slots'): ?>
                <div id="slots-container" style="text-align: center;">
                    <div id="slots-machine" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 2rem; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.3);">
                        <div style="display: flex; gap: 1rem; justify-content: center; font-size: 4rem; background: white; padding: 1rem; border-radius: 10px; border: 3px solid gold;">
                            <span class="slot-reel" id="slot-reel1">🍒</span>
                            <span class="slot-reel" id="slot-reel2">🍒</span>
                            <span class="slot-reel" id="slot-reel3">🍒</span>
                        </div>
                    </div>
                    <p id="slots-status" style="color: var(--text-gray); margin-top: 1rem;">Kliknij Graj aby zagrać</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
@keyframes spinWheel {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(1440deg); }
}
.slot-reel {
    display: inline-block;
}
.slot-spinning {
    animation: slotSpin 0.1s infinite;
}
@keyframes slotSpin {
    0% { transform: translateY(0); }
    25% { transform: translateY(-10px); }
    50% { transform: translateY(0); }
    75% { transform: translateY(10px); }
    100% { transform: translateY(0); }
}
</style>

<script>
// COINFLIP LOGIC
<?php if ($game['type'] == 'coinflip'): ?>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Coinflip initialized');
    
    const playBtn = document.getElementById('play-coinflip');
    const betInput = document.getElementById('bet-amount');
    const resultDiv = document.getElementById('game-result');
    const sideBtns = document.querySelectorAll('.coin-side');
    const coin = document.getElementById('coin');
    const coinStatus = document.getElementById('coin-status');
    
    let selectedSide = null;

    sideBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            sideBtns.forEach(b => b.classList.replace('btn-primary', 'btn-secondary'));
            this.classList.replace('btn-secondary', 'btn-primary');
            selectedSide = this.dataset.side;
            playBtn.disabled = false;
            coinStatus.innerText = `Wybrano: ${selectedSide === 'heads' ? 'Orzeł' : 'Reszka'}`;
        });
    });

    playBtn.addEventListener('click', function() {
        const bet = parseFloat(betInput.value);
        
        if (isNaN(bet) || bet <= 0) {
            alert('Podaj poprawną kwotę zakładu');
            return;
        }

        if (!selectedSide) {
            alert('Wybierz stronę monety!');
            return;
        }

        playBtn.disabled = true;
        resultDiv.innerText = 'Gramy...';
        resultDiv.style.color = 'var(--text-gray)';
        coinStatus.innerText = 'Losowanie...';
        
        coin.style.display = 'block';
        coin.style.transition = 'none';
        coin.style.transform = 'rotateY(0deg)';
        void coin.offsetWidth;
        coin.style.transition = 'transform 1.5s ease-out';

        const formData = new URLSearchParams();
        formData.append('game_id', '<?php echo $game_id; ?>');
        formData.append('bet', bet);
        formData.append('side', selectedSide);

        fetch('play_game.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData.toString()
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert(data.message);
                playBtn.disabled = false;
                resultDiv.innerText = '';
                coin.style.display = 'none';
                coinStatus.innerText = 'Wybierz stronę i kliknij Graj';
                return;
            }

            const rotations = 10;
            const finalRotation = data.result === 'heads' 
                ? rotations * 360 
                : (rotations * 360) + 180;
            
            coin.style.transform = `rotateY(${finalRotation}deg)`;

            setTimeout(() => {
                if (data.win) {
                    resultDiv.innerText = `WYGRANA! +${data.win_amount} SZC`;
                    resultDiv.style.color = 'var(--success)';
                    coinStatus.innerText = `Wypadł: ${data.result === 'heads' ? 'Orzeł' : 'Reszka'} - WYGRANA!`;
                } else {
                    resultDiv.innerText = `PRZEGRANA! -${bet} SZC`;
                    resultDiv.style.color = 'var(--error)';
                    coinStatus.innerText = `Wypadł: ${data.result === 'heads' ? 'Orzeł' : 'Reszka'} - PRZEGRANA`;
                }
                
                const balanceDisplay = document.querySelector('.balance-display');
                if (balanceDisplay) {
                    balanceDisplay.innerHTML = `<i class="fas fa-coins"></i> ${data.new_balance} SZC`;
                }
                
                playBtn.disabled = false;
            }, 2000);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Wystąpił błąd');
            playBtn.disabled = false;
        });
    });
});
<?php endif; ?>

// ROULETTE LOGIC - Z RESETEM DO POZYCJI STARTOWEJ
<?php if ($game['type'] == 'roulette'): ?>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Roulette initialized');
    
    const playBtn = document.getElementById('play-roulette');
    const betInput = document.getElementById('bet-amount');
    const resultDiv = document.getElementById('game-result');
    const simpleBtns = document.querySelectorAll('.roulette-simple');
    const numberBtns = document.querySelectorAll('.roulette-number');
    const betPreview = document.getElementById('bet-preview');
    const rouletteWheel = document.getElementById('roulette-wheel');
    const rouletteDisplay = document.getElementById('roulette-display');
    const rouletteStatus = document.getElementById('roulette-status');
    
    let selectedBet = null;
    let selectedNumber = null;
    let currentRotation = 0;

    // Prawidłowa kolejność numerów
    const numberOrder = [0, 32, 15, 19, 4, 21, 2, 25, 17, 34, 6, 27, 13, 36, 11, 30, 8, 23, 10, 5, 24, 16, 33, 1, 20, 14, 31, 9, 22, 18, 29, 7, 28, 12, 35, 3, 26];
    const segmentAngle = 360 / 37;

    // Ustaw pozycję startową - między polem 26 a 0
    function resetToStartPosition() {
        // Szybki reset bez animacji
        rouletteWheel.style.transition = 'none';
        rouletteWheel.style.transform = 'rotate(0deg)';
        currentRotation = 0;
        
        // Wymuś repaint
        void rouletteWheel.offsetWidth;
        
        // Przywróć transition
        rouletteWheel.style.transition = 'transform 3s cubic-bezier(0.2, 0.9, 0.3, 1)';
        
        console.log('Koło zresetowane do pozycji startowej');
    }

    // Obsługa prostych zakładów
    simpleBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            simpleBtns.forEach(b => b.classList.replace('btn-primary', 'btn-secondary'));
            numberBtns.forEach(b => b.classList.replace('btn-primary', 'btn-secondary'));
            this.classList.replace('btn-secondary', 'btn-primary');
            selectedBet = this.dataset.bet;
            selectedNumber = null;
            playBtn.disabled = false;
            
            const betNames = {
                'red': 'Czerwony',
                'black': 'Czarny',
                'even': 'Parzyste',
                'odd': 'Nieparzyste',
                '1-18': '1-18',
                '19-36': '19-36'
            };
            betPreview.innerHTML = `<span style="color: var(--secondary-blue);">Wybrano: ${betNames[selectedBet]} (x2)</span>`;
        });
    });

    // Obsługa zakładów na numery
    numberBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            simpleBtns.forEach(b => b.classList.replace('btn-primary', 'btn-secondary'));
            numberBtns.forEach(b => b.classList.replace('btn-primary', 'btn-secondary'));
            this.classList.replace('btn-secondary', 'btn-primary');
            selectedBet = 'number';
            selectedNumber = parseInt(this.dataset.number);
            playBtn.disabled = false;
            
            let colorText = '';
            if(selectedNumber === 0) colorText = 'zielony';
            else {
                const reds = [1,3,5,7,9,12,14,16,18,19,21,23,25,27,30,32,34,36];
                colorText = reds.includes(selectedNumber) ? 'czerwony' : 'czarny';
            }
            betPreview.innerHTML = `<span style="color: var(--secondary-blue);">Wybrano numer: ${selectedNumber} (${colorText}) - x36</span>`;
        });
    });

    playBtn.addEventListener('click', function() {
        const bet = parseFloat(betInput.value);
        
        if (isNaN(bet) || bet <= 0) {
            alert('Podaj poprawną kwotę zakładu');
            return;
        }

        if (!selectedBet) {
            alert('Wybierz zakład!');
            return;
        }

        playBtn.disabled = true;
        resultDiv.innerText = 'Gramy...';
        resultDiv.style.color = 'var(--text-gray)';
        rouletteStatus.innerText = 'Koło się kręci...';

        // ZRESETUJ KOŁO DO POZYCJI STARTOWEJ
        resetToStartPosition();

        const formData = new URLSearchParams();
        formData.append('game_id', '<?php echo $game_id; ?>');
        formData.append('bet', bet);
        formData.append('side', selectedBet);
        if (selectedNumber !== null) {
            formData.append('number', selectedNumber);
        }

        fetch('play_game.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData.toString()
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert(data.message);
                playBtn.disabled = false;
                resultDiv.innerText = '';
                return;
            }

            // Krótkie opóźnienie, żeby reset był widoczny
            setTimeout(() => {
                // Znajdź indeks wylosowanego numeru
                const resultNum = parseInt(data.details.result);
                const resultIndex = numberOrder.indexOf(resultNum);
                
                // Oblicz kąt obrotu - środek segmentu ma być pod wskaźnikiem
                const targetAngle = (resultIndex * segmentAngle) + (segmentAngle / 2);
                
                // Wskaźnik jest na górze, koło kręci się zgodnie z ruchem wskazówek zegara
                const rotationToTop = 360 - targetAngle;
                
                // Dodaj pełne obroty
                const fullSpins = 5 + Math.floor(Math.random() * 5);
                const finalRotation = currentRotation + (fullSpins * 360) + rotationToTop;
                
                console.log('Numer:', resultNum);
                console.log('Target angle:', targetAngle);
                console.log('Rotation to top:', rotationToTop);
                console.log('Final rotation:', finalRotation);
                
                // Animacja
                rouletteWheel.style.transform = `rotate(${finalRotation}deg)`;
                currentRotation = finalRotation;

                setTimeout(() => {
                    rouletteDisplay.textContent = resultNum;
                    
                    if (data.win) {
                        resultDiv.innerText = `WYGRANA! +${data.win_amount} SZC`;
                        resultDiv.style.color = 'var(--success)';
                        rouletteStatus.innerText = `Wynik: ${resultNum} (${data.details.color}) - WYGRANA!`;
                    } else {
                        resultDiv.innerText = `PRZEGRANA! -${bet} SZC`;
                        resultDiv.style.color = 'var(--error)';
                        rouletteStatus.innerText = `Wynik: ${resultNum} (${data.details.color}) - PRZEGRANA`;
                    }
                    
                    const balanceDisplay = document.querySelector('.balance-display');
                    if (balanceDisplay) {
                        balanceDisplay.innerHTML = `<i class="fas fa-coins"></i> ${data.new_balance} SZC`;
                    }
                    
                    playBtn.disabled = false;
                }, 3000);
            }, 50); // Krótkie opóźnienie na reset
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Wystąpił błąd');
            playBtn.disabled = false;
        });
    });
});
<?php endif; ?>

// SLOTS LOGIC (bez zmian - działa świetnie)
<?php if ($game['type'] == 'slots'): ?>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Slots initialized');
    
    const playBtn = document.getElementById('play-slots');
    const betInput = document.getElementById('bet-amount');
    const resultDiv = document.getElementById('game-result');
    const slotsStatus = document.getElementById('slots-status');
    const reel1 = document.getElementById('slot-reel1');
    const reel2 = document.getElementById('slot-reel2');
    const reel3 = document.getElementById('slot-reel3');
    
    const symbols = ['🍒', '🍋', '🍊', '🍇', '💎', '7️⃣', '🎰', '⭐'];

    function spinReels() {
        reel1.classList.add('slot-spinning');
        reel2.classList.add('slot-spinning');
        reel3.classList.add('slot-spinning');
        
        let spinCount = 0;
        const spinInterval = setInterval(() => {
            reel1.innerText = symbols[Math.floor(Math.random() * symbols.length)];
            reel2.innerText = symbols[Math.floor(Math.random() * symbols.length)];
            reel3.innerText = symbols[Math.floor(Math.random() * symbols.length)];
            spinCount++;
        }, 50);
        
        return spinInterval;
    }

    playBtn.addEventListener('click', function() {
        const bet = parseFloat(betInput.value);
        
        if (isNaN(bet) || bet <= 0) {
            alert('Podaj poprawną kwotę zakładu');
            return;
        }

        playBtn.disabled = true;
        resultDiv.innerText = 'Gramy...';
        resultDiv.style.color = 'var(--text-gray)';
        slotsStatus.innerText = 'Losowanie...';
        
        const spinInterval = spinReels();

        const formData = new URLSearchParams();
        formData.append('game_id', '<?php echo $game_id; ?>');
        formData.append('bet', bet);

        fetch('play_game.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData.toString()
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert(data.message);
                playBtn.disabled = false;
                resultDiv.innerText = '';
                clearInterval(spinInterval);
                reel1.classList.remove('slot-spinning');
                reel2.classList.remove('slot-spinning');
                reel3.classList.remove('slot-spinning');
                return;
            }

            setTimeout(() => {
                clearInterval(spinInterval);
                reel1.classList.remove('slot-spinning');
                reel2.classList.remove('slot-spinning');
                reel3.classList.remove('slot-spinning');
                
                reel1.innerText = data.details.reels[0];
                reel2.innerText = data.details.reels[1];
                reel3.innerText = data.details.reels[2];
                
                if (data.win) {
                    resultDiv.innerText = `WYGRANA! +${data.win_amount} SZC`;
                    resultDiv.style.color = 'var(--success)';
                    slotsStatus.innerText = `${data.details.reels.join(' - ')} - WYGRANA!`;
                } else {
                    resultDiv.innerText = `PRZEGRANA! -${bet} SZC`;
                    resultDiv.style.color = 'var(--error)';
                    slotsStatus.innerText = `${data.details.reels.join(' - ')} - PRZEGRANA`;
                }
                
                const balanceDisplay = document.querySelector('.balance-display');
                if (balanceDisplay) {
                    balanceDisplay.innerHTML = `<i class="fas fa-coins"></i> ${data.new_balance} SZC`;
                }
                
                playBtn.disabled = false;
            }, 1500);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Wystąpił błąd');
            playBtn.disabled = false;
        });
    });
});
<?php endif; ?>
</script>

<?php include 'footer.php'; ?>