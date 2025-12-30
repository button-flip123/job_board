<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once("includes/db.php");
    session_start();
    if(!isset($_SESSION['logged_in'])){
        header("Location: login.php");
        exit();
    }

    $user_name = $_SESSION['user']['name'];
    $user_id = $_SESSION['user']['id'];
    $ad_id = (int)($_GET['id'] ?? 0);
    $message = '';
    $error = '';

    if($ad_id <= 0) {
        header("Location: index.php");
        exit();
    }

    $sql = $conn->prepare("
        SELECT a.*, u.name AS author_name 
        FROM ads a 
        JOIN users u ON a.user_id = u.id 
        WHERE a.id = :ad_id
    ");
    $sql->bindParam(":ad_id",$ad_id);
    $sql->execute();
    $ad = $sql->fetch(PDO::FETCH_ASSOC);

    $comments_stmt = $conn->prepare("
        SELECT c.*, u.name AS commenter_name 
        FROM comments c 
        JOIN users u ON c.user_id = u.id 
        WHERE c.ad_id = :ad_id 
        ORDER BY c.created_at DESC
    ");
    $comments_stmt->bindParam(":ad_id",$ad_id);
    $comments_stmt->execute();
    $comments = $comments_stmt->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_POST['add_comment'])) {
        $comment_text = trim($_POST['comment_text']);

        if (empty($comment_text)) 
        {
            $error = "Comment cannot be empty.";
        } 
        elseif (strlen($comment_text) > 1000) 
        {
            $error = "Comment is too long (max 1000 characters).";
        } 
        else
        {
            $insert = $conn->prepare("INSERT INTO comments (ad_id, user_id, comment_text) VALUES (:ad_id,:user_id,:comment_text)");
            $insert->bindParam(":comment_text",$comment_text);
            $insert->bindParam(":ad_id",$ad_id);
            $insert->bindParam(":user_id",$user_id);
            $insert->execute();
            $message = '<div class="alert alert-success">Comment added successfully!</div>';
            $comments_stmt->execute();
            $comments = $comments_stmt->fetchAll(PDO::FETCH_ASSOC);
            
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobify - Ad Details</title>
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
                        <span class="nav-link text-white">Hello, <strong><?php echo htmlspecialchars($user_name); ?></strong>!</span>
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
        <?php if ($error): ?>
            <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow mb-5">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0"><?php echo htmlspecialchars($ad['title']); ?></h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-2">
                            <strong>Author:</strong> <?php echo htmlspecialchars($ad['author_name']); ?>
                            <?php if ($ad['location']): ?>
                                • <strong>Location:</strong> <?php echo htmlspecialchars($ad['location']); ?>
                            <?php endif; ?>
                            • <strong>Category:</strong> <?php echo htmlspecialchars($ad['category']); ?>
                        </p>
                        <p class="text-muted mb-4">
                            <strong>Type:</strong> <?php echo $ad['type'] === 'looking' ? 'Looking for' : 'Offering'; ?>
                            • Posted: <?php echo date('M d, Y', strtotime($ad['created_at'])); ?>
                        </p>
                        <div class="mb-4">
                            <h5>Description</h5>
                            <p><?php echo nl2br(htmlspecialchars($ad['description'])); ?></p>
                        </div>
                    </div>
                </div>

                <h4 class="mb-4">Comments (<?php echo count($comments); ?>)</h4>

                <?php echo $message; ?>

                <?php if (empty($comments)): ?>
                    <div class="alert alert-info">No comments yet. Be the first to comment!</div>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <p class="text-muted mb-1">
                                    <strong><?php echo htmlspecialchars($comment['commenter_name']); ?></strong> 
                                    • <?php echo date('M d, Y H:i', strtotime($comment['created_at'])); ?>
                                </p>
                                <p><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Forma za dodavanje komentara -->
                <div class="mt-5">
                    <h5>Add a Comment</h5>
                    <form method="post">
                        <div class="mb-3">
                            <textarea name="comment_text" class="form-control" rows="4" placeholder="Write your comment here..." required maxlength="1000"></textarea>
                        </div>
                        <button type="submit" name="add_comment" class="btn btn-primary">Post Comment</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-link text-muted">← Back to Home</a>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <p>&copy; 2025 Jobify. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script/script.js"></script>
</body>
</html>