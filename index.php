<?php
    require_once("includes/db.php");
    session_start();

    if(!isset($_SESSION['logged_in'])){
        header("Location: login.php");
        exit();
    } 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobify</title>
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
                        <span class="nav-link text-white">Hello, <strong><?php echo htmlspecialchars($_SESSION['user']['name']); ?></strong>!</span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light me-2" href="post_ad.php">Post Ad</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light me-2" href="profile.php">My Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-danger" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold">Welcome to Job Board!</h1>
            <p class="lead text-muted">Find a job or offer your services – all in one place.</p>
        </div>

        <div class="row mb-4">
            <div class="col-md-8 mx-auto">
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search ads (e.g. developer, Belgrade...)" aria-label="Search">
                    <button class="btn btn-primary" type="submit">Search</button>
                </form>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Looking for Web Developer</h5>
                        <p class="card-text text-muted">XYZ Company, Belgrade</p>
                        <p class="card-text">Need a full-stack developer with PHP and Laravel experience. Remote work possible.</p>
                        <small class="text-muted">Posted: Dec 28, 2025</small>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="ad_details.php?id=1" class="btn btn-outline-primary btn-sm">View Ad</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-success">Offering Cleaning Services</h5>
                        <p class="card-text text-muted">Ana P., Novi Sad</p>
                        <p class="card-text">Professional apartment and office cleaning at affordable prices.</p>
                        <small class="text-muted">Posted: Dec 27, 2025</small>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="ad_details.php?id=2" class="btn btn-outline-success btn-sm">View Ad</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Looking for Graphic Designer</h5>
                        <p class="card-text text-muted">Tech Startup, Remote</p>
                        <p class="card-text">Need a freelance designer for logo and branding.</p>
                        <small class="text-muted">Posted: Dec 26, 2025</small>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="ad_details.php?id=3" class="btn btn-outline-primary btn-sm">View Ad</a>
                    </div>
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