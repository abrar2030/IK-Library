<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$users = json_decode(file_get_contents('data/users.json'), true);
$username = $_SESSION['username'];
$user = array_filter($users, fn($user) => $user['username'] === $username)[0] ?? null;

if (!$user) {
    echo "User not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="styles/main.css">
</head>
<body>
    <h1>User Profile: <?= htmlspecialchars($user['username']) ?></h1>
    <p>Email: <?= htmlspecialchars($user['email']) ?></p>
    <p>Last Login: <?= htmlspecialchars($user['last_login']) ?></p>
    
    <h2>Books Read</h2>
    <ul>
        <?php foreach ($user['booksRead'] as $bookRead): ?>
            <li><?= htmlspecialchars($bookRead['title']) ?> - <?= htmlspecialchars($bookRead['date']) ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Reviews</h2>
    <ul>
        <?php foreach ($user['reviews'] as $review): ?>
            <li><?= htmlspecialchars($review['bookTitle']) ?> - Rating: <?= htmlspecialchars($review['rating']) ?> - <?= htmlspecialchars($review['text']) ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
