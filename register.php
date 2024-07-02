<?php
session_start();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $users = json_decode(file_get_contents('data/users.json'), true);

    // Validation
    if (empty($username) || strlen($username) < 4) {
        $errors[] = "Enter a name that is at least 4 characters long!";
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Enter a valid email address!";
    } elseif (empty($password) || empty($confirm_password)) {
        $errors[] = "Enter the password and confirm password!";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match!";
    } elseif (array_filter($users, fn($user) => $user['username'] === $username)) {
        $errors[] = "Username already exists!";
    }

    if (empty($errors)) {
        $newUser = [
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'is_admin' => false,
            'reviews' => [],
            'booksRead' => [],
            'last_login' => date('Y-m-d H:i:s')
        ];
        $users[] = $newUser;
        file_put_contents('data/users.json', json_encode($users));
        $_SESSION['username'] = $username;
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
    <title>Register</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/auth.css">
</head>
<body>
    <div class="auth-container">
        <h2>Register</h2>
        <form action="register.php" method="post">
            <label>Username: <input type="text" name="username" required value="<?= htmlspecialchars($username ?? '') ?>"></label>
            <label>Email: <input type="email" name="email" required value="<?= htmlspecialchars($email ?? '') ?>"></label>
            <label>Password: <input type="password" name="password" required></label>
            <label>Confirm Password: <input type="password" name="confirm_password" required></label>
            <button type="submit">Register</button>
            <?php foreach ($errors as $error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
