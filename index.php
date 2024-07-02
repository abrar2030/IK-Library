<?php
session_start();
$books = json_decode(file_get_contents('data/books.json'), true);
$logged_in_user = $_SESSION['username'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IK-Library | Home</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>

<body>
    <header>
        <h1><a href="index.php">IK-Library</a> > Home</h1>
        <div class="user-info">
            <?php if ($logged_in_user): ?>
                <span>Welcome, <?= htmlspecialchars($logged_in_user) ?></span>
                <a href="user_profile.php">Profile</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </header>
    <div id="content">
        <div id="card-list">
            <?php foreach ($books as $book_id => $book): ?>
                <div class="book-card">
                    <div class="image">
                        <img src="assets/<?= htmlspecialchars($book['image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                    </div>
                    <div class="details">
                        <h2><a href="details.php?id=<?= $book_id ?>"><?= htmlspecialchars($book['author']) ?> - <?= htmlspecialchars($book['title']) ?></a></h2>
                    </div>
                    <?php if ($logged_in_user && $_SESSION['is_admin']): ?>
                        <div class="edit">
                            <span>Edit</span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <footer>
        <p>IK-Library | ELTE IK Webprogramming</p>
    </footer>
</body>

</html>
