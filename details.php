<?php
session_start();
$books = json_decode(file_get_contents('data/books.json'), true);
$bookId = $_GET['id'] ?? '';

if (!isset($books[$bookId])) {
    header('Location: index.php');
    exit;
}

$book = $books[$bookId];
$reviews = $book['reviews'] ?? [];
$averageRating = !empty($reviews) ? array_sum(array_column($reviews, 'rating')) / count($reviews) : "No ratings yet";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($book['title']) ?> Details</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/details.css">
</head>
<body>
    <header>
        <h1>Book Details</h1>
    </header>
    <div id="details">
        <div class="image">
            <img src="assets/<?= htmlspecialchars($book['image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
        </div>
        <div class="info">
            <h2><?= htmlspecialchars($book['title']) ?></h2>
            <p>Author: <?= htmlspecialchars($book['author']) ?></p>
            <p>Description: <?= htmlspecialchars($book['description']) ?></p>
            <p>Year: <?= htmlspecialchars($book['year']) ?></p>
            <p>Planet: <?= htmlspecialchars($book['planet']) ?></p>
            <p>Average Rating: <?= $averageRating ?></p>
        </div>
        <?php if (isset($_SESSION['username'])): ?>
            <form action="submit_rating.php" method="post">
                <input type="number" name="rating" min="1" max="5" required>
                <textarea name="review" required></textarea>
                <input type="hidden" name="book_id" value="<?= $bookId ?>">
                <button type="submit">Submit Review</button>
            </form>
        <?php endif; ?>
        <a href="index.php">Return to main page</a>
    </div>
    <footer>
        <p>IK-Library | ELTE IK Webprogramming</p>
    </footer>
</body>
</html>
