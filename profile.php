<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();
    require_once("includes/db.php");
    if(!isset($_SESSION['logged_in'])){
        header("Location: login.php");
        exit();
    }

    if(isset($_POST['edit_profile'])){
        $sql = $conn->prepare("SELECT * FROM `users` WHERE `id` = :id");
        $sql->bindParam(":id",$_SESSION['user']['id']);
        $sql->execute();
        $current = $sql->fetch(PDO::FETCH_ASSOC);

        $_SESSION['user'] = $current;

        $company  = !empty($_POST['company']) ? trim($_POST['company']) : $current['company'];
        $location = !empty($_POST['location']) ? trim($_POST['location']) : $current['location'];
        $bio      = !empty($_POST['bio']) ? trim($_POST['bio']) : ($current['bio'] ?? null);

        $update = $conn->prepare("UPDATE users SET company = :company, location = :location, short_bio = :bio 
        WHERE id = :user_id");
        $update->bindParam(":user_id",$_SESSION['user']['id']);
        $update->bindParam(":company",$company,PDO::PARAM_STR);
        $update->bindParam(":location",$location,PDO::PARAM_STR);
        $update->bindParam(":bio",$bio,PDO::PARAM_STR);
        $update->execute();

        

        $_SESSION['user']['company'] = $company;
        $_SESSION['user']['location'] = $location;
        $_SESSION['user']['short_bio'] = $bio;
        
        
    }

    $profile_pic = "imgs/" . $_SESSION['user']['profile_pic'];
    //echo $profile_pic;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobify - My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Jobify</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <span class="nav-link text-white">Hello, <strong></strong><?php echo htmlspecialchars($_SESSION['user']['name']); ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light me-2" href="post_ad.php">Post Ad</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light me-2 active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-danger" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">My Profile</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center mb-4 mb-md-0">
                                <img src="<?php echo $profile_pic; ?>" alt="Profile Picture" class="rounded-circle img-fluid mb-3" style="width: 200px; height: 200px; object-fit: cover;">
                                <br>
                                <a class="btn btn-outline-primary btn-sm" href="change_pic.php">Change Photo</a>
                            </div>
                            <div class="col-md-8">
                                <form method="post" action="">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" name="name" id="name" value=<?php echo htmlspecialchars($_SESSION['user']['name']); ?> readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" id="email" value=<?php echo htmlspecialchars($_SESSION['user']['email']); ?> readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="company" class="form-label">Company</label>
                                        <input type="text" class="form-control" id="company" name="company" value="<?php echo htmlspecialchars($_SESSION['user']['company'] ?? ''); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="location" class="form-label">Location</label>
                                        <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($_SESSION['user']['location'] ?? ''); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="bio" class="form-label">Short Bio</label>
                                        <textarea class="form-control" id="bio" name="bio" rows="4" ><?php echo htmlspecialchars($_SESSION['user']['short_bio'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="submit" name="edit_profile" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <hr class="my-5">
                        
                        <h4 class="mb-4">Account Settings</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <a href="forgot_pass.php" class="btn btn-outline-warning w-100">Change Password</a>
                            </div>
                            <div class="col-md-6">
                                <a href="delete_account.php" class="btn btn-outline-danger w-100">Delete Account</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <a href="index.php" class="btn btn-link text-muted">← Back to Home</a>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <p>&copy; 2025 Job Board. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script/script.js"></script>
</body>
</html>