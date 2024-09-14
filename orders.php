<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
}

// Update delivery status only
if (isset($_POST['update_delivery'])) {
   $order_id = $_POST['order_id'];

   // Update order with delivery status (no image handling)
   $update_delivery = $conn->prepare("UPDATE `orders` SET status = 'delivered' WHERE id = ? AND user_id = ?");
   $update_delivery->execute([$order_id, $user_id]);
   $message[] = 'Order marked as delivered!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <script src="https://cdn.tailwindcss.com"></script>

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="orders p-4">

    <h2 class="text-2xl font-semibold mb-4">Your Pending Orders</h2>
    <div class="table-container overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
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
                    <td class="border px-4 py-2"><?= $fetch_orders['placed_on']; ?></td>
                    <td class="border px-4 py-2"><?= $fetch_orders['product_quantities']; ?></td>
                    <td class="border px-4 py-2">&#8369;<?= $fetch_orders['total_price']; ?>/-</td>
                    <td class="border px-4 py-2"><?= $fetch_orders['method']; ?></td>
                    <td class="border px-4 py-2">
                        <form action="" method="post" class="flex flex-col items-center">
                            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                            <?php if ($order_age < 20 && $fetch_orders['status'] != 'delivered'): ?>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded" name="update_delivery">Mark as Delivered</button>
                            <?php else: ?>
                                <button class="bg-gray-500 text-white px-4 py-2 rounded" disabled>Delivered</button>
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

</body>
</html>
