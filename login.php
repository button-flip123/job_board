<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();
    require_once("includes/db.php");
    require_once("includes/mail.php");

    $error = '';
    if(isset($_SESSION['logged_in'])){
        header("Location: index.php");
        exit();
    }

    if(isset($_POST['login']))
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if(!empty($email) && !empty($password))
        {
            if(filter_var($email, FILTER_VALIDATE_EMAIL))
            {   
                $sql = $conn->prepare("SELECT * FROM `users` WHERE `email` = :email LIMIT 1");
                $sql->bindParam(":email",$email,PDO::PARAM_STR);
                $sql->execute();
                $user = $sql->fetch(PDO::FETCH_ASSOC);
                if(!empty($user)){
                    if(password_verify($password,$user['password']) && $user['email_verified'] == 1){
                        $_SESSION['logged_in'] = true;
                        $_SESSION['user'] = $user;
                        header("Location: index.php");
                        exit();
                    }
                    else{
                        $error = 'password is incorrect';
                    }
                }
                else{
                    $error = 'this user does not exist';
                }          
            }
            else
            {
                $error = 'email is invalid';
            }
        }
        else{
            $error = 'email or password field is empty';
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobify - Login</title>
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
                        <h2 class="card-title text-center mb-4">Login</h2>
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <?php echo htmlspecialchars($error); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        <form id="loginForm" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control" id="password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">Show</button>
                                </div>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                        </form>
                        <div class="text-center mt-3">
                            <a href="forgot_pass.php">Forgot Password?</a> | <a href="register.php">Register</a>
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