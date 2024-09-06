<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if (isset($_POST['register'])) {
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);

    // Hash the passwfilerord
     $hashed_pass = sha1($password);

    try {
        $check_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $check_email->execute([$email]);

        if ($check_email->rowCount() > 0) {
            $message[] = 'Email already exists!';
        } else {
            $insert_user = $conn->prepare("INSERT INTO `users` (name, email, password, user_type) VALUES (?, ?, ?, 'rider')");
            $insert_user->execute([$name, $email, $hashed_pass]);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
    
<?php include '../components/admin_header.php'; ?>
<section class="form-container">
    <form action="" method="post">
        <h3>Register Now</h3>
        <input type="text" name="name" class="box" required placeholder="Enter your name" maxlength="50">
        <input type="email" name="email" class="box" required placeholder="Enter your email" maxlength="50">
        <input type="password" name="password" class="box" required placeholder="Enter your password" maxlength="20">
        <input type="submit" value="Register Now" class="btn" name="register">
    </form>
</section>
<?php
if (!empty($message)) {
    foreach ($message as $msg) {
        echo "<p>$msg</p>";
    }
}
?>
</body>
</html>
