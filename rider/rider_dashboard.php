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
    $update_delivery = $conn->prepare("UPDATE `orders` SET status = 'delivered' WHERE id = ?");
    $update_delivery->execute([$order_id]);
    $message[] = 'Order marked as delivered!';
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
    $delete_order->execute([$delete_id]);
    header('location:placed_orders.php');
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
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .update-btn, .delete-btn {
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            color: #fff;
        }
        .update-btn {
            background-color: #4CAF50; /* Green */
        }
        .update-btn:hover {
            background-color: #45a049;
        }
        .delete-btn {
            background-color: #f44336; /* Red */
        }
        .delete-btn:hover {
            background-color: #e53935;
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

<section class="orders">

    <h1 class="heading">Placed Orders</h1>

    <h2>Pending Orders</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Placed On</th>
                    <th>Name</th>
                    <th>Number</th>
                    <th>Address</th>
                    <th>Total Products</th>
                    <th>Total Price</th>
                    <th>Receipt</th>
                    <th>Payment Method</th>
                    <th>Delivered</th>
                    <th>Actions</th>
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
                <tr>
                    <td><?= $fetch_orders['placed_on']; ?></td>
                    <td><?= $fetch_orders['name']; ?></td>
                    <td><?= $fetch_orders['mobile']; ?></td>
                    <td><?= $fetch_orders['address']; ?></td>
                    <td><?= $fetch_orders['product_quantities']; ?></td>
                    <td>&#8369;<?= $fetch_orders['total_price']; ?>/-</td>
                    <td>
                        <?php if (!empty($fetch_orders['receipt_image'])): ?>
                            <a href="../uploads/<?= $fetch_orders['receipt_image']; ?>" target="_blank">
                                <img src="../uploads/<?= $fetch_orders['receipt_image']; ?>" alt="Receipt Image" class="receipt-image">
                            </a>
                        <?php else: ?>
                            No Receipt
                        <?php endif; ?>
                    </td>
                    <td><?= $fetch_orders['method']; ?></td>
                    <td>
                        <form action="" method="post" style="display: inline;">
                            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                            <input type="checkbox" name="delivery_status" onchange="this.form.submit()" <?= $fetch_orders['status'] == 'delivered' ? 'checked' : ''; ?>>
                            <input type="hidden" name="update_delivery">
                        </form>
                    </td>
                    <td>
                        <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Delete this order?');">Delete</a>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="10" style="text-align: center; padding: 8px;">No pending orders!</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    
    </div>

</section>

<script src="../js/admin_script.js"></script>
</body>
</html>
