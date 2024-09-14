<?php
include '../components/connect.php';
session_start();

// Check if the user is logged in and is a rider
if (!isset($_SESSION['user_id'])) {
    header('Location: ../user_login.php');
    exit();
}

if (isset($_POST['update_delivery'])) {
    $order_id = $_POST['order_id'];
    $image = $_FILES['receipt_image'];

    // Handle file upload
    if ($image['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        $file_name = basename($image['name']);
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($image['tmp_name'], $file_path)) {
            // Update order with delivery status and receipt image path
            $update_delivery = $conn->prepare("UPDATE `orders` SET status = 'delivered', receipt_image = ? WHERE id = ?");
            $update_delivery->execute([$file_name, $order_id]);
            $message[] = 'Order marked as delivered and receipt uploaded!';
        } else {
            $message[] = 'Failed to upload receipt image.';
        }
    } else {
        $update_delivery = $conn->prepare("UPDATE `orders` SET status = 'delivered' WHERE id = ?");
        $update_delivery->execute([$order_id]);
        $message[] = 'Order marked as delivered!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placed Orders</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .receipt-image {
            width: 100px;
            height: auto;
            max-height: 100px;
            display: block;
            margin: 0 auto;
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-gray-100">

<!-- Responsive Header -->
<header class="bg-red-600 p-4 flex justify-between items-center text-white">
    <h1 class="text-xl font-bold">Placed Orders</h1>
    <nav class="flex gap-4">
        <form action="../components/user_logout" method="POST" class="inline">
            <button type="submit" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded">Logout</button>
        </form>
    </nav>
</header>

<!-- Main Content -->
<section class="orders p-4">

    <h2 class="text-2xl font-semibold mb-4">Pending Orders</h2>
    <div class="table-container overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Placed On</th>
                    <th class="border px-4 py-2">Name</th>
                    <th class="border px-4 py-2">Number</th>
                    <th class="border px-4 py-2">Address</th>
                    <th class="border px-4 py-2">Total Products</th>
                    <th class="border px-4 py-2">Total Price</th>
                    <th class="border px-4 py-2">Receipt</th>
                    <th class="border px-4 py-2">Payment Method</th>
                    <th class="border px-4 py-2">Delivered</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                $select_pending_orders = $conn->prepare("
                    SELECT orders.*, users.name, users.mobile, users.address 
                    FROM `orders` 
                    JOIN `users` ON orders.user_id = users.id
                    WHERE orders.payment_status = 'pending'
                ");
                $select_pending_orders->execute();
                if ($select_pending_orders->rowCount() > 0) {
                    while ($fetch_orders = $select_pending_orders->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr class="hover:bg-gray-100">
                    <td class="border px-4 py-2"><?= $fetch_orders['placed_on']; ?></td>
                    <td class="border px-4 py-2"><?= $fetch_orders['name']; ?></td>
                    <td class="border px-4 py-2"><?= $fetch_orders['mobile']; ?></td>
                    <td class="border px-4 py-2"><?= $fetch_orders['address']; ?></td>
                    <td class="border px-4 py-2"><?= $fetch_orders['product_quantities']; ?></td>
                    <td class="border px-4 py-2">&#8369;<?= $fetch_orders['total_price']; ?>/-</td>
                    <td class="border px-4 py-2">
                        <?php if (!empty($fetch_orders['receipt_image'])): ?>
                            <a href="../uploads/<?= $fetch_orders['receipt_image']; ?>" target="_blank">
                                <img src="../uploads/<?= $fetch_orders['receipt_image']; ?>" alt="Receipt Image" class="receipt-image">
                            </a>
                        <?php else: ?>
                            No Receipt
                        <?php endif; ?>
                    </td>
                    <td class="border px-4 py-2"><?= $fetch_orders['method']; ?></td>
                    <td class="border px-4 py-2">
                        <form action="" method="post" enctype="multipart/form-data" style="display: inline;">
                            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                            <input type="file" name="receipt_image">
                            <input type="checkbox" name="delivery_status" onchange="this.form.submit()" <?= $fetch_orders['status'] == 'delivered' ? 'checked' : ''; ?>>
                            <input type="hidden" name="update_delivery">
                        </form>
                    </td>
                    <!-- Removed Delete Action -->
                </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="10" class="text-center p-4">No pending orders!</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<script src="../js/admin_script.js"></script>
</body>
</html>


