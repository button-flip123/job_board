<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobify - Delete Account</title>
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
                        <span class="nav-link text-white">Hello, <strong><?php echo htmlspecialchars($_SESSION['user']['name'] ?? 'User'); ?></strong>!</span>
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
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow border-danger">
                    <div class="card-header bg-danger text-white text-center">
                        <h3 class="mb-0">Delete Account</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <strong>Warning!</strong> This action is permanent and cannot be undone.
                        </div>
                        <p class="text-muted">
                            Deleting your account will:
                        </p>
                        <ul class="text-muted">
                            <li>Permanently remove your profile and all personal data</li>
                            <li>Delete all your posted ads</li>
                            <li>Remove you from the platform completely</li>
                        </ul>

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Confirm your current password</label>
                                <input type="password" name="current_password" id="current_password" class="form-control" required>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="profile.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" name="delete_account" class="btn btn-danger" 
                                        onclick="return confirm('Are you ABSOLUTELY sure you want to delete your account? This cannot be undone.');">
                                    Delete My Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="profile.php" class="btn btn-link text-muted">← Back to Profile</a>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <p>&copy; 2025 Jobify. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script/script.js"></script>
</body>
</html>