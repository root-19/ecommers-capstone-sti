<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
} else {
    $user_id = '';
}

if (isset($_POST['submit'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $pass = filter_var(sha1(trim($_POST['pass'])), FILTER_SANITIZE_STRING);
    // $user_type = 'rider';

   
    try {
        $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
        $select_user->execute([$email, $pass]);
        $row = $select_user->fetch(PDO::FETCH_ASSOC);
    
        if ($select_user->rowCount() > 0) {
            // User exists in the database
            $_SESSION['id'] = $row['id'];
        
            // Check the status of the user (e.g., if they are banned or not activated)
            if ($row['status'] === 'banned') {
                $message[] = 'Your account has been banned. Please contact support.';
            } elseif ($row['status'] === 'pending') {
                $message[] = 'Your account is not yet activated. Please check your email for the activation link.';
            } else {
                // Proceed based on user type
                switch ($row['user_type']) {
                    case 'admin':
                        // Additional conditions for admin can go here
                        header('Location: ../admin/dashboard.php');
                        break;
                    case 'rider':
                        // Additional conditions for rider can go here
                        header('Location: ../rider/rider_dashboard.php');
                        break;
                    default:
                        // Default user (regular)
                        header('Location: home.php');
                        break;
                }
                exit();
            }
        } else {
            // User not found or incorrect email/password
            $message[] = 'Incorrect email or password!';
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
    <title>Login</title>
   
    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="form-container">
    <form action="" method="post">
        <h3>Login Now</h3>
        <input type="email" name="email" required placeholder="Enter your email" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" name="pass" required placeholder="Enter your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="submit" value="Login Now" class="btn" name="submit">
        <p>Don't have an account?</p>
        <a href="user_register.php" class="option-btn">Register Now</a>
    </form>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
