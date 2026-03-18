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

            <!-- ROULETTE ANIMATION -->
            <?php if ($game['type'] == 'roulette'): ?>
                <div id="roulette-container" style="text-align: center;">
                    <div style="position: relative; width: 400px; height: 400px; margin: 0 auto;">
                        <!-- Koło ruletki -->
                        <div id="roulette-wheel" style="width: 100%; height: 100%; border-radius: 50%; position: relative; overflow: hidden; border: 5px solid #d4af37; box-shadow: 0 0 30px rgba(212, 175, 55, 0.3);">
                            
                            <!-- Segmenty koła -->
                            <?php
                            // Prawidłowa kolejność w ruletce europejskiej (zgodnie z kierunkiem wskazówek zegara)
                            $numbers = [0, 32, 15, 19, 4, 21, 2, 25, 17, 34, 6, 27, 13, 36, 11, 30, 8, 23, 10, 5, 24, 16, 33, 1, 20, 14, 31, 9, 22, 18, 29, 7, 28, 12, 35, 3, 26];
                            $colors = [];
                            
                            // Przypisz kolory
                            foreach($numbers as $num) {
                                if($num == 0) $colors[] = '#059669'; // zielony
                                else {
                                    $reds = [1,3,5,7,9,12,14,16,18,19,21,23,25,27,30,32,34,36];
                                    $colors[] = in_array($num, $reds) ? '#dc2626' : '#1f2937';
                                }
                            }
                            
                            $startAngle = 0;
                            $anglePerSegment = 360 / 37;
                            
                            for($i = 0; $i < 37; $i++):
                                $endAngle = $startAngle + $anglePerSegment;
                                $midAngle = $startAngle + ($anglePerSegment / 2);
                                $radians = deg2rad($midAngle);
                                
                                // Współrzędne dla numeru (na zewnątrz koła)
                                $radius = 160;
                                $x = 200 + cos($radians) * $radius;
                                $y = 200 + sin($radians) * $radius;
                            ?>
                                <!-- Segment koła -->
                                <div style="position: absolute; width: 0; height: 0; left: 200px; top: 200px; 
                                            border-left: 200px solid transparent; 
                                            border-right: 200px solid transparent; 
                                            border-bottom: 200px solid <?php echo $colors[$i]; ?>;
                                            transform: rotate(<?php echo $startAngle; ?>deg) translateY(-50%);
                                            transform-origin: 0 0; opacity: 0.95;">
                                </div>
                                
                                <!-- Biała linia oddzielająca -->
                                <div style="position: absolute; left: 200px; top: 0; width: 2px; height: 200px; 
                                            background: rgba(255, 255, 255, 0.3); 
                                            transform-origin: bottom; 
                                            transform: translateX(-50%) rotate(<?php echo $startAngle; ?>deg);">
                                </div>
                                
                            <?php 
                                $startAngle = $endAngle;
                            endfor; 
                            ?>
                            
                            <!-- Numerki na kole (umieszczone na zewnątrz) -->
                            <?php 
                            $startAngle = 0;
                            for($i = 0; $i < 37; $i++):
                                $midAngle = $startAngle + ($anglePerSegment / 2);
                                $radians = deg2rad($midAngle);
                                $radius = 135;
                                $x = 200 + cos($radians) * $radius - 12;
                                $y = 200 + sin($radians) * $radius - 12;
                            ?>
                                <div class="roulette-number-label" data-num="<?php echo $numbers[$i]; ?>" 
                                    style="position: absolute; left: <?php echo $x; ?>px; top: <?php echo $y; ?>px; 
                                            width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;
                                            color: white; font-size: 16px; font-weight: bold; 
                                            text-shadow: 1px 1px 2px black, 0 0 5px rgba(0,0,0,0.5);
                                            z-index: 10; transform: rotate(<?php echo -$midAngle; ?>deg);">
                                    <?php echo $numbers[$i]; ?>
                                </div>
                            <?php 
                                $startAngle += $anglePerSegment;
                            endfor; 
                            ?>
                        </div>
                        
                        <!-- Środek koła -->
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100px; height: 100px; background: #d4af37; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 20; border: 3px solid #0a0a0a; box-shadow: 0 0 30px rgba(212, 175, 55, 0.5);">
                            <div style="width: 80px; height: 80px; background: #0a0a0a; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #d4af37; font-weight: bold; font-size: 2rem; border: 2px solid #d4af37;" id="roulette-display">0</div>
                        </div>
                        
                        <!-- Wskaźnik (stały) -->
                        <div style="position: absolute; top: -20px; left: 50%; transform: translateX(-50%); width: 0; height: 0; border-left: 25px solid transparent; border-right: 25px solid transparent; border-top: 50px solid #d4af37; filter: drop-shadow(0 0 15px gold); z-index: 30;"></div>
                        <div style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); width: 12px; height: 12px; background: white; border-radius: 50%; z-index: 31; box-shadow: 0 0 15px white;"></div>
                        
                        <!-- Oświetlenie wewnętrzne -->
                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border-radius: 50%; box-shadow: inset 0 0 50px rgba(0,0,0,0.8); pointer-events: none; z-index: 25;"></div>
                    </div>
                    <p id="roulette-status" style="color: var(--text-gray); margin-top: 2rem;">Wybierz zakład i kliknij Graj</p>
                </div>
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

