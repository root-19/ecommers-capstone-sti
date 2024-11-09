<?php
if (isset($message)) {
    foreach ($message as $message) {
        echo '
        <div class="message">
            <span>' . $message . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>


<script>
    // Toggle the navigation menu on small devices
    document.getElementById('menu-btn').addEventListener('click', function () {
        const navbar = document.querySelector('.navbar');
        navbar.classList.toggle('active'); 
    });
</script>

<link rel="stylesheet" href="../css/admin_style.css">
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!--    
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css"> -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<header class="header">
    <section class="flex">
       <a href="../admin/dashboard.php" class="logo">HP<span style="color:white;">AdminPanel</span></a> <
        <br>
        <!-- Navigation bar -->
        <nav class="navbar">
            <!-- <a href="../admin/dashboard.php" class="logo">HP<span style="color:white;">AdminPanel</span></a> -->
            <a href="../admin/dashboard.php">Home</a>
            <a href="../admin/products.php">Products</a>
            <a href="../admin/placed_orders.php">Orders</a>
            <a href="../admin/admin_accounts.php">Admins</a>
            <a href="../admin/users_accounts.php">Users</a>
            <a href="../admin/purchase.php">Purchase</a>
            <a href="../admin/messages.php">Messages</a>
            <a href="../admin/archive.php">Archive</a>
            <a href="../admin/analytics.php">Analytics</a>
            <a href="../admin/inventory_alert.php">Alerts</a>
            <a href="../admin/notif_status.php">Rider</a>
        </nav>

        <div class="icons">
        <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        <div class="profile">
            <?php
            $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <p><?= $fetch_profile['name']; ?></p>
            <a href="../admin/update_profile.php" class="btn">update profile</a>
            <div class="flex-btn"></div>
            <a href="../components/admin_logout.php" class="delete-btn" onclick="return confirm('logout from the website?');">logout</a>
        </div>
    </section>
</header>


