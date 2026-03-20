<?php 
session_start();
include 'connect.php';

if (!isset($_SESSION["logged_in"]) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Pobierz aktualne saldo użytkownika
$balance_sql = "SELECT balance FROM users WHERE id = $user_id";
$balance_res = mysqli_query($conn, $balance_sql);
$user_balance = mysqli_fetch_assoc($balance_res)['balance'];

include 'header.php';
?>

<style>
#win-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.85);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

#modal-box {
    background: linear-gradient(145deg, #1e1e1e, #151515);
    padding: 2.5rem 2.5rem 1.5rem 2.5rem;
    border-radius: 25px;
    border: 3px solid #f59e0b;
    text-align: center;
    position: relative;
    min-width: 400px;
    box-shadow: 0 10px 30px rgba(245, 158, 11, 0.4);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
}

#modal-box:hover {
    box-shadow: 0 15px 40px rgba(245, 158, 11, 0.6);
    transform: translateY(-2px);
}

#close-modal {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 2rem;
    cursor: pointer;
    color: #aaa;
    font-weight: bold;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s ease;
    background: rgba(255,255,255,0.05);
}

#close-modal:hover {
    color: #f59e0b;
    background: rgba(245, 158, 11, 0.15);
    transform: scale(1.1);
}

#win-modal h2 {
    color: #f59e0b;
    font-size: 2.5rem;
    margin: 0.5rem 0 1rem 0;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-shadow: 0 2px 5px rgba(245, 158, 11, 0.3);
    width: 100%;
}

.image-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
    margin: 0.5rem 0;
}

#win-modal img {
    width: 120px;
    height: 120px;
    filter: drop-shadow(0 5px 15px rgba(245, 158, 11, 0.6));
    transition: transform 0.3s ease;
}

#win-modal img:hover {
    transform: scale(1.05) rotate(5deg);
}

.win-text {
    font-size: 2rem;
    color: white;
    margin: 1rem 0 1.5rem 0;
    background: rgba(0,0,0,0.3);
    padding: 0.8rem 1.5rem;
    border-radius: 50px;
    border: 1px solid rgba(245, 158, 11, 0.3);
    text-align: center;
}

#modal-amount {
    color: #f59e0b;
    font-weight: bold;
    font-size: 3rem;
}

#modal-close-btn {
    background: linear-gradient(145deg, #f59e0b, #d97706);
    border: none;
    color: black;
    font-weight: bold;
    padding: 0.8rem 2.5rem;
    border-radius: 40px;
    font-size: 1.2rem;
    cursor: pointer;
    margin: 0.5rem 0 1rem 0;
    border: 2px solid #fbbf24;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
    box-shadow: 0 5px 15px rgba(245, 158, 11, 0.3);
    width: auto;
    min-width: 150px;
}

#modal-close-btn:hover {
    background: linear-gradient(145deg, #fbbf24, #f59e0b);
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.5);
    border-color: #f59e0b;
}

#modal-close-btn:active {
    transform: scale(0.95);
}

.modal-footer {
    margin-top: 0.5rem;
    color: #666;
    font-size: 0.9rem;
    width: 100%;
    text-align: center;
}

@keyframes modalAppear {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

#modal-box {
    animation: modalAppear 0.3s ease-out;
}

.error-message {
    color: #ef4444;
    margin-top: 1rem;
    font-size: 1rem;
    display: none;
    padding: 0.5rem;
    background: rgba(239, 68, 68, 0.1);
    border-radius: 8px;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

#case-container {
    position: relative;
    width: 100%;
    height: 250px;
    overflow: hidden;
    background: #0a0a0a;
    border-radius: 12px;
    margin-bottom: 2rem;
    border: 1px solid #333;
}

#case-track {
    display: flex;
    gap: 10px;
    position: absolute;
    left: 0;
    will-change: transform;
    transition: transform 0s linear;
}

.case-item {
    min-width: 150px;
    height: 180px;
    background: #1a1a1a;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 1px solid #333;
    flex-shrink: 0;
}

.case-item img {
    width: 70px;
    height: 70px;
    margin-bottom: 0.8rem;
    object-fit: contain;
}

.case-item span {
    font-weight: bold;
    font-size: 1.1rem;
}
</style>

