<?php

include 'components/connect.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
session_start();

// Get user ID from session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

if (isset($_POST['submit'])) {
    // Sanitize and validate input data
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $pass = filter_var(trim($_POST['pass']), FILTER_SANITIZE_STRING);
    $cpass = filter_var(trim($_POST['cpass']), FILTER_SANITIZE_STRING);

    $address = filter_var(trim($_POST['address']), FILTER_SANITIZE_STRING);
    $city = filter_var(trim($_POST['city']), FILTER_SANITIZE_STRING);
    $country = filter_var(trim($_POST['country']), FILTER_SANITIZE_STRING);
    $mobile = filter_var(trim($_POST['mobile']), FILTER_SANITIZE_STRING);
    $pin_code = filter_var(trim($_POST['pin_code']), FILTER_SANITIZE_STRING);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message[] = 'Invalid email format!';
    } else {
        // Check if email already exists
        $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $select_user->execute([$email]);

        if ($select_user->rowCount() > 0) {
            $message[] = 'Email already exists!';
        } else {
            // Check if passwords match
            if ($pass !== $cpass) {
                $message[] = 'Confirm password does not match!';
            } else {
                // Hash the password
                $hashed_pass = sha1($pass);

                // Set the user type (e.g., 'admin' or 'user')
                $user_type = 'user'; 

                // Insert user into the database
                $insert_user = $conn->prepare("INSERT INTO `users` (name, email, password, address, city, country, mobile, pin_code, user_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                if ($insert_user->execute([$name, $email, $hashed_pass, $address, $city, $country, $mobile, $pin_code, $user_type])) {
                    // Send OTP email
                    sendOTP($email);
                    header("Location: otp-verify.php"); 
                    exit();
                } else {
                    $message[] = 'Registration failed, please try again!';
                }
            }
        }
    }
}

function sendOTP($email) {
    $otp = rand(100000, 999999); 
    $_SESSION['otp'] = $otp; 
    $_SESSION['otp_expiry'] = time() + 300; 

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();                                       
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'wasieacuna@gmail.com';
        $mail->Password   = 'qipc vais smfq rwim';                
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;           
        $mail->Port       = 587;                                    
        // Recipients
        $mail->setFrom('wasieacuna@gmail.com', 'HD');
        $mail->addAddress($email);                            

        // Content
        $mail->isHTML(true);                                       
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = 'Your OTP code is: ' . $otp;

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
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
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="form-container">
   <form action="" method="post">
      <h3>Register Now</h3>
      <div class="form-row">
         <div class="form-column">
            <input type="text" name="name" required placeholder="Enter your username" maxlength="20" class="box">
            <input type="password" name="pass" required placeholder="Enter your password" maxlength="20" class="box">
            <input type="text" name="address" required placeholder="Enter your address" maxlength="100" class="box">
            <input type="text" name="country" required placeholder="Enter your country" maxlength="50" class="box">
            <input type="text" name="pin_code" required placeholder="Enter your pin code" maxlength="10" class="box">
         </div>
         <div class="form-column">
            <input type="email" name="email" required placeholder="Enter your email" maxlength="50" class="box">
            <input type="password" name="cpass" required placeholder="Confirm your password" maxlength="20" class="box">
            <input type="text" name="city" required placeholder="Enter your city" maxlength="50" class="box">
            <input type="text" name="mobile" required placeholder="Enter your mobile number" maxlength="15" class="box">
         </div>
      </div>
      <input type="submit" value="Register Now" class="btn" name="submit">
      <p>Already have an account?</p>
      <a href="user_login.php" class="option-btn">Login now</a>
   </form>
</section>


<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>