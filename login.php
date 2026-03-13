<?php 
session_start();
include 'connect.php';

if (isset($_SESSION["logged_in"]) && $_SESSION['logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    
    if(empty($username) || empty($password)) {
        $error = "Wszystkie pola są wymagane";
    } else {
        $sql = "SELECT * FROM `users` WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);    
        
        if(mysqli_num_rows($result) > 0){
            $user = mysqli_fetch_assoc($result);
            if(password_verify($password, $user['password'])){
                $_SESSION["logged_in"] = true;
                $_SESSION["username"] = $user['username'];
                $_SESSION["user_id"] = $user['id'];
                $_SESSION["email"] = $user['email'];
                $_SESSION["balance"] = $user['balance'];
                $_SESSION["admin"] = $user['is_admin'];
                
                header("Location: index.php");
                exit;
            } else {
                $error = "Błędne hasło";
            }
        } else {
            $error = "Użytkownik o podanej nazwie nie istnieje";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie - Casino 67</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="auth-container">
        <div class="auth-card">
            <div style="text-align: center; margin-bottom: 2rem;">
                <h1 style="color: var(--secondary-blue); margin-bottom: 0.5rem;"><i class="fas fa-sign-in-alt"></i> Logowanie</h1>
                <p style="color: var(--text-gray);">Witaj ponownie w Casino 67!</p>
            </div>
            
            <?php if(!empty($error)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form action="" method="POST">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Nazwa użytkownika</label>
                    <input type="text" name="username" id="username" required placeholder="Wpisz swoją nazwę">
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Hasło</label>
                    <input type="password" name="password" id="password" required placeholder="Wpisz swoje hasło">
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.9rem;">
                    <i class="fas fa-sign-in-alt"></i> Zaloguj się
                </button>
            </form>
            
            <div style="text-align: center; margin-top: 1.5rem; color: var(--text-gray);">
                <p>Nie masz konta? <a href="register.php" style="color: var(--secondary-blue); text-decoration: none;">Zarejestruj się</a></p>
                <p style="margin-top: 0.5rem;"><a href="index.php" style="color: var(--text-gray); text-decoration: none;"><i class="fas fa-arrow-left"></i> Powrót do strony głównej</a></p>
            </div>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>
