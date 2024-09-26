<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
} else {
    $user_id = '';
}

// Update delivery status only
if (isset($_POST['update_delivery'])) {
    $order_id = $_POST['order_id'];

    // Update order with delivery status
    $update_delivery = $conn->prepare("UPDATE `orders` SET status = 'delivered' WHERE id = ? AND user_id = ?");
    $update_delivery->execute([$order_id, $user_id]);
    $message[] = 'Order marked as delivered!';
}

// Cancel order
if (isset($_POST['cancel_order'])) {
    $order_id = $_POST['order_id'];
    
    // Move order to canceled_orders table
    $move_to_canceled = $conn->prepare("INSERT INTO `canceled_orders` (product_quantities, total_price, method, user_id, placed_on) 
        SELECT product_quantities, total_price, method, user_id, placed_on FROM `orders` WHERE id = ?");
    $move_to_canceled->execute([$order_id]);

    // Delete the order from the orders table
    $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
    $delete_order->execute([$order_id]);

    $message[] = 'Order has been canceled!';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="orders p-4">

    <h2 class="text-2xl font-semibold mb-4">Your Pending Orders</h2>
    <div class="table-container overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Placed On</th>
                    <th class="border px-4 py-2">Total Products</th>
                    <th class="border px-4 py-2">Total Price</th>
                    <th class="border px-4 py-2">Payment Method</th>
                    <th class="border px-4 py-2">Order Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query only orders of the logged-in user
                $select_pending_orders = $conn->prepare("
                    SELECT * FROM `orders` 
                    WHERE payment_status = 'pending' AND user_id = ?
                ");
                $select_pending_orders->execute([$user_id]);

                if ($select_pending_orders->rowCount() > 0) {
                    while ($fetch_orders = $select_pending_orders->fetch(PDO::FETCH_ASSOC)) {
                        // Calculate the order age
                        $placed_on = new DateTime($fetch_orders['placed_on']);
                        $today = new DateTime();
                        $order_age = $today->diff($placed_on)->days;
                ?>
                <tr class="hover:bg-gray-100">
                    <td class="border px-4 py-4"><?= $fetch_orders['id'];?></td>
                    <td class="border px-4 py-2"><?= $fetch_orders['placed_on']; ?></td>
                    <td class="border px-4 py-2"><?= $fetch_orders['product_quantities']; ?></td>
                    <td class="border px-4 py-2">&#8369;<?= $fetch_orders['total_price']; ?>/-</td>
                    <td class="border px-4 py-2"><?= $fetch_orders['method']; ?></td>
                    <td class="border px-4 py-2">
                        <form action="" method="post" class="flex flex-col items-center">
                            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                            <?php if ($order_age < 20 && $fetch_orders['status'] != 'delivered'): ?>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded" name="update_delivery">Mark as Delivered</button>
                                <!-- <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded mt-2 cancel-order" data-order-id="<?= $fetch_orders['id']; ?>">Cancel Order</button> -->
                            <?php else: ?>
                                <div class="flex space-x-2"> 
                                <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded mt-2 cancel-order" data-order-id="<?= $fetch_orders['id']; ?>">Cancel Order</button>
                                <button class="bg-gray-500 text-white px-4 py-2 rounded" disabled>Delivered</button>
                                </div>
                                <?php endif; ?>
                        </form>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="5" class="text-center p-4">No pending orders!</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
<script>
    // SweetAlert for cancel confirmation
    document.querySelectorAll('.cancel-order').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to cancel this order?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, cancel it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a form to submit cancellation
                    const form = document.createElement('form');
                    form.method = 'post';
                    form.action = '';

                    const hiddenField = document.createElement('input');
                    hiddenField.type = 'hidden';
                    hiddenField.name = 'order_id';
                    hiddenField.value = orderId;

                    const cancelField = document.createElement('input');
                    cancelField.type = 'hidden';
                    cancelField.name = 'cancel_order';
                    cancelField.value = '1';

                    form.appendChild(hiddenField);
                    form.appendChild(cancelField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>
<style> 

.flex-col {
    display: flex;
    flex-direction: column;
    align-items: center; 
}

.mb-2 {
    margin-bottom: 0.5rem;
}

</style>
</body>
</html>
