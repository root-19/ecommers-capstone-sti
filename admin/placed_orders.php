<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}



// Payment Confirmation
if (isset($_GET['confirm_payment'])) {
    $confirm_id = $_GET['confirm_payment'];
    $confirm_payment = $conn->prepare("UPDATE `orders` SET payment_status = 'completed' WHERE id = ?");
    $confirm_payment->execute([$confirm_id]);
    
    // Check if the update was successful
    if ($confirm_payment->rowCount() > 0) {
        echo "<script>alert('Payment status marked as completed!');</script>";
    } else {
        echo "<script>alert('Failed to confirm payment.');</script>";
    }

    header('location:placed_orders.php');
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
    $delete_order->execute([$delete_id]);
    header('location:placed_orders.php');
    exit();
}

if (isset($_POST['update_trucking'])) {
    $order_id = $_POST['order_id'];
    $tracking_status = filter_var($_POST['tracking_status'], FILTER_SANITIZE_STRING);

    $update_tracking = $conn->prepare("UPDATE `orders` SET tracking_status = ? WHERE id = ?");
    $update_tracking->execute([$tracking_status, $order_id]);

    $message[] = 'Tracking status updated!';
}

if (isset($_POST['update_payment_segundary'])) {
    $order_id = $_POST['order_id'];
    $tracking_status = filter_var($_POST['payment_segundary'], FILTER_SANITIZE_STRING);

    $update_tracking = $conn->prepare("UPDATE `orders` SET payment_segundary = ? WHERE id = ?");
    $update_tracking->execute([$tracking_status, $order_id]);

    $message[] = 'Payment status updated!';
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placed Orders</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                <th>ID</th>
                <th>Placed On</th>
                <th>Name</th>
                <th>Number</th>
                <th>Address</th>
                <th>Total Products</th>
                <th>Total Price</th>
                <th>Receipt</th>
                <th>Payment Method</th>
                <th>Payment Status</th>
                <th>Tracking Status</th>
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
                    <td><?=$fetch_orders['id'];?></td>
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
                    <form action="" method="post" id="paymentForm_<?= $fetch_orders['id']; ?>">
    <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
    <select name="payment_segundary" id="payment_segundary_<?= $fetch_orders['id']; ?>">
        <option selected disabled><?= $fetch_orders['payment_segundary']; ?></option>
        <option value="pending">Pending</option>
        <option value="completed">Completed</option>
    </select>
    <input type="submit" value="Update" class="update-btn" name="update_payment_segundary" onclick="confirmUpdate(event, <?= $fetch_orders['id']; ?>)">
</form>

                    </td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                            <select name="tracking_status">
                                <option selected disabled><?= $fetch_orders['tracking_status']; ?></option>
                                <option value="packing">Packing</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                            </select>
                            <input type="submit" value="Update" class="update-btn" name="update_trucking">
                        </form>
                    </td>
                    <td>
    <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Delete this order?');">Delete</a>
    
    <!-- Confirm Payment Button -->
    <a href="placed_orders.php?confirm_payment=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirmPayment(<?= $fetch_orders['id']; ?>);">Approve</a>
</td>

<script>
function confirmPayment(orderId) {
    // SweetAlert confirmation prompt
    Swal.fire({
        title: 'Are you sure?',
        text: "You are about to confirm this payment as final.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, confirm it!',
        cancelButtonText: 'No, cancel!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to the confirmation URL
            window.location.href = `placed_orders.php?confirm_payment=${orderId}`;
        }
    });
    return false; // Prevent default link behavior
}
</script>
<style> 
    .confirm-btn {
    background-color: #2196F3; /* Blue color for confirmation */
    color: #fff;
    padding: 5px 10px;
    border-radius: 4px;
    text-decoration: none;
}
.confirm-btn:hover {
    background-color: #1976D2;
}

</style>
                </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="12" style="text-align: center; padding: 8px;">No pending orders!</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</section>




    <h2>Completed Orders</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr> <th>ID</th>
                    <th>Placed On</th>
                    <th>Name</th>
                    <th>Number</th>
                    <th>Address</th>
                    <th>Total Products</th>
                    <th>Total Price</th>
                    <th>Receipt</th>
                    <th>Payment Method</th>
                    <th>Payment Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $select_completed_orders = $conn->prepare("
                    SELECT orders.*, users.name, users.mobile, users.address 
                    FROM `orders` 
                    JOIN `users` ON orders.user_id = users.id
                    WHERE orders.payment_status = 'completed'
                ");
                $select_completed_orders->execute();
                if ($select_completed_orders->rowCount() > 0) {
                    while ($fetch_orders = $select_completed_orders->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <th><?= $fetch_orders['id'];?></th>
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
                            <select name="payment_status" style="padding: 5px; border-radius: 4px;">
                                <option selected disabled><?= $fetch_orders['payment_status']; ?></option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                            <input type="submit" value="Update" class="update-btn" name="update_payment">
                        </form>
                    </td>
                    <td>
                        <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Delete this order?');">Delete</a>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="10" style="text-align: center; padding: 8px;">No completed orders!</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

</section>

<script src="../js/admin_script.js"></script>
</body>
</html>
