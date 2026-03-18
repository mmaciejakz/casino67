<?php 
session_start();
include 'connect.php';
?>
        
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casi 67 - Strona główna</title>
    <link rel="stylesheet" href="index-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
</head>
<body>
    <?php 
    include 'header.php'; 
    ?>
    
    <section class="hero">
        <h1>Witaj w kasynie</h1>
        <p>jakis taki fajny opis sie tutaj da i bedzie git</p>
        <div class = "auth-buttons2">
        <a href="movies.php" class="btn btn-bonus">Odbierz bonus</a>
        <a href="movies.php" class="btn btn-graj">Zagraj </a>
        </div>
    </section>

    
    
    <?php include 'footer.php'; ?>

</body>
</html>
