<?php
session_start();
$books = json_decode(file_get_contents('data/books.json'), true);
$users = json_decode(file_get_contents('data/users.json'), true);
$logged_in_user = $_SESSION['username'] ?? null;

if (!$logged_in_user) {
    header('Location: login.php');
    exit;
}

$book_id = $_GET['id'] ?? null;
$book = $books[$book_id] ?? null;

if (!$book) {
    header('Location: index.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = $_POST['rating'] ?? null;
    $comment = trim($_POST['comment'] ?? '');

    if (!$rating || !is_numeric($rating) || $rating < 1 || $rating > 5) {
        $errors[] = 'Rating is required and must be a number between 1 and 5';
    }

    if (empty($errors)) {
        $review = [
            'username' => $logged_in_user,
            'rating' => $rating,
            'comment' => $comment
        ];

        $books[$book_id]['reviews'][] = $review;
        $users[$logged_in_user]['reviews'][] = [
            'book_id' => $book_id,
            'rating' => $rating,
            'comment' => $comment
        ];

        file_put_contents('data/books.json', json_encode($books, JSON_PRETTY_PRINT));
        file_put_contents('data/users.json', json_encode($users, JSON_PRETTY_PRINT));

        header('Location: details.php?id=' . urlencode($book_id));
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Review | IK-Library</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/forms.css">
</head>

<body>
    <header>
        <h1><a href="index.php">IK-Library</a> > Add Review for <?= htmlspecialchars($book['title']) ?></h1>
    </header>
    <div id="content">
        <form action="add_review.php?id=<?= urlencode($book_id) ?>" method="post" novalidate>
            <h2>Add Review</h2>
            <?php if ($errors): ?>
                <div class="error">
                    <?php foreach ($errors as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div>
                <label for="rating">Rating (1-5):</label>
                <input type="number" name="rating" id="rating" min="1" max="5" required>
            </div>
            <div>
                <label for="comment">Comment:</label>
                <textarea name="comment" id="comment"></textarea>
            </div>
            <div>
                <button type="submit" class="user-button">Submit Review</button>
            </div>
        </form>
    </div>
    <footer>
        <p>IK-Library | ELTE IK Webprogramming</p>
    </footer>
</body>
</html>
