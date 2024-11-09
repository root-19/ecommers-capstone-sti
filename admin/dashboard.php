<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}


?>


   <title>Admin Dashboard</title>

  




<?php include '../components/admin_header.php'; ?>

<section class="dashboard">

   <h1 class="heading">HP Performance Dashboard</h1>

   <div class="box-container">

      <div class="box">
         <h3>Welcome!</h3>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="update_profile.php" class="btn">update profile</a>
      </div>

    <!-- Total Pending Orders -->
   <!-- Total Pending Orders -->
<div class="box">
   <?php
      $total_pendings = 0; // Initialize the count to 0

      // Prepare the query to count the number of pending orders
      $select_pendings = $conn->prepare("SELECT COUNT(*) AS pending_count FROM `orders` WHERE payment_status = ?");
      $select_pendings->execute(['pending']);
      $result = $select_pendings->fetch(PDO::FETCH_ASSOC);

      if ($result) {
         $total_pendings = $result['pending_count']; // Set the count of pending orders
      }
   ?>
   <h3><?= $total_pendings; ?></h3>
   <p>Total Pending Orders</p>
   <a href="placed_orders.php" class="btn">See Orders</a>
</div>

<div class="box">
   <?php
      $total_completes = 0;
      
      $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
      $select_completes->execute(['completed']);
      if($select_completes->rowCount() > 0){
         while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
            // Ensure product_quantities is numeric
            if(is_numeric($fetch_completes['product_quantities'])){
               $total_completes += (int)$fetch_completes['product_quantities']; // Cast to int to avoid warnings
            }
         }
      }
   ?>


   <h3><?= $total_completes; ?></h3>
   <p>completed orders</p>
   <a href="placed_orders.php" class="btn">see orders</a>
</div>

      <!-- Other Stats like total products, users, admins -->
      <div class="box">
         <?php
            $select_products = $conn->prepare("SELECT * FROM `products`");
            $select_products->execute();
            $number_of_products = $select_products->rowCount();
         ?>
         <h3><?= $number_of_products; ?></h3>
         <p>products added</p>
         <a href="products.php" class="btn">see products</a>
      </div>

      <!-- Orders Placed -->
      <div class="box">
         <?php
            $select_orders = $conn->prepare("SELECT * FROM `orders`");
            $select_orders->execute();
            $number_of_orders = $select_orders->rowCount();
         ?>
         <h3><?= $number_of_orders; ?></h3>
         <p>orders placed</p>
         <a href="placed_orders.php" class="btn">see orders</a>
      </div>

      <!-- Users Count -->
      <!-- <div class="box">
         <?php
            $select_users = $conn->prepare("SELECT * FROM `users`");
            $select_users->execute();
            $number_of_users = $select_users->rowCount();
         ?>
         <h3><?= $number_of_users; ?></h3>
         <p>normal users</p>
         <a href="users_accounts.php" class="btn">see users</a>
      </div> -->

      <!-- <div class="box">
         <?php
            $select_admins = $conn->prepare("SELECT * FROM `admins`");
            $select_admins->execute();
            $number_of_admins = $select_admins->rowCount();
         ?>
         <h3><?= $number_of_admins; ?></h3>
         <p>admin users</p>
         <a href="admin_accounts.php" class="btn">see admins</a>
      </div> -->

      <!-- New Messages -->
      <div class="box">
         <?php
            $select_messages = $conn->prepare("SELECT * FROM `messages`");
            $select_messages->execute();
            $number_of_messages = $select_messages->rowCount();
         ?>
         <h3><?= $number_of_messages; ?></h3>
         <p>new messages</p>
         <a href="messages.php" class="btn">see messages</a>
      </div>
   <!-- Total Sold Products -->
<div class="box">
<?php
   $total_sold_products = 0;
   
   // Optimized query to directly sum 'product_quantities' for completed orders
   $select_sold_products = $conn->prepare("SELECT SUM(product_quantities) AS total_quantities FROM `orders` WHERE payment_status = ?");
   $select_sold_products->execute(['completed']);
   
   // Fetch the result
   $result = $select_sold_products->fetch(PDO::FETCH_ASSOC);
   
   // Check if the result contains a valid total and assign it
   if ($result && is_numeric($result['total_quantities'])) {
      $total_sold_products = (int)$result['total_quantities'];
   }
?>
   
   <h3><?= $total_sold_products; ?></h3>
   <p>total sold products</p>
   <a href="placed_orders.php" class="btn">see orders</a>
</div>

<!-- Total Sales Revenue -->
<div class="box">
   <?php
      $total_sales_revenue = 0;
      
      $select_sales_revenue = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
      $select_sales_revenue->execute(['completed']);
      if($select_sales_revenue->rowCount() > 0){
         while($fetch_sales_revenue = $select_sales_revenue->fetch(PDO::FETCH_ASSOC)){
            $total_sales_revenue += $fetch_sales_revenue['total_price'];
         }
      }
   ?>
   <h3><span>₱</span><?= $total_sales_revenue; ?></h3>
   <p>total sales revenue</p>
   <a href="placed_orders.php" class="btn">see orders</a>
</div>

   </div>

</section>
