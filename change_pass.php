<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("includes/db.php");
session_start();

$token = $_GET['token'];

if(!isset($_SESSION['logged_in'])){
    header("Location: login.php");
    exit();
}

$error = '';

if(isset($_POST['reset'])){
    $newpass = $_POST['password'];
    if(!empty($newpass)){
        if(strlen($newpass)){$error = 'password is to short, minimum is 6 characters';}

        $hashed = password_hash($newpass, PASSWORD_DEFAULT);
        $sql = $conn->prepare('UPDATE `users` SET `password` = :pass WHERE `id` = :id AND `reset_password_expires` > NOW() AND `reset_password_token` = :token');
        $sql->bindParam(':pass', $hashed);
        $sql->bindParam(':token',$token);
        $sql->bindParam(':id',$_SESSION['user']['id']);
        $sql->execute();
        
        $sql = $conn->prepare('UPDATE `users` SET `reset_password_expires` = NULL,  `reset_password_token` = NULL WHERE `id` = :id');
        $sql->bindParam(':id',$_SESSION['user']['id']);
        $sql->execute();

        header("Location: login.php");
        exit();
    
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobify - Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="bg-primary text-white text-center py-3">
        <h1>Jobify</h1>
        <p>Find or offer jobs and services</p>
    </header>
    
    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Change password</h2>
                        <form id="forgotForm" method="post">
                            <div class="mb-3">
                                <label for="password" class="form-label">Enter your new password</label>
                                <input type="password" name="password" class="form-control" id="password" required>
                            </div>
                            <button type="submit" name="reset" class="btn btn-primary w-100">Reset Password</button>
                        </form>
                        <div class="text-center mt-3">
                            <a href="login.php">Back to Login</a> | <a href="register.php">Register</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 Job Board. All rights reserved.</p>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script/script.js"></script>
</body>
</html>