// ROULETTE LOGIC
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

    // Prawidłowa kolejność numerów w ruletce europejskiej (zgodnie z kierunkiem wskazówek zegara)
    const numberOrder = [0, 32, 15, 19, 4, 21, 2, 25, 17, 34, 6, 27, 13, 36, 11, 30, 8, 23, 10, 5, 24, 16, 33, 1, 20, 14, 31, 9, 22, 18, 29, 7, 28, 12, 35, 3, 26];
    const segmentAngle = 360 / 37; // 9.7297 stopnia

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
        rouletteStatus.innerText = 'Losowanie...';
        
        // Usuń poprzednie podświetlenia
        document.querySelectorAll('.roulette-number-label').forEach(label => {
            label.classList.remove('winner-highlight');
            label.style.color = 'white';
            label.style.fontSize = '16px';
            label.style.textShadow = '1px 1px 2px black, 0 0 5px rgba(0,0,0,0.5)';
        });

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

            // Znajdź indeks wylosowanego numeru
            const resultNum = parseInt(data.details.result);
            const resultIndex = numberOrder.indexOf(resultNum);
            
            // Oblicz kąt dla wylosowanego numeru (środek segmentu)
            // Wskaźnik jest na górze (90 stopni w tej orientacji)
            const targetAngle = (resultIndex * segmentAngle) + (segmentAngle / 2);
            
            // Chcemy, żeby środek segmentu znalazł się pod wskaźnikiem (na górze)
            // Wskaźnik jest na 90°, więc odejmujemy targetAngle od 90
            const rotationToTop = 90 - targetAngle;
            
            // Dodajemy pełne obroty (5-8 obrotów dla efektu)
            const fullSpins = 5 + Math.floor(Math.random() * 4);
            const finalRotation = currentRotation + (fullSpins * 360) + rotationToTop;
            
            // Animacja koła
            rouletteWheel.style.transition = 'transform 2.5s cubic-bezier(0.2, 0.9, 0.3, 1)';
            rouletteWheel.style.transform = `rotate(${finalRotation}deg)`;
            currentRotation = finalRotation;

            setTimeout(() => {
                rouletteDisplay.innerText = resultNum;
                
                // Podświetl wylosowany numer na kole
                document.querySelectorAll('.roulette-number-label').forEach(label => {
                    if (parseInt(label.dataset.num) === resultNum) {
                        label.style.color = 'gold';
                        label.style.fontSize = '20px';
                        label.style.textShadow = '0 0 10px gold, 0 0 20px gold';
                        label.classList.add('winner-highlight');
                    }
                });
                
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
            }, 2500);
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