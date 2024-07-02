<?php
session_start();
if (!isset($_SESSION['username']) || !$_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Location: index.php');
    exit;
}

$bookId = $_POST['book_id'];
$rating = $_POST['rating'];
$review = $_POST['review'];

$books = json_decode(file_get_contents('data/books.json'), true);
if (isset($books[$bookId])) {
    $books[$bookId]['reviews'][] = [
        'user' => $_SESSION['username'],
        'rating' => $rating,
        'review' => $review
    ];
    file_put_contents('data/books.json', json_encode($books));
}

header("Location: details.php?id=$bookId");
exit;
