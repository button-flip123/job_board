<?php
    session_start();
    require_once("includes/db.php");
    
    if(!isset($_SESSION['logged_in'])){
        header('Location: login.php');
        exit();
    }

    $current_pic = "imgs/" . $_SESSION['user']['profile_pic'];

    if (isset($_POST['upload_picture'])) 
    {
        if (empty($_FILES['profile_picture']['name'])) 
        {
            $error = "Please select an image to upload.";
        } 
        else 
        {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['profile_picture']['name'];
            $tmp_name = $_FILES['profile_picture']['tmp_name'];
            $size     = $_FILES['profile_picture']['size'];
            $upload_error = $_FILES['profile_picture']['error'];

            if ($upload_error !== UPLOAD_ERR_OK) 
            {
                $error = "Error during upload. Please try again.";
            } 
            elseif ($size > 2 * 1024 * 1024) 
            { 
                $error = "Image is too large (max 2MB).";
            } 
            else 
            {
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed)) 
                {
                    $error = "Invalid format. Only JPG, PNG, and GIF are allowed.";
                } 
                else 
                {
                    $new_filename = uniqid('profile_', true) . '.' . $ext;
                    $upload_path = 'imgs/' . $new_filename;
                    if (move_uploaded_file($tmp_name, $upload_path)) 
                    {
                        if (!empty($user['profile_pic']) && file_exists('imgs/' . $user['profile_pic'])) 
                        {
                            unlink('imgs/' . $user['profile_pic']);
                        }
                        $update = $conn->prepare("UPDATE users SET profile_pic = :profile_pic WHERE id = :user_id");
                        $update->bindParam(":profile_pic",$new_filename);
                        $update->bindParam(":user_id",$user_id);
                        $update->execute();

                        $message = '<div class="alert alert-success">Profile picture successfully updated!</div>';
                        $_SESSION['user']['profile_pic'] = $new_filename;
                        $current_pic = 'imgs/' . $new_filename;
                    } 
                    else 
                    {
                        $error = "Failed to save image. Check folder permissions.";
                    }
                }
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobify - Change Profile Picture</title>
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
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="mb-0">Change Profile Picture</h3>
                    </div>
                    <div class="card-body text-center">
                        <?php echo $message; ?>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <div class="mb-4">
                            <img src="<?php echo $current_pic; ?>" 
                                 alt="Current Profile Picture" 
                                 class="rounded-circle img-fluid mb-3" 
                                 style="width: 200px; height: 200px; object-fit: cover;">
                            <p class="text-muted">Current picture</p>
                        </div>

                        <form method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="profile_picture" class="form-label">Choose new image</label>
                                <input type="file" name="profile_picture" id="profile_picture" class="form-control" accept="image/jpeg,image/png,image/gif" required>
                                <small class="text-muted d-block mt-2">Max 2MB • JPG, PNG, GIF</small>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="profile.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" name="upload_picture" class="btn btn-primary">Upload Picture</button>
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