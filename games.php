<?php 
session_start();
include 'connect.php';
include 'header.php'; 
?>

<div class="container" style="padding: 4rem 0;">
    <h1 style="text-align: center; margin-bottom: 3rem; font-size: 2.5rem; color: var(--secondary-blue);">
        Wszystkie Gry
    </h1>
    
    <div class="games-grid">
        <?php
        $sql = "SELECT * FROM games";
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
                            <i class="fas fa-play"></i> Graj teraz
                        </a>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>
