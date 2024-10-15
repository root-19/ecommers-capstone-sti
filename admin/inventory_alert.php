<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

// Check product quantities
$check_quantity = $conn->prepare("SELECT * FROM `products` WHERE quantity <= 50");
$check_quantity->execute();
$low_stock_products = $check_quantity->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Alert</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/admin_style.css">

    <!-- SweetAlert CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function showAlert(productName, quantity) {
            Swal.fire({
                icon: 'warning',
                title: 'Low Stock Alert',
                text: `Product '${productName}' has a low quantity of ${quantity}.`,
                confirmButtonText: 'OK'
            });
        }
    </script>
</head>
<body class="bg-gray-100">

<?php include '../components/admin_header.php'; ?>

<section class="container mx-auto p-8">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Low Stock Alert</h1>

        <?php if (!empty($low_stock_products)): ?>
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6">
                <h3 class="font-semibold">The following products have a quantity of 50 or less:</h3>
            </div>
            <ul class="list-disc pl-6 space-y-2">
                <?php foreach ($low_stock_products as $product): ?>
                    <li class="text-lg text-gray-700">
                        <?= $product['name'] . ' - Quantity: ' . $product['quantity']; ?>
                        <script>
                            // Call the SweetAlert function for each low-stock product
                            showAlert("<?= $product['name']; ?>", <?= $product['quantity']; ?>);
                        </script>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                <p>All products are sufficiently stocked.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

</body>
</html>
