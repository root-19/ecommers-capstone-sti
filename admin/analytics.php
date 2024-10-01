<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['id'];

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
      flex-wrap: wrap; /* Allows wrapping if there are too many items */
      justify-content: space-between; /* Evenly distributes space between charts */
   }

   .chart-container {
      width: 30%; /* Each chart takes 30% of the row width */
      height: 200px; /* Adjust the height of the charts */
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

            // Chart for Total Order Pendings (Bar Chart)
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

            // Chart for Completed Orders (Pie Chart)
            new Chart(document.getElementById('completedOrdersChart'), {
                type: 'pie',
                data: {
                    labels: ['Completed Orders'],
                    datasets: [{
                        label: 'Amount',
                        data: [total_completes],
                        backgroundColor: ['rgba(75, 192, 192, 0.5)', 'rgba(255, 206, 86, 0.5)'],
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                }
            });

            // Chart for Total Orders Placed (Doughnut Chart)
            new Chart(document.getElementById('totalOrdersChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Total Orders Placed'],
                    datasets: [{
                        label: 'Count',
                        data: [number_of_orders],
                        backgroundColor: ['rgba(153, 102, 255, 0.5)', 'rgba(255, 159, 64, 0.5)'],
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                }
            });

            // Chart for Products Added (Bar Chart)
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

            // Chart for Normal Users (Pie Chart)
            new Chart(document.getElementById('normalUsersChart'), {
                type: 'pie',
                data: {
                    labels: ['Normal Users'],
                    datasets: [{
                        label: 'Count',
                        data: [number_of_users],
                        backgroundColor: ['rgba(54, 162, 235, 0.5)', 'rgba(255, 99, 132, 0.5)'],
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                }
            });

            // Chart for Admin Users (Doughnut Chart)
            new Chart(document.getElementById('adminUsersChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Admin Users'],
                    datasets: [{
                        label: 'Count',
                        data: [number_of_admins],
                        backgroundColor: ['rgba(255, 99, 132, 0.5)', 'rgba(75, 192, 192, 0.5)'],
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                }
            });

            // Chart for New Messages (Bar Chart)
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
