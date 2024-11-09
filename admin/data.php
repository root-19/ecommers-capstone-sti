<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Dashboard</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
<style>
   .charts {
      display: flex;
      flex-wrap: wrap; 
      justify-content: space-between; 
   }

   .chart-container {
      width: 30%; 
      height: 200px;
      margin: 20px 0;
      background-color: #fff;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      border-radius: 5px;
   }

   canvas {
      width: 100% !important;
      height: 100% !important;
   }
</style>


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
<div class="box">
   <?php
      $total_pendings = 0;

      $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE status = ?");
      $select_pendings->execute(['pending']);
      if($select_pendings->rowCount() > 0){
         while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
            
            $total_pendings += $fetch_pendings['product_quantities'];
         }
      }
   ?>
   <h3><?= $total_pendings; ?></h3>
   <p>total order pendings</p>
   <a href="placed_orders.php" class="btn">see orders</a>
</div>


    <!-- Completed Orders -->
<div class="box">
   <?php
      $total_completes = 0;
     
      $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
      $select_completes->execute(['completed']);
      if($select_completes->rowCount() > 0){
         while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
           
            $total_completes += $fetch_completes['product_quantities'];
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
      <div class="box">
         <?php
            $select_users = $conn->prepare("SELECT * FROM `users`");
            $select_users->execute();
            $number_of_users = $select_users->rowCount();
         ?>
         <h3><?= $number_of_users; ?></h3>
         <p>normal users</p>
         <a href="users_accounts.php" class="btn">see users</a>
      </div>

      <div class="box">
         <?php
            $select_admins = $conn->prepare("SELECT * FROM `admins`");
            $select_admins->execute();
            $number_of_admins = $select_admins->rowCount();
         ?>
         <h3><?= $number_of_admins; ?></h3>
         <p>admin users</p>
         <a href="admin_accounts.php" class="btn">see admins</a>
      </div>

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
      
      $select_sold_products = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
      $select_sold_products->execute(['completed']);
      if($select_sold_products->rowCount() > 0){
         while($fetch_sold_products = $select_sold_products->fetch(PDO::FETCH_ASSOC)){
            $total_sold_products += $fetch_sold_products['product_quantities'];
         }
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

<!-- Charts Section -->
<h2 class="heading">Sales Analytics</h2>
<section class="charts">
   

   <div class="chart-container">
      <canvas id="pendingOrdersChart"></canvas>
   </div>

   <div class="chart-container">
      <canvas id="completedOrdersChart"></canvas>
   </div>

   <div class="chart-container">
      <canvas id="totalOrdersChart"></canvas>
   </div>

   <div class="chart-container">
      <canvas id="productsAddedChart"></canvas>
   </div>

   <div class="chart-container">
      <canvas id="normalUsersChart"></canvas>
   </div>

   <div class="chart-container">
      <canvas id="adminUsersChart"></canvas>
   </div>

   <div class="chart-container">
      <canvas id="newMessagesChart"></canvas>
   </div>
</section>


<script src="../js/admin_script.js"></script>

<script>
 document.addEventListener('DOMContentLoaded', function() {
    fetch('fetch_data_sales.php')
    .then(response => response.json())
        .then(data => {
            if (!data) {
                console.error('No data received');
                return;
            }

            const {
                total_pendings,
                total_completes,
                number_of_orders,
                number_of_products,
                number_of_users,
                number_of_admins,
                number_of_messages
            } = data;

            // Chart for Total Order Pendings
            new Chart(document.getElementById('pendingOrdersChart'), {
                type: 'bar',
                data: {
                    labels: ['Total Order Pendings'],
                    datasets: [{
                        label: 'Amount',
                        data: [total_pendings],
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Chart for Completed Orders
            new Chart(document.getElementById('completedOrdersChart'), {
                type: 'bar',
                data: {
                    labels: ['Total Completed Orders'],
                    datasets: [{
                        label: 'Amount',
                        data: [total_completes],
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Chart for Total Orders Placed
            new Chart(document.getElementById('totalOrdersChart'), {
                type: 'bar',
                data: {
                    labels: ['Total Orders Placed'],
                    datasets: [{
                        label: 'Count',
                        data: [number_of_orders],
                        backgroundColor: 'rgba(153, 102, 255, 0.5)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Chart for Products Added
            new Chart(document.getElementById('productsAddedChart'), {
                type: 'bar',
                data: {
                    labels: ['Products Added'],
                    datasets: [{
                        label: 'Count',
                        data: [number_of_products],
                        backgroundColor: 'rgba(255, 159, 64, 0.5)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Chart for Normal Users
            new Chart(document.getElementById('normalUsersChart'), {
                type: 'bar',
                data: {
                    labels: ['Normal Users'],
                    datasets: [{
                        label: 'Count',
                        data: [number_of_users],
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Chart for Admin Users
            new Chart(document.getElementById('adminUsersChart'), {
                type: 'bar',
                data: {
                    labels: ['Admin Users'],
                    datasets: [{
                        label: 'Count',
                        data: [number_of_admins],
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Chart for New Messages
            new Chart(document.getElementById('newMessagesChart'), {
                type: 'bar',
                data: {
                    labels: ['New Messages'],
                    datasets: [{
                        label: 'Count',
                        data: [number_of_messages],
                        backgroundColor: 'rgba(255, 205, 86, 0.5)',
                        borderColor: 'rgba(255, 205, 86, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

        })
        .catch(error => console.error('Error fetching data:', error));
});
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>
</html>