<div class="container" style="padding: 4rem 0;">
    <h1 style="text-align: center; margin-bottom: 3rem; font-size: 2.5rem; color: var(--gold);">
        🎰 Skrzynia Za 75 SZC 🎰
    </h1>
    
    <div style="max-width: 800px; margin: 0 auto; background: var(--secondary-dark); border-radius: 20px; padding: 3rem; border: 2px solid var(--gold); box-shadow: 0 0 30px rgba(251, 191, 36, 0.2); text-align: center;">
        
        <div style="margin-bottom: 1rem; font-size: 1.2rem;">
            Twoje saldo: <span style="color: var(--gold); font-weight: bold;" id="current-balance"><?php echo number_format($user_balance, 2); ?></span> SZC
        </div>
        
        <div id="case-container">
            <div style="position: absolute; top: 0; bottom: 0; left: 50%; width: 4px; background: var(--gold); z-index: 10; box-shadow: 0 0 15px var(--gold); transform: translateX(-2px);"></div>
            <div id="case-track"></div>
        </div>
        
        <button id="spin-case-btn" class="btn btn-gold" style="padding: 1.2rem 3rem; font-size: 1.3rem;">
            <i class="fas fa-sync-alt"></i> Zakręć (75 SZC)
        </button>
        
        <div id="error-message" class="error-message">
            ❌ Nie masz wystarczających środków! (75 SZC wymagane)
        </div>
        
        <div style="margin-top: 1rem; font-size: 0.9rem; color: var(--text-gray);">
            * Każde zakręcenie kosztuje 75 SZC
        </div>
    </div>
    
    <div style="margin-top: 4rem; text-align: center;">
        <h3 style="color: var(--text-light); margin-bottom: 1.5rem;">Co możesz wygrać?</h3>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <div style="padding: 1.5rem; background: var(--secondary-dark); border-radius: 10px; min-width: 120px; border-bottom: 4px solid #94a3b8;">
                <div style="font-size: 1.2rem; font-weight: bold;">50 SZC</div>
                <div style="font-size: 0.8rem; color: var(--text-gray);">Częste (60%)</div>
            </div>
            <div style="padding: 1.5rem; background: var(--secondary-dark); border-radius: 10px; min-width: 120px; border-bottom: 4px solid #94a3b8;">
                <div style="font-size: 1.2rem; font-weight: bold;">100 SZC</div>
                <div style="font-size: 0.8rem; color: var(--text-gray);">Częste (20%)</div>
            </div>
            <div style="padding: 1.5rem; background: var(--secondary-dark); border-radius: 10px; min-width: 120px; border-bottom: 4px solid #3b82f6;">
                <div style="font-size: 1.2rem; font-weight: bold;">200 SZC</div>
                <div style="font-size: 0.8rem; color: var(--text-gray);">Rzadkie (10%)</div>
            </div>
            <div style="padding: 1.5rem; background: var(--secondary-dark); border-radius: 10px; min-width: 120px; border-bottom: 4px solid #8b5cf6;">
                <div style="font-size: 1.2rem; font-weight: bold;">500 SZC</div>
                <div style="font-size: 0.8rem; color: var(--text-gray);">Epickie (7%)</div>
            </div>
            <div style="padding: 1.5rem; background: var(--secondary-dark); border-radius: 10px; min-width: 120px; border-bottom: 4px solid #8b5cf6;">
                <div style="font-size: 1.2rem; font-weight: bold;">1000 SZC</div>
                <div style="font-size: 0.8rem; color: var(--text-gray);">Epickie (2%)</div>
            </div>
            <div style="padding: 1.5rem; background: var(--secondary-dark); border-radius: 10px; min-width: 120px; border-bottom: 4px solid #fbbf24;">
                <div style="font-size: 1.2rem; font-weight: bold;">2500 SZC</div>
                <div style="font-size: 0.8rem; color: var(--text-gray);">Legendarne (1%)</div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="win-modal">
    <div id="modal-box">
        <span id="close-modal">✖</span>
        <h2>WYGRANA!</h2>
        
        <div class="image-container">
            <img src="cfb65be8-d96a-45c9-8769-648a36cf9dc2-removebg-preview.png">
            <div class="win-text">
                <span id="modal-amount">0</span> SZC
            </div>
        </div>
        
        <button id="modal-close-btn">OK</button>
        <div class="modal-footer">✦ Gratulacje! ✦</div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const track = document.getElementById('case-track');
    const spinBtn = document.getElementById('spin-case-btn');
    const modal = document.getElementById('win-modal');
    const closeModal = document.getElementById('close-modal');
    const modalCloseBtn = document.getElementById('modal-close-btn');
    const modalAmount = document.getElementById('modal-amount');
    const errorMessage = document.getElementById('error-message');
    const currentBalanceSpan = document.getElementById('current-balance');
    
    let currentBalance = <?php echo $user_balance; ?>;
    const spinCost = 75;
    let isSpinning = false;
    let spinTimeout = null;
    let animationTimeout = null;
    
    const possiblePrizes = [
        { val: 50, color: '#94a3b8', weight: 60 },
        { val: 100, color: '#94a3b8', weight: 20 },
        { val: 200, color: '#3b82f6', weight: 10 },
        { val: 500, color: '#8b5cf6', weight: 7 },
        { val: 1000, color: '#8b5cf6', weight: 2 },
        { val: 2500, color: '#fbbf24', weight: 1 }
    ];
    
    function getRandomPrize() {
        let totalWeight = possiblePrizes.reduce((a, b) => a + b.weight, 0);
        let random = Math.random() * totalWeight;
        for (let p of possiblePrizes) {
            if (random < p.weight) {
                return p;
            }
            random -= p.weight;
        }
        return possiblePrizes[0];
    }
    
    // Generuj elementy raz, na początku
    function generateItems() {
        track.innerHTML = '';
        for (let i = 0; i < 150; i++) {
            const prize = getRandomPrize();
            const div = document.createElement('div');
            div.className = 'case-item';
            div.style.borderBottom = `5px solid ${prize.color}`;
            div.setAttribute('data-value', prize.val);
            div.innerHTML = `
                <img src="cfb65be8-d96a-45c9-8769-648a36cf9dc2-removebg-preview.png">
                <span>${prize.val} SZC</span>
            `;
            track.appendChild(div);
        }
    }
    
    generateItems();
    
    const itemWidth = 160;
    const container = document.getElementById('case-container');
    const containerWidth = container.offsetWidth;
    const centerOffset = containerWidth / 2;
    
    // Ustaw pozycję początkową
    const totalItems = document.querySelectorAll('.case-item').length;
    const startIndex = Math.floor(totalItems / 2);
    let currentPosition = (startIndex * itemWidth) - centerOffset + (itemWidth / 2);
    track.style.transform = `translateX(-${currentPosition}px)`;
    
    function updateBalanceUI(newBalance) {
        currentBalance = parseFloat(newBalance);
        if (currentBalanceSpan) {
            currentBalanceSpan.innerText = currentBalance.toFixed(2);
        }
    }
    
    function closeModalFunction() {
        modal.style.display = 'none';
    }
    
    closeModal.addEventListener('click', closeModalFunction);
    if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeModalFunction);

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModalFunction();
        }
    });
    
    spinBtn.addEventListener('click', function() {
        if (isSpinning) return;
        
        if (currentBalance < spinCost) {
            errorMessage.style.display = 'block';
            setTimeout(() => {
                errorMessage.style.display = 'none';
            }, 3000);
            return;
        }
        
        errorMessage.style.display = 'none';
        spinBtn.disabled = true;
        isSpinning = true;
        
        // Losuj nagrodę
        const selectedPrize = getRandomPrize();
        
        // Znajdź elementy z wygraną
        const items = document.querySelectorAll('.case-item');
        let winningIndices = [];
        items.forEach((item, idx) => {
            const val = parseInt(item.getAttribute('data-value'));
            if (val === selectedPrize.val) {
                winningIndices.push(idx);
            }
        });
        
        // Wybierz losowy indeks wygranej
        let targetIndex = winningIndices[Math.floor(Math.random() * winningIndices.length)];
        
        // Upewnij się, że targetIndex jest w bezpiecznym zakresie
        if (targetIndex < 50) targetIndex = targetIndex + 200;
        if (targetIndex > items.length - 50) targetIndex = targetIndex - 200;
        
        // Oblicz pozycję docelową
        const targetOffset = (targetIndex * itemWidth) - centerOffset + (itemWidth / 2);
        
        // Liczba pełnych obrotów (20-30)
        const totalItemsCount = items.length;
        const extraSpins = Math.floor(Math.random() * 20) + 25; // 25-45 obrotów
        const extraDistance = extraSpins * totalItemsCount * itemWidth;
        
        // Oblicz nową pozycję
        const totalWidth = totalItemsCount * itemWidth;

        const newPosition = (currentPosition + extraDistance + targetOffset) % totalWidth;
        
        // Uruchom animację
        track.style.transition = 'none';
        track.style.transform = `translateX(-${currentPosition}px)`;

// wymuś reflow
        track.offsetHeight;

        track.style.transition = `transform 3.5s cubic-bezier(0.2, 0.9, 0.3, 1.05)`;
        track.style.transform = `translateX(-${newPosition}px)`;
        
        // Zapamiętaj nową pozycję (ale z modulo, żeby nie rosła w nieskończoność)
        currentPosition = newPosition % (totalItemsCount * itemWidth);
        
        if (spinTimeout) clearTimeout(spinTimeout);
        if (animationTimeout) clearTimeout(animationTimeout);
        
        animationTimeout = setTimeout(() => {
            // Odejmij koszt i dodaj wygraną
            fetch('deduct_balance.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `amount=${spinCost}`
            })
            .then(response => response.json())
            .then(deductData => {
                if (deductData.success) {
                    updateBalanceUI(deductData.new_balance);
                    return fetch('claim_case.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `case=75`
                    });
                } else {
                    throw new Error(deductData.message || 'Nie udało się odjąć środków');
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modalAmount.innerText = data.win;
                    modal.style.display = 'flex';
                    updateBalanceUI(data.new_balance);
                    
                    // Opcjonalnie odtwórz dźwięk
                    try {
                        const audio = new Audio('win.mp3');
                        audio.play().catch(e => console.log('Audio not available'));
                    } catch(e) {}
                    
                    spinTimeout = setTimeout(() => {
                        spinBtn.disabled = false;
                        isSpinning = false;
                        // Usuń transition po animacji, żeby nie przeszkadzała przy następnym kręceniu
                        setTimeout(() => {
                            track.style.transition = '';
                        }, 100);
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Błąd podczas dodawania wygranej');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Wystąpił błąd: ' + error.message);
                spinBtn.disabled = false;
                isSpinning = false;
                track.style.transition = '';
            });
        }, 3500); // 3.5 sekundy (dopasowane do czasu animacji)
    });
});
</script>

<?php include 'footer.php'; ?>