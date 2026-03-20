<?php 
session_start();
include 'connect.php';

if (!isset($_SESSION["logged_in"]) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$can_claim = false;
$wait_time = "";

// Check if user can claim daily bonus
$check_sql = "SELECT last_daily_claim FROM users WHERE id = $user_id";
$res = mysqli_query($conn, $check_sql);
$user = mysqli_fetch_assoc($res);

if ($user['last_daily_claim'] === null) {
    $can_claim = true;
} else {
    $last_claim = strtotime($user['last_daily_claim']);
    $now = time();
    $diff = $now - $last_claim;
    if ($diff >= 86400) { // 24 hours
        $can_claim = true;
    } else {
        $remaining = 86400 - $diff;
        $hours = floor($remaining / 3600);
        $minutes = floor(($remaining % 3600) / 60);
        $wait_time = "$hours godz. $minutes min.";
    }
}

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
</style>

<div class="container" style="padding: 4rem 0;">
    <h1 style="text-align: center; margin-bottom: 3rem; font-size: 2.5rem; color: #FC3D19;">
        Codzienna Skrzynia Bonusowa
    </h1>
    
    <div style="max-width: 800px; margin: 0 auto; background: var(--secondary-dark); border-radius: 20px; padding: 3rem; border: 2px solid var(--gold); box-shadow: 0 0 30px rgba(251, 191, 36, 0.2); text-align: center;">
        
        <?php if($can_claim): ?>
            <!-- Pokazuj pola tylko gdy można otworzyć -->
            <div id="case-container" style="position: relative; width: 100%; height: 250px; overflow: hidden; background: #0a0a0a; border-radius: 12px; margin-bottom: 2rem; border: 1px solid #333; display: flex; align-items: center; justify-content: center;">
                <!-- Indicator line -->
                <div style="position: absolute; top: 0; bottom: 0; left: 50%; width: 4px; background: var(--gold); z-index: 10; box-shadow: 0 0 15px var(--gold);"></div>
                
                <!-- Items track -->
                <div id="case-track" style="display: flex; gap: 10px; position: absolute; left: 0; transition: transform 6s cubic-bezier(0.15, 0, 0.15, 1);">
                    <!-- Items will be generated by JS -->
                </div>
            </div>
            
            <button id="open-case-btn" class="btn btn-gold" style="padding: 1.2rem 3rem; font-size: 1.3rem;">
                <i class="fas fa-box-open"></i> Otwórz Skrzynię Za Darmo!
            </button>
            
        <?php else: ?>
            <!-- Pokazuj duży timer gdy skrzynka zamknięta -->
            <div style="text-align: center; padding: 2rem;">
                <h2 style="color: var(--gold); font-size: 2.2rem; margin-bottom: 1rem;">Skrzynia została już otwarta!</h2>
                <button class="btn btn-secondary" disabled style="padding: 1.2rem 3rem; font-size: 1.3rem; margin-top: 1rem; color: #FC3D19; border-color: #FC3D19;">
                    <i class="fas fa-clock"></i> Następna za: <?php echo $wait_time; ?>
                </button>
                <div style="margin-top: 2rem; color: var(--text-gray); font-size: 1.1rem;">
                    <i class="fas fa-gift" style="color: #FC3D19; margin-right: 0.5rem;"></i> 
                    Wróć za podany czas po kolejną skrzynkę!
                </div>
            </div>
        <?php endif; ?>
        
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
            <img src="sztywny.png">
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
    const openBtn = document.getElementById('open-case-btn');
    const modal = document.getElementById('win-modal');
    const closeModal = document.getElementById('close-modal');
    const modalCloseBtn = document.getElementById('modal-close-btn');
    const modalAmount = document.getElementById('modal-amount');

    if (!openBtn) return;
    
    const possiblePrizes = [
        { val: 50, color: '#94a3b8', weight: 60 },
        { val: 100, color: '#94a3b8', weight: 20 },
        { val: 200, color: '#3b82f6', weight: 10 },
        { val: 500, color: '#8b5cf6', weight: 7 },
        { val: 1000, color: '#8b5cf6', weight: 2 },
        { val: 2500, color: '#fbbf24', weight: 1 }
    ];
    
    // Create items for the track
    let items = [];
    for (let i = 0; i < 50; i++) {
        let totalWeight = possiblePrizes.reduce((a, b) => a + b.weight, 0);
        let random = Math.random() * totalWeight;
        let selected;
        for (let p of possiblePrizes) {
            if (random < p.weight) {
                selected = p;
                break;
            }
            random -= p.weight;
        }
        items.push(selected);
    }
    
    // Render items
    track.innerHTML = '';
    items.forEach((item, index) => {
        const div = document.createElement('div');
        div.style.minWidth = '150px';
        div.style.height = '180px';
        div.style.background = '#1a1a1a';
        div.style.borderRadius = '8px';
        div.style.display = 'flex';
        div.style.flexDirection = 'column';
        div.style.alignItems = 'center';
        div.style.justifyContent = 'center';
        div.style.border = '1px solid #333';
        div.style.borderBottom = `5px solid ${item.color}`;
        div.innerHTML = `
            <img src="sztywny.png" style="width: 70px; height: 70px; margin-bottom: 0.8rem; object-fit: contain;">
            <span style="font-weight: bold; font-size: 1.1rem;">${item.val} SZC</span>
        `;
        track.appendChild(div);
    });
    
    function closeModalFunction() {
        modal.style.display = 'none';
        window.location.reload();
    }
    
    closeModal.addEventListener('click', closeModalFunction);
    if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeModalFunction);

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModalFunction();
        }
    });
    
    openBtn.addEventListener('click', function() {
        openBtn.disabled = true;
        
        const finalPrize = items[45];
        
        const itemWidth = 160;
        const offset = (45 * itemWidth) - 400 + 80;
        
        track.style.transform = `translateX(-${offset}px)`;
        
        setTimeout(() => {
            fetch('calm_daily.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `amount=${finalPrize.val}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modalAmount.innerText = finalPrize.val;
                    modal.style.display = 'flex';
                    
                    try {
                        const audio = new Audio('win.mp3');
                        audio.play();
                    } catch(e) {
                        console.log('Audio not available');
                    }
                    
                    const balanceDisplay = document.querySelector('.balance-display');
                    if (balanceDisplay) {
                        balanceDisplay.innerHTML = `<img src="sztywny.png" alt="sztywny"> ${data.new_balance} SZC`;
                    }
                    
                    setTimeout(() => {
                        openBtn.disabled = false;
                    }, 3000);
                }
            });
        }, 6500);
    });
});
</script>

<?php include 'footer.php'; ?>