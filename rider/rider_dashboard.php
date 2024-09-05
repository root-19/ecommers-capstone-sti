<?php
include '../components/connect.php';
session_start();

// Check if the user is logged in and is a rider
if (!isset($_SESSION['user_id'])) {
    header('Location: ../user_login.php');
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];

// Get user information
$select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_user->execute([$user_id]);
$user_data = $select_user->fetch(PDO::FETCH_ASSOC);

// If the user is not a rider, redirect to home
if ($user_data['user_type'] !== 'rider') {
    header('Location: ../home.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Dashboard</title>
    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include '../components/user_header.php'; ?>

<section class="dashboard">
    <h1>Welcome, <?php echo $user_data['name']; ?>!</h1>
    <p>This is your rider dashboard where you can manage your deliveries and view your profile information.</p>
    
    <!-- Example of specific rider-related information -->
    <div class="dashboard-content">
        <h2>Delivery Summary</h2>
        <p>You have completed X deliveries this week.</p>
        <a href="view_deliveries.php" class="btn">View All Deliveries</a>
    </div>

    <div class="dashboard-content">
        <h2>Profile Information</h2>
        <p><strong>Name:</strong> <?php echo $user_data['name']; ?></p>
        <p><strong>Email:</strong> <?php echo $user_data['email']; ?></p>
        <p><strong>Mobile:</strong> <?php echo $user_data['mobile']; ?></p>
        <a href="edit_profile.php" class="btn">Edit Profile</a>
    </div>
</section>

<?php include '../components/footer.php'; ?>

<script src="../js/script.js"></script>

</body>
</html>
