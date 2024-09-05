<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: home.php'); // Redirect if already logged in
    exit();
}

if (isset($_POST['register'])) {
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $check_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $check_email->execute([$email]);

        if ($check_email->rowCount() > 0) {
            $message[] = 'Email already exists!';
        } else {
            $insert_user = $conn->prepare("INSERT INTO `users` (name, email, password, user_type) VALUES (?, ?, ?, 'user')");
            $insert_user->execute([$name, $email, $hashed_password]);
            $message[] = 'Account created successfully!';
        }
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <form action="" method="post">
        <h3>Register Now</h3>
        <input type="text" name="name" required placeholder="Enter your name" maxlength="50">
        <input type="email" name="email" required placeholder="Enter your email" maxlength="50">
        <input type="password" name="password" required placeholder="Enter your password" maxlength="20">
        <input type="submit" value="Register Now" name="register">
        <p>Already have an account?</p>
        <a href="login.php">Login Here</a>
    </form>
    <?php
    if (!empty($message)) {
        foreach ($message as $msg) {
            echo "<p>$msg</p>";
        }
    }
    ?>
</body>
</html>
