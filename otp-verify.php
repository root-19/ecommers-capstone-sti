<?php
session_start();

if(isset($_POST['verify_otp'])){
    $user_otp = filter_var($_POST['otp'], FILTER_SANITIZE_STRING);

    if(time() > $_SESSION['otp_expiry']){
        $message[] = 'OTP has expired. Please request a new one.';
    } elseif($user_otp == $_SESSION['otp']){
        unset($_SESSION['otp']); 
        unset($_SESSION['otp_expiry']);
        $message[] = 'OTP verified successfully! Redirecting to login...';
        header("Refresh: 2; url=user_login.php"); 
        exit();
    } else {
        $message[] = 'Incorrect OTP. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<section class="form-container">
    <form action="" method="post">
        <h3>Verify OTP</h3>
        <input type="text" name="otp" required placeholder="Enter your OTP" maxlength="6" class="box">
        <input type="submit" value="Verify OTP" class="btn" name="verify_otp">
        <p>If you didn't receive the OTP, please check your spam folder or <a href="resend-otp.php" class="option-btn">resend OTP</a>.</p>
    </form>

    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '<p class="error-msg">'.$msg.'</p>';
        }
    }
    ?>

</section>

<script src="js/script.js"></script>

</body>
</html>
