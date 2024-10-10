<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
} else {
    $user_id = '';
}

// Process refund with additional details
if (isset($_POST['submit_refund'])) {
    $order_id = $_POST['refund_order_id'];
    $concern = $_POST['concern'];
    $gcash_number = $_POST['gcash_number'];
    $address = $_POST['address'];
    
    // Handle image upload
    $image = $_FILES['image'];
    $imagePath = null;

    if ($image['error'] === UPLOAD_ERR_OK) {
        // Define the target directory
        $targetDirectory = "uploads/"; // Ensure this directory exists and is writable
        $imageName = uniqid() . '-' . basename($image['name']);
        $imagePath = $targetDirectory . $imageName;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
            $message[] = 'Failed to upload the image.';
        }
    }

 

    // Insert refund request data into the refund table
    $insert_refund = $conn->prepare("INSERT INTO `refunds` (order_id, concern, gcash_number, address, image_path, user_id) VALUES (?, ?, ?, ?, ?, ?)");
    $insert_refund->execute([$order_id, $concern, $gcash_number, $address, $imagePath, $user_id]); // Ensure $user_id is defined


    $refund_id = $conn->lastInsertId();

   
   

    $message[] = 'Refund request submitted successfully for Order ID: ' . $order_id;
}


// var_dump($_POST);

// Update delivery status
if (isset($_POST['update_delivery'])) {
    $order_id = $_POST['order_id'];

    // Update order with delivery status
    $update_delivery = $conn->prepare("UPDATE `orders` SET status = 'delivered', tracking_status = 'Delivered' WHERE id = ? AND user_id = ?");
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
                    <th class="border px-4 py-2">Product Names</th>
                    <th class="border px-4 py-2">Total Products</th>
                    <th class="border px-4 py-2">Total Price</th>
                    <th class="border px-4 py-2">Payment Method</th>
                    <th class="border px-4 py-2">Order Status</th>
                    <th class="border px-4 py-2">Tracking Status</th>
                    <th class="border px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query only orders of the logged-in user
                $select_pending_orders = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = 'pending' AND user_id = ?");
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
                    <td class="border px-4 py-2"><?= $fetch_orders['product_names']; ?></td> 
                    <td class="border px-4 py-2"><?= $fetch_orders['product_quantities']; ?></td>
                    <td class="border px-4 py-2">&#8369;<?= $fetch_orders['total_price']; ?>/-</td>
                    <td class="border px-4 py-2"><?= $fetch_orders['method']; ?></td>
                    <td class="border px-4 py-2"><?= ucfirst($fetch_orders['status']); ?></td>
                    <td class="border px-4 py-2"><?= ucfirst($fetch_orders['tracking_status']); ?></td>
                    <td class="border px-4 py-2">
    <form action="" method="post" class="flex flex-col items-center">
        <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
        <?php if ($fetch_orders['status'] != 'delivered'): ?>
            <?php if ($order_age < 20): ?>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded" name="update_delivery">Mark as Delivered</button>
                <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded cancel-order" data-order-id="<?= $fetch_orders['id']; ?>">Cancel Order</button>
            <?php else: ?>
                <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded cancel-order" data-order-id="<?= $fetch_orders['id']; ?>">Cancel Order</button>
            <?php endif; ?>
        <?php else: ?>
            <div class="flex space-x-2">
    <button type="button" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded request-refund" 
            data-order-id="<?= htmlspecialchars($fetch_orders['id']); ?>">Return/Request Refund</button>
</div>

        <?php endif; ?>
    </form>
</td>

<!-- Refund Modal -->
<div id="refundModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h2 class="text-2xl font-semibold mb-4">Request Refund</h2>
        
        <form id="refundForm" method="post" enctype="multipart/form-data">
            <input type="hidden" name="refund_order_id" id="refundOrderId">

            <!-- Product Details Section -->
            <div id="productDetails"></div>

            <!-- Concern -->
            <div class="mb-4">
                <label for="concern" class="block mb-1 font-semibold">Refund Concern</label>
                <textarea id="concern" name="concern" class="w-full border border-gray-300 px-3 py-2 rounded" required></textarea>
            </div>

            <!-- Image Upload -->
            <div class="mb-4">
                <label for="image" class="block mb-1 font-semibold">Attach Image </label>
                <input type="file" id="image" name="image" accept="image/*" class="border border-gray-300 rounded w-full py-2 px-3">
            </div>

            <!-- GCash Number -->
            <div class="mb-4">
                <label for="gcash_number" class="block mb-1 font-semibold">GCash Number</label>
                <input type="text" id="gcash_number" name="gcash_number" class="w-full border border-gray-300 px-3 py-2 rounded" required>
            </div>

            <!-- Address -->
            <div class="mb-4">
                <label for="address" class="block mb-1 font-semibold">Refund Address</label>
                <input type="text" id="address" name="address" class="w-full border border-gray-300 px-3 py-2 rounded" required>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" id="cancelRefund" class="px-4 py-2 bg-gray-300 text-gray-700 rounded">Cancel</button>
                <button type="submit" name="submit_refund" class="px-4 py-2 bg-blue-500 text-white rounded">Submit Refund</button>
            </div>
        </form>
    </div>
</div>


                </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="8" class="text-center p-4">No pending orders!</td></tr>';
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

    document.addEventListener('DOMContentLoaded', function () {
        // Get all buttons with the class 'request-refund'
        const refundButtons = document.querySelectorAll('.request-refund');

        // Add click event listener to each button
        refundButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Get the order ID from the data attribute
                const orderId = this.getAttribute('data-order-id');

                // Set the order ID in the hidden input field of the refund form
                document.getElementById('refundOrderId').value = orderId;

                // Show the refund modal
                document.getElementById('refundModal').classList.remove('hidden');
            });
        });

        // Handle cancel button click to hide modal
        document.getElementById('cancelRefund').addEventListener('click', function () {
            document.getElementById('refundModal').classList.add('hidden');
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
