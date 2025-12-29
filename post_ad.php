<?php
    require_once("includes/db.php");
    session_start();
    if(!isset($_SESSION['logged_in'])){
        header('Location: login.php');
        exit();
    }

    if(isset($_POST['post_ad'])){
        $title       = $_POST['title'];
        $description = $_POST['description'];
        $type        = $_POST['type'];
        $category    = $_POST['category'];
        $location    = $_POST['location'];

        if (empty($title) || empty($description) || empty($type) || empty($category)) {
            $error = "All required fields must be filled.";
        } 
        elseif (strlen($title) < 5) {
            $error = "Title must be at least 5 characters long.";
        } 
        elseif (strlen($description) < 20) {
            $error = "Description must be at least 20 characters long.";
        }
        else{
            $sql = $conn->prepare("INSERT INTO ads (user_id, title, description, type, category, location, created_at) VALUES (:id, :title, :description, :type, :category, :location, NOW())");
            $sql->bindParam(":id",$_SESSION['user']['id']);
            $sql->bindParam(":title",$title);
            $sql->bindParam(":description",$description);
            $sql->bindParam(":type",$type);
            $sql->bindParam(":category",$category);
            $sql->bindParam(":location",$location);
            $sql->execute();

            header("Location: index.php");
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobify - Post Ad</title>
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
                        <a class="btn btn-outline-light me-2 active" href="post_ad.php">Post Ad</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light me-2" href="index.php">Home</a>
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
                        <h3 class="mb-0">Post a New Ad</h3>
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label for="title" class="form-label">Ad Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" required minlength="5">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ad Type <span class="text-danger">*</span></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="looking" value="looking" <?php echo (($_POST['type'] ?? '') === 'looking') ? 'checked' : ''; ?> required>
                                    <label class="form-check-label" for="looking">I'm looking for a job/service</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="offering" value="offering" <?php echo (($_POST['type'] ?? '') === 'offering') ? 'checked' : ''; ?> required>
                                    <label class="form-check-label" for="offering">I'm offering a job/service</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                <select name="category" id="category" class="form-select" required>
                                    <option value="" disabled <?php echo empty($_POST['category']) ? 'selected' : ''; ?>>Choose category</option>
                                    <option value="it" <?php echo ($_POST['category'] ?? '') === 'it' ? 'selected' : ''; ?>>IT & Programming</option>
                                    <option value="design" <?php echo ($_POST['category'] ?? '') === 'design' ? 'selected' : ''; ?>>Design & Creative</option>
                                    <option value="marketing" <?php echo ($_POST['category'] ?? '') === 'marketing' ? 'selected' : ''; ?>>Marketing & Sales</option>
                                    <option value="writing" <?php echo ($_POST['category'] ?? '') === 'writing' ? 'selected' : ''; ?>>Writing & Translation</option>
                                    <option value="admin" <?php echo ($_POST['category'] ?? '') === 'admin' ? 'selected' : ''; ?>>Admin & Support</option>
                                    <option value="other" <?php echo ($_POST['category'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" name="location" required id="location" class="form-control" value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>" placeholder="e.g. Belgrade, Remote">
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea name="description" id="description" rows="8" class="form-control" required minlength="20"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="index.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" name="post_ad" class="btn btn-primary">Post Ad</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="index.php" class="btn btn-link text-muted">← Back to Home</a>
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