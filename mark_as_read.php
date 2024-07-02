<?php
session_start();
$books = json_decode(file_get_contents('data/books.json'), true);
$users = json_decode(file_get_contents('data/users.json'), true);
$logged_in_user = $_SESSION['username'] ?? null;

if (!$logged_in_user) {
    header('Location: login.php');
    exit;
}

$book_id = $_POST['book_id'] ?? null;
$book = $books[$book_id] ?? null;

if (!$book) {
    header('Location: index.php');
    exit;
}

$users[$logged_in_user]['read_books'][] = $book_id;
$books[$book_id]['read_by'][] = [
    'username' => $logged_in_user,
    'time' => date('Y-m-d H:i:s')
];

file_put_contents('data/books.json', json_encode($books, JSON_PRETTY_PRINT));
file_put_contents('data/users.json', json_encode($users, JSON_PRETTY_PRINT));

header('Location: details.php?id=' . urlencode($book_id));
exit;
?>
