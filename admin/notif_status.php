<?php
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

// Join orders with users to get user details
$select_notifications = $conn->prepare("
    SELECT orders.*, users.name AS user_name, users.mobile AS user_number, users.address AS user_address
    FROM `orders`
    JOIN `users` ON orders.user_id = users.id
    WHERE orders.status = 'delivered'
    ORDER BY orders.placed_on DESC
");
$select_notifications->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <style>
        .notifications {
            margin: 20px;
        }
        .notifications table {
            width: 100%;
            border-collapse: collapse;
        }
        .notifications th, .notifications td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .notifications th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .notifications tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .notifications tr:hover {
            background-color: #f1f1f1;
        }
        .notifications .timestamp {
            font-size: 0.9em;
            color: #888;
        }
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
<body>

<?php include '../components/admin_header.php'; ?>

<section class="notifications">
    <h1>Delivered Orders</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Placed On</th>
                <th>Name</th>
                <th>Number</th>
                <th>Address</th>
                <th>Total Products</th>
                <th>Total Price</th>
                <th>Receipt</th>
                <th>Payment Method</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($select_notifications->rowCount() > 0) {
                while ($fetch_notification = $select_notifications->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <tr>
                <td><?= $fetch_notification['id']; ?></td>
                <td><?= htmlspecialchars($fetch_notification['placed_on']); ?></td>
                <td><?= htmlspecialchars($fetch_notification['user_name']); ?></td>
                <td><?= htmlspecialchars($fetch_notification['user_number']); ?></td>
                <td><?= htmlspecialchars($fetch_notification['user_address']); ?></td>
                <td><?= htmlspecialchars($fetch_notification['product_quantities']); ?></td>
                <td>&#8369;<?= htmlspecialchars($fetch_notification['total_price']); ?>/-</td>
                <td>
                    <?php if (!empty($fetch_notification['receipt_image'])): ?>
                        <a href="../uploads/<?= htmlspecialchars($fetch_notification['receipt_image']); ?>" target="_blank">
                            <img src="../uploads/<?= htmlspecialchars($fetch_notification['receipt_image']); ?>" alt="Receipt Image" class="receipt-image">
                        </a>
                    <?php else: ?>
                        No Receipt
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($fetch_notification['method']); ?></td>
            </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="8" style="text-align: center; padding: 8px;">No delivered orders!</td></tr>';
            }
            ?>
        </tbody>
    </table>
</section>

</body>
</html>
