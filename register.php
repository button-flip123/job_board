<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once("includes/db.php");
    require_once("includes/mail.php");

    $send_mail = new Mail();

    if(isset($_POST['register']))
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $company = $_POST['company'];
        $location = $_POST['location'];

        if(!empty($name) && !empty($email) && !empty($password) && !empty($company) && !empty($location))
        {
            if(filter_var($email,FILTER_VALIDATE_EMAIL))
            {
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = $conn->prepare("INSERT INTO users (name,email,password,company,location,email_verification_token,email_verification_expires) VALUES (:name,:email,:password,:company,:location,:email_verification_token,:email_verification_expires)");

                $sql->bindParam(":name",$name);
                $sql->bindParam(":email",$email);
                $sql->bindParam(":password",$hashed_password);
                $sql->bindParam(":company",$company);
                $sql->bindParam(":location",$location);
                $sql->bindParam(":email_verification_token",$token);
                $sql->bindParam(":email_verification_expires",$expires);
                $sql->execute();

                $userMessage = "Please verifiy your email to activate your account, this token is active for 1 hour. http://localhost/job_board/verify_account.php?token=$token";
                $send_mail->SendMail($email,'jobify@gmail.com','Verification',$userMessage);
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Board - Register</title>
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
                        <h2 class="card-title text-center mb-4">Register</h2>
                        <form id="registerForm" method="post">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">Show</button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="company" class="form-label">Company (Optional)</label>
                                <input type="text" class="form-control" id="company" name="company">
                            </div>
                            <div class="mb-3">
                                <label for="location" class="form-label">Location (Optional)</label>
                                <input type="text" class="form-control" id="location" name="location">
                            </div>
                            <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
                        </form>
                        <div class="text-center mt-3">
                            <a href="login.php">Already have an account? Login</a>
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