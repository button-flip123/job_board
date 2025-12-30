<?php
    require_once("includes/db.php");
    session_start();

    if(!isset($_SESSION['logged_in'])){
        header("Location: login.php");
        exit();
    } 

    $sql = $conn->prepare("
        SELECT a.*, u.name AS author_name 
        FROM ads a 
        JOIN users u ON a.user_id = u.id 
        ORDER BY a.created_at DESC
    ");
    $sql->execute();
    $ads = $sql->fetchAll(PDO::FETCH_ASSOC);

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
            <h1 class="display-5 fw-bold">Welcome to Jobify!</h1>
            <p class="lead text-muted">Find a job or offer your services – all in one place.</p>
        </div>

        <div class="row mb-4">
            <div class="col-md-8 mx-auto">
                <form class="d-flex">
                    <input class="form-control me-2" type="search" name="q" placeholder="Search ads (e.g. developer, Belgrade...)" aria-label="Search" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                    <button class="btn btn-primary" type="submit">Search</button>
                </form>
            </div>
        </div>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error_message); ?></div>
        <?php elseif (empty($ads)): ?>
            <div class="text-center my-5 py-5">
                <p class="lead text-muted">No ads posted yet.</p>
                <a href="post_ad.php" class="btn btn-primary btn-lg">Be the first to post an ad!</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($ads as $ad): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <?php if ($ad['type'] === 'looking'): ?>
                                    <h5 class="card-title text-primary"><?php echo htmlspecialchars($ad['title']); ?></h5>
                                <?php else: ?>
                                    <h5 class="card-title text-success"><?php echo htmlspecialchars($ad['title']); ?></h5>
                                <?php endif; ?>

                                <p class="card-text text-muted">
                                    <?php echo htmlspecialchars($ad['author_name']); ?>
                                    <?php if ($ad['location']): ?>
                                        • <?php echo htmlspecialchars($ad['location']); ?>
                                    <?php endif; ?>
                                </p>

                                <p class="card-text"><?php echo nl2br(htmlspecialchars($ad['description'])); ?></p>

                                <small class="text-muted">
                                    Posted: <?php echo date('M d, Y', strtotime($ad['created_at'])); ?>
                                </small>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="ad_details.php?id=<?php echo $ad['id']; ?>" class="btn btn-outline-primary btn-sm">View Ad</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <p>&copy; 2025 Jobify. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script/script.js"></script>
</body>
</html>