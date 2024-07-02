<?php
session_start();
if (!isset($_SESSION['username']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

$books = json_decode(file_get_contents('data/books.json'), true);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];
    $year = $_POST['year'];
    $image = $_POST['image'];
    $planet = $_POST['planet'];

    if (empty($title) || empty($author) || empty($year) || empty($image) || empty($planet)) {
        $errors[] = "All fields except description are required.";
    } elseif (!preg_match('/^P[0-9]{2}-[0-9]{3,4}$/', $planet)) {
        $errors[] = "Invalid planet code format.";
    } else {
        $newBook = [
            'title' => $title,
            'author' => $author,
            'description' => $description,
            'year' => $year,
            'image' => $image,
            'planet' => $planet,
            'reviews' => []
        ];
        $books[] = $newBook;
        file_put_contents('data/books.json', json_encode($books));
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Book</title>
    <link rel="stylesheet" href="styles/main.css">
</head>
<body>
    <form action="admin.php" method="post">
        <label>Title: <input type="text" name="title" required></label>
        <label>Author: <input type="text" name="author" required></label>
        <label>Description: <textarea name="description"></textarea></label>
        <label>Year: <input type="number" name="year" required></label>
        <label>Image: <input type="text" name="image" required></label>
        <label>Planet: <input type="text" name="planet" required></label>
        <button type="submit">Add Book</button>
        <?php foreach ($errors as $error): ?>
            <div><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
    </form>
</body>
</html>
