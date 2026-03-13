<?php 
session_start();
include 'connect.php';

if (isset($_SESSION["logged_in"]) && $_SESSION['logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$error = "";
$success = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST["confirmPassword"]);  
    
    if(empty($username) || empty($email) || empty($password)) {
        $error = "Wszystkie pola są wymagane";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Podaj poprawny adres email";
    } elseif(strlen($password) < 6) {
        $error = "Hasło musi mieć co najmniej 6 znaków";
    } elseif($password !== $confirmPassword){
        $error = "Hasła różnią się";  
    } else {
        $sql = "SELECT * FROM `users` WHERE username = '$username' OR email = '$email'";
        $result = mysqli_query($conn, $sql);    
        
        if(mysqli_num_rows($result) > 0){
            $error = "Nazwa użytkownika lub email jest już zajęty";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO `users` (`username`, `password`, `email`, `balance`) 
                    VALUES ('$username','$hashedPassword','$email', 1000.00)";
            
            if(mysqli_query($conn, $sql)){
                $success = "Konto zostało utworzone pomyślnie! Otrzymałeś 1000 SZC na start! Za chwilę zostaniesz przekierowany...";
                
                $new_user_id = mysqli_insert_id($conn);
                
                $_SESSION["logged_in"] = true;
                $_SESSION["username"] = $username;
                $_SESSION["user_id"] = $new_user_id;
                $_SESSION["email"] = $email;
                $_SESSION["balance"] = 1000.00;
                $_SESSION["admin"] = 0;
                
                echo '<script>
                    setTimeout(function() {
                        window.location.href = "index.php";
                    }, 2000);
                </script>';
            } else {
                $error = "Nie udało się zarejestrować użytkownika: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja - Casino 67</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="auth-container">
        <div class="auth-card">
            <div style="text-align: center; margin-bottom: 2rem;">
                <h1 style="color: var(--secondary-blue); margin-bottom: 0.5rem;"><i class="fas fa-user-plus"></i> Rejestracja</h1>
                <p style="color: var(--text-gray);">Dołącz do Casino 67 i zgarnij 1000 SZC na start!</p>
            </div>
            
            <?php if(!empty($error)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if(!empty($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <?php if(empty($success)): ?>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" id="email" required placeholder="twoj@email.pl">
                </div>
                
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Nazwa użytkownika</label>
                    <input type="text" name="username" id="username" required placeholder="Wybierz nazwę użytkownika">
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Hasło</label>
                    <input type="password" name="password" id="password" required placeholder="Minimum 6 znaków">
                </div>
                
                <div class="form-group">
                    <label for="confirmPassword"><i class="fas fa-lock"></i> Potwierdź hasło</label>
                    <input type="password" name="confirmPassword" id="confirmPassword" required placeholder="Wpisz ponownie hasło">
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.9rem;">
                    <i class="fas fa-user-plus"></i> Zarejestruj się
                </button>
            </form>
            
            <div style="text-align: center; margin-top: 1.5rem; color: var(--text-gray);">
                <p>Masz już konto? <a href="login.php" style="color: var(--secondary-blue); text-decoration: none;">Zaloguj się</a></p>
                <p style="margin-top: 0.5rem;"><a href="index.php" style="color: var(--text-gray); text-decoration: none;"><i class="fas fa-arrow-left"></i> Powrót do strony głównej</a></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>
