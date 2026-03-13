<?php 
session_start();
include 'connect.php';
include 'header.php'; 
?>

<section class="hero">
    <div class="container">
        <h1>Witaj w Casino 67</h1>
        <p>Najbardziej ekscytujące gry kasynowe w jednym miejscu. Dołącz do tysięcy graczy i wygrywaj sztywne coiny każdego dnia!</p>
        <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem;">
            <a href="games.php" class="btn btn-primary btn-large" style="padding: 1rem 2rem; font-size: 1.1rem;">
                <i class="fas fa-play"></i> Graj teraz
            </a>
            <a href="daily_case.php" class="btn btn-gold btn-large" style="padding: 1rem 2rem; font-size: 1.1rem;">
                <i class="fas fa-gift"></i> Odbierz bonus
            </a>
        </div>
    </div>
</section>

<div class="container" style="padding: 4rem 0;">
    <h2 style="text-align: center; margin-bottom: 3rem; font-size: 2.5rem; color: var(--secondary-blue);">
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
                <h2 style="color: var(--gold); font-size: 2.5rem; margin-bottom: 1.5rem;">Czym jest Sztywny Coin?</h2>
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
                <img src="https://images.unsplash.com/photo-1610366398516-46da90014d60?auto=format&fit=crop&w=600&q=80" 
                     alt="Coins" style="width: 100%; max-width: 500px; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.5);">
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
