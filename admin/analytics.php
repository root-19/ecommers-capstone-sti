<?php
include '../components/connect.php';
session_start();

$admin_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;

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
   .filter-container {
      margin: 20px 0;
   }
   .input-group .form-select {
        border-radius: 0.5rem 0 0 0.5rem;
        border: 1px solid #007bff;
    }

    .input-group .btn {
        border-radius: 0 0.5rem 0.5rem 0;
    }

    .input-group {
        margin-top: 10px;
    }

    /* Optional: Add some hover effects */
    .input-group .form-select:hover {
        border-color: #0056b3;
    }

    .input-group .btn:hover {
        background-color: #0056b3;
    }
</style>

<?php include '../components/admin_header.php'; ?>

<section class="dashboard">
   <h1 class="heading">HP Performance Dashboard</h1>

<!-- Filtering Dropdown -->
<div class="text-center mb-4">
    <label for="filter" class="h5">Filter by:</label>
    <div class="input-group w-50 mx-auto">
        <select id="filter" class="form-select">
            <option value="day">Day</option>
            <option value="week">Week</option>
            <option value="month">Month</option>
        </select>
    </div>
</div>
    <br>

    <section class="charts">
        <!-- Chart for Total Order Pendings -->
        <div class="chart-container">
            <h2>Total Order Pendings</h2>
            <canvas id="pendingOrdersChart"></canvas>
        </div>

        <!-- Chart for Total Order Completes -->
        <div class="chart-container">
            <h2>Total Order Completes</h2>
            <canvas id="completeOrdersChart"></canvas>
        </div>

        <!-- Chart for Number of Orders -->
        <div class="chart-container">
            <h2>Number of Orders</h2>
            <canvas id="numberOfOrdersChart"></canvas>
        </div>

        <!-- Chart for Number of Products -->
        <div class="chart-container">
            <h2>Number of Products</h2>
            <canvas id="numberOfProductsChart"></canvas>
        </div>

        <!-- Chart for Number of Users -->
        <div class="chart-container">
            <h2>Number of Users</h2>
            <canvas id="numberOfUsersChart"></canvas>
        </div>

        <!-- Chart for Number of Admins -->
        <div class="chart-container">
            <h2>Number of Admins</h2>
            <canvas id="numberOfAdminsChart"></canvas>
        </div>

        <!-- Chart for Number of Messages -->
        <div class="chart-container">
            <h2>Number of Messages</h2>
            <canvas id="numberOfMessagesChart"></canvas>
        </div>
    </section>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filter = document.getElementById('filter');

        const fetchDataAndRenderCharts = (timeFrame) => {
            fetch(`fetch_data_sales.php?filter=${timeFrame}`)
                .then(response => response.json())
                .then(data => {
                    if (!data) {
                        console.error('No data received');
                        return;
                    }

                    // Chart data
                    const {
                        total_pendings,
                        total_completes,
                        number_of_orders,
                        number_of_products,
                        number_of_users,
                        number_of_admins,
                        number_of_messages
                    } = data;

                    // Update charts with new data
                    updateCharts(total_pendings, total_completes, number_of_orders, number_of_products, number_of_users, number_of_admins, number_of_messages);
                })
                .catch(error => console.error('Error fetching data:', error));
        };

        const updateCharts = (total_pendings, total_completes, number_of_orders, number_of_products, number_of_users, number_of_admins, number_of_messages) => {
            // Pending Orders Chart
            new Chart(document.getElementById('pendingOrdersChart'), {
                type: 'bar',
                data: {
                    labels: ['Total Order Pendings'],
                    datasets: [{
                        label: 'Amount',
                        data: [total_pendings],
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Amount'
                            }
                        }
                    }
                }
            });

            // Complete Orders Chart
            new Chart(document.getElementById('completeOrdersChart'), {
                type: 'bar',
                data: {
                    labels: ['Total Order Completes'],
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
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Amount'
                            }
                        }
                    }
                }
            });

            // Number of Orders Chart
            new Chart(document.getElementById('numberOfOrdersChart'), {
                type: 'bar',
                data: {
                    labels: ['Total Orders'],
                    datasets: [{
                        label: 'Count',
                        data: [number_of_orders],
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Count'
                            }
                        }
                    }
                }
            });

            // Number of Products Chart
            new Chart(document.getElementById('numberOfProductsChart'), {
                type: 'bar',
                data: {
                    labels: ['Total Products'],
                    datasets: [{
                        label: 'Count',
                        data: [number_of_products],
                        backgroundColor: 'rgba(255, 206, 86, 0.5)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Count'
                            }
                        }
                    }
                }
            });

            // Number of Users Chart
            new Chart(document.getElementById('numberOfUsersChart'), {
                type: 'bar',
                data: {
                    labels: ['Total Users'],
                    datasets: [{
                        label: 'Count',
                        data: [number_of_users],
                        backgroundColor: 'rgba(153, 102, 255, 0.5)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Count'
                            }
                        }
                    }
                }
            });

            // Number of Admins Chart
            new Chart(document.getElementById('numberOfAdminsChart'), {
                type: 'bar',
                data: {
                    labels: ['Total Admins'],
                    datasets: [{
                        label: 'Count',
                        data: [number_of_admins],
                        backgroundColor: 'rgba(255, 159, 64, 0.5)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Count'
                            }
                        }
                    }
                }
            });

            // Number of Messages Chart
            new Chart(document.getElementById('numberOfMessagesChart'), {
                type: 'bar',
                data: {
                    labels: ['Total Messages'],
                    datasets: [{
                        label: 'Count',
                        data: [number_of_messages],
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Count'
                            }
                        }
                    }
                }
            });
        };

        // Initial fetch and render
        fetchDataAndRenderCharts(filter.value);

        // Event listener for filter change
        filter.addEventListener('change', () => {
            fetchDataAndRenderCharts(filter.value);
        });
    });
   </script>
</section>
</body>
</html>
