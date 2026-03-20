<?php 
session_start();
include 'connect.php';
include 'header.php'; 
?>
<link rel="stylesheet" href="style.css">
<section class="hero">
    <div class="container">
        <h1>Witaj w Casino 67</h1>
        <p>Najbardziej ekscytujące gry kasynowe w jednym miejscu. Dołącz do tysięcy graczy i wygrywaj sztywne coiny każdego dnia!</p>
        <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem;">
            <a href="games.php" class="btn2 btn-graj2">
                <i class="fas fa-play"></i> Graj teraz
            </a>
            <a href="daily_case.php" class="btn2 btn-bonus2">
                <i class="fas fa-gift"></i> Odbierz bonus
            </a>
        </div>
    </div>
</section>

<div class="container" style="padding: 4rem 0;">
    <h2 style="text-align: center; margin-bottom: 3rem; font-size: 2.5rem; color: #257D05;">
        Nasze Najlepsze Gry
    </h2>
    
    <div class="games-grid">
        <?php
        $sql = "SELECT * FROM games LIMIT 3";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="game-card">
                    <div class="game-image-container">
                        <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" class="game-image">
                    </div>
                    <div class="game-info">
                        <h3 class="game-title"><?php echo $row['name']; ?></h3>
                        <p class="game-description"><?php echo $row['description']; ?></p>
                        <a href="game.php?id=<?php echo $row['id']; ?>" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-play"></i> Graj
                        </a>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
    
    <div style="text-align: center; margin-top: 2rem;">
        <a href="games.php" class="btn btn-secondary">
            Zobacz wszystkie gry <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</div>

<section style="background: var(--secondary-dark); padding: 5rem 0; border-top: 1px solid #333; border-bottom: 1px solid #333;">
    <div class="container">
        <div style="display: flex; align-items: center; gap: 4rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 300px;">
                <h2 style="color: #FC3D19; font-size: 2.5rem; margin-bottom: 1.5rem;">Czym jest Sztywny Coin?</h2>
                <p style="color: var(--text-gray); font-size: 1.1rem; margin-bottom: 1.5rem;">
                    Sztywny Coin (SZC) to nasza unikalna wirtualna waluta. Na start otrzymujesz 1000 SZC zupełnie za darmo! Możesz ich używać we wszystkich naszych grach, a jeśli Ci braknie, zawsze możesz doładować swój portfel lub odebrać codzienną skrzynię z bonusami.
                </p>
                <div style="display: flex; gap: 2rem;">
                    <div style="text-align: center;">
                        <div style="font-size: 2.5rem; color: var(--secondary-blue); font-weight: bold;">100%</div>
                        <div style="color: var(--text-gray);">Bezpieczeństwa</div>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 2.5rem; color: var(--secondary-blue); font-weight: bold;">24/7</div>
                        <div style="color: var(--text-gray);">Dostępności</div>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 2.5rem; color: var(--secondary-blue); font-weight: bold;">0 zł</div>
                        <div style="color: var(--text-gray);">Opłat</div>
                    </div>
                </div>
            </div>
            <div style="flex: 1; min-width: 300px; text-align: center;">
                <img src="sztywny.png" alt="Coins" style="width: 100%; max-width: 500px; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.5);">

                     
            </div>
        </div>
    </div>
</section>



<!-- PREMIUM ANIMACJA 67 - SZYBKA PRZY KAŻDYM WEJŚCIU NA INDEX -->
<div id="splashScreen67" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: #03050a; z-index: 99999; display: flex; justify-content: center; align-items: center; flex-direction: column; pointer-events: none; opacity: 1; transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1);">

    <!-- Tło z efektem głębi - tylko niebieskie akcenty -->
    <div style="position: absolute; width: 100%; height: 100%; overflow: hidden;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 200%; height: 200%; background: radial-gradient(circle at 30% 50%, rgba(246, 59, 59, 0.08) 0%, transparent 50%), radial-gradient(circle at 70% 50%, rgba(0, 0, 0, 0.08) 0%, transparent 50%);"></div>
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, #FC3D19, #AD2710, transparent); animation: scanTop 2s ease-in-out infinite;"></div>
        <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, #AD2710, #FC3D19, transparent); animation: scanBottom 2s ease-in-out infinite;"></div>
    </div>

    <!-- Kontener główny - MNIEJSZY I SZYBSZY -->
    <div style="position: relative; z-index: 2; text-align: center;">
        
        <!-- Główny napis 67 - OBA NIEBIESKIE, MNIEJSZE -->
        <div style="display: flex; gap: 20px; transform: scale(1.2); perspective: 1000px;">
            <!-- 6 - niebieski -->
            <div id="sixContainer" style="position: relative; transform-style: preserve-3d;">
                <div style="font-size: 100px; font-weight: 900; background: linear-gradient(135deg, #FC3D19, #AD2710); -webkit-background-clip: text; background-clip: text; color: transparent; filter: drop-shadow(0 0 20px rgba(173, 39, 16,0.5)); transform: rotateY(5deg) rotateX(2deg); animation: floatSixFast 0.8s cubic-bezier(0.4, 0, 0.2, 1) infinite;">
                    6
                </div>
                <div style="position: absolute; top: 0; left: 0; font-size: 100px; font-weight: 900; color: #FC3D19; filter: blur(10px); opacity: 0.3; transform: translateZ(-10px);">6</div>
            </div>
            
            <!-- 7 - też niebieski (jaśniejszy odcień) -->
            <div id="sevenContainer" style="position: relative; transform-style: preserve-3d;">
                <div style="font-size: 100px; font-weight: 900; background: linear-gradient(135deg, #257D05, #195802); -webkit-background-clip: text; background-clip: text; color: transparent; filter: drop-shadow(0 0 20px rgba(173, 39, 16,0.5)); transform: rotateY(-5deg) rotateX(2deg); animation: floatSevenFast 0.8s cubic-bezier(0.4, 0, 0.2, 1) infinite 0.1s;">
                    7
                </div>
                <div style="position: absolute; top: 0; left: 0; font-size: 100px; font-weight: 900; color: #257D05; filter: blur(10px); opacity: 0.3; transform: translateZ(-10px);">7</div>
            </div>
        </div>
    </div>

    <!-- Tekst KINO 67 - pojawi się na końcu -->
    <div id="finalText" style="position: absolute; bottom: 40%; left: 50%; transform: translateX(-50%); font-size: 20px; letter-spacing: 10px; opacity: 0; filter: blur(5px); font-weight: 200; white-space: nowrap;">
        <span style="background: linear-gradient(135deg, #AD2710, #FC3D19); -webkit-background-clip: text; background-clip: text; color: transparent;">CASINO <span style = "color: #257D05;">67</span></span>
    </div>
</div>
<style>
@keyframes floatSixFast {
    0%, 100% {
        transform: rotateY(5deg) rotateX(2deg) translateY(0) scale(1);
        filter: drop-shadow(0 0 20px rgba(173, 39, 16,0.5));
    }
    25% {
        transform: rotateY(8deg) rotateX(4deg) translateY(-15px) scale(1.05);
        filter: drop-shadow(0 0 30px rgba(173, 39, 16,0.8));
    }
    50% {
        transform: rotateY(2deg) rotateX(0deg) translateY(15px) scale(0.98);
        filter: drop-shadow(0 0 10px rgba(173, 39, 16,0.3));
    }
    75% {
        transform: rotateY(0deg) rotateX(5deg) translateY(-8px) scale(1.02);
        filter: drop-shadow(0 0 25px rgba(173, 39, 16,0.6));
    }
}

@keyframes floatSevenFast {
    0%, 100% {
        transform: rotateY(-5deg) rotateX(2deg) translateY(0) scale(1);
        filter: drop-shadow(0 0 20px hsla(137, 95%, 31%, 0.5));
    }
    25% {
        transform: rotateY(-8deg) rotateX(4deg) translateY(15px) scale(0.98);
        filter: drop-shadow(0 0 10px hsla(137, 95%, 31%,0.3));
    }
    50% {
        transform: rotateY(-2deg) rotateX(0deg) translateY(-15px) scale(1.05);
        filter: drop-shadow(0 0 30px hsla(137, 95%, 31%,0.8));
    }
    75% {
        transform: rotateY(0deg) rotateX(5deg) translateY(8px) scale(1.02);
        filter: drop-shadow(0 0 25px hsla(137, 95%, 31%,0.6));
    }
}

@keyframes scanTop {
    0%, 100% { transform: translateX(-100%); }
    50% { transform: translateX(100%); }
}

@keyframes scanBottom {
    0%, 100% { transform: translateX(100%); }
    50% { transform: translateX(-100%); }
}

@keyframes fadeInTextFast {
    0% { opacity: 0; filter: blur(5px); transform: translateX(-50%) scale(0.9); bottom: 40%; }
    100% { opacity: 1; filter: blur(0); transform: translateX(-50%) scale(1); bottom: 45%; }
}
</style>
<script>
// ANIMACJA - SZYBKA PRZY KAŻDYM WEJŚCIU NA INDEX
window.addEventListener('load', function() {
    
    const splash = document.getElementById('splashScreen67');
    const six = document.getElementById('sixContainer');
    const seven = document.getElementById('sevenContainer');
    const finalText = document.getElementById('finalText');
    
    // Po 0.3 sekundy zaczynamy rozdzielanie
    setTimeout(() => {
        // Zatrzymaj floating animation
        if(six && six.querySelector('div')) six.querySelector('div').style.animation = 'none';
        if(seven && seven.querySelector('div')) seven.querySelector('div').style.animation = 'none';
        
        // SZYBKIE rozdzielenie w przeciwne strony
        six.style.transition = 'all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
        seven.style.transition = 'all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
        
        six.style.transform = 'translateX(-100px) scale(1.3)';
        seven.style.transform = 'translateX(100px) scale(1.3)';
        
        six.style.opacity = '0';
        seven.style.opacity = '0';
        
        // Pokaż finalny tekst
        finalText.style.transition = 'all 0.4s ease';
        finalText.style.opacity = '1';
        finalText.style.filter = 'blur(0)';
        finalText.style.bottom = '45%';
        
        // Usuń splash po rozdzieleniu
        setTimeout(() => {
            splash.style.opacity = '0';
            setTimeout(() => {
                if(splash) splash.remove();
            }, 400);
        }, 400);
        
    }, 300); // SZYBKO - tylko 0.3 sekundy animacji
});
</script>

<?php include 'footer.php'; ?>
