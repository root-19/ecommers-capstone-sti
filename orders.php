<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
} else {
    $user_id = '';
}

if (isset($_POST['submit_refund'])) {
    // Get form data
    $order_id = $_POST['refund_order_id'];
    $concern = $_POST['concern'];
    $gcash_number = $_POST['gcash_number'];
    $address = $_POST['address'];
    $product_name = $_POST['product_name'];
    $product_quantity = $_POST['product_quantity'];
    $total_price = $_POST['total_price'];

   // Initialize variables for image upload
$image = $_FILES['image'];
$imagePath = null;

// Allowed image MIME types
$allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif'];

// Handle image upload if a file was uploaded
if ($image['error'] === UPLOAD_ERR_OK) {

    $fileMimeType = mime_content_type($image['tmp_name']);

    // Check if the uploaded file is an image
    if (in_array($fileMimeType, $allowedImageTypes)) {
        // Define the target directory for uploads
        $targetDirectory = "uploads/"; // Make sure this directory exists and is writable
        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0777, true); // Create the directory if it doesn't exist
        }

        // Generate a unique name for the image to prevent overwriting
        $imageName = uniqid() . '-' . basename($image['name']);
        $imagePath = $targetDirectory . $imageName;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
            $message[] = 'Failed to upload the image.';
        } else {
            // Success message if image upload succeeds
            $message[] = 'Image uploaded successfully.';
        }
    } else {
        // If the file is not an image, display an error
        $message[] = 'Only image files (JPG, PNG, GIF) are allowed.';
    }
} else {
    // If no image is uploaded or there is an error, you can add a fallback
    $message[] = 'No image uploaded or an error occurred during the upload.';
}


    // Fetch product details from the orders table
    $select_order = $conn->prepare("SELECT product_names, product_quantities, total_price, method FROM `orders` WHERE id = ? AND user_id = ?");
    $select_order->execute([$order_id, $user_id]);

    if ($select_order->rowCount() > 0) {
        // Insert refund request into the refunds table
        $insert_refund = $conn->prepare("
            INSERT INTO `refunds` (order_id, concern, gcash_number, address, image_path, user_id, product_name, product_quantity, total_price, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        // Execute the query with form and order data
        $insert_refund->execute([$order_id, $concern, $gcash_number, $address, $imagePath, $user_id, $product_name, $product_quantity, $total_price]);

        // Success message
        $message[] = 'Refund request submitted successfully for Order ID: ' . $order_id;
    } else {
        // If the order does not exist or belongs to a different user
        $message[] = 'Order not found or you are not authorized to request a refund for this order.';
    }
}

// Output any messages
if (!empty($message)) {
    foreach ($message as $msg) {
        echo "<div class='alert alert-info'>{$msg}</div>";
    }
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

$select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
$select_orders->execute([$user_id]);
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
                    <th class="border px-4 py-2">Tracking Status</th>
                    <th class="border px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch user's address details
                $sql = "SELECT address, house_number, street, subdivision, pin_point FROM users WHERE id = :user_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();
                $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

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
                    <td class="border px-4 py-4"><?= $fetch_orders['id']; ?></td>
                    <td class="border px-4 py-2"><?= $fetch_orders['placed_on']; ?></td>
                    <td class="border px-4 py-2"><?= $fetch_orders['product_names']; ?></td> 
                    <td class="border px-4 py-2"><?= $fetch_orders['product_quantities']; ?></td>
                    <td class="border px-4 py-2">&#8369;<?= $fetch_orders['total_price']; ?>/-</td>
                    <td class="border px-4 py-2"><?= $fetch_orders['method']; ?></td>
                    <td class="border px-4 py-2"><?= ucfirst($fetch_orders['tracking_status']); ?></td>
                    <td class="border px-4 py-2">
                   
   
                    <td class="border px-4 py-2">
    <!-- View Button -->
    <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded view-buttons" data-order-id="<?= $fetch_orders['id']; ?>">View</button>

    <!-- Modal for Order Details and Actions -->
    <div id="modal-<?= $fetch_orders['id']; ?>" class="fixed inset-0 hidden bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-xl">
            <!-- Close button -->
            <button type="button" class="close-modal text-gray-500 hover:text-gray-800 text-lg float-right">&times;</button>
            
            <!-- Order Details -->
            <h2 class="text-lg font-bold mb-4">Order Details</h2>
            <p><strong>Order ID:</strong> <?= $fetch_orders['id']; ?></p>
            <p><strong>Placed On:</strong> <?= $fetch_orders['placed_on']; ?></p>
            <p><strong>Product Names:</strong> <?= $fetch_orders['product_names']; ?></p>
            <p><strong>Total Products:</strong> <?= $fetch_orders['product_quantities']; ?></p>
            <p><strong>Total Price:</strong> &#8369;<?= $fetch_orders['total_price']; ?></p>
            <p><strong>Payment Method:</strong> <?= $fetch_orders['method']; ?></p>
            <p><strong>Tracking Status:</strong> <?= ucfirst($fetch_orders['tracking_status']); ?></p>
            
            <!-- Address Details -->
            <div class="mb-4">
                <label for="address" class="block font-semibold">Address:</label>
                <p><?= htmlspecialchars($user_data['address']); ?></p>
            </div>

            <!-- Action Buttons -->
            <?php if ($order_age < 20): ?>
                <div class="mt-6">
                    <?php if ($fetch_orders['tracking_status'] === 'packing'): ?>
                        <!-- Show Cancel Order button only if status is Packing -->
                        <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded cancel-order" data-order-id="<?= $fetch_orders['id']; ?>">Cancel Order</button>
                    <?php elseif ($fetch_orders['tracking_status'] === 'delivered'): ?>
                        <button type="button" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded delivered-btn" 
                            onclick="markAsDelivered(this)">Delivered</button>
                        <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded request-refund"
                            data-order-id="<?= htmlspecialchars($fetch_orders['id']); ?>"
                            data-product-name="<?= htmlspecialchars($fetch_orders['product_names']); ?>"
                            data-product-quantity="<?= htmlspecialchars($fetch_orders['product_quantities']); ?>"
                            data-total-price="<?= htmlspecialchars($fetch_orders['total_price']); ?>">
                            Return/Request Refund
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>              
        <script>
    function markAsDelivered(button) {
        button.innerText = "Already Delivered";
        button.disabled = true;
        button.classList.remove("bg-green-500", "hover:bg-green-600");
        button.classList.add("bg-gray-500", "text-gray-300");
    }
</script>        </div>
                    </td>
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
<!-- Refund Modal -->
<div id="refundModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h2 class="text-2xl font-semibold mb-4">Request Refund</h2>
        
        <form id="refundForm" method="post" enctype="multipart/form-data">
            <input type="hidden" name="refund_order_id" id="refundOrderId">

            <!-- Product Details Section -->
            <div id="productDetails"></div>
             <!-- Hidden inputs for product details -->
             <input type="hidden" name="product_name" id="refundProductName">
            <input type="hidden" name="product_quantity" id="refundProductQuantity">
            <input type="hidden" name="total_price" id="refundTotalPrice">

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
             
            </tbody>
        </table>

    </div>
</section>
<section class="orders p-4">
    <h2 class="text-2xl font-semibold mb-4">Your Order History</h2>
    <div class="table-container overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Order ID</th>
                    <th class="border px-4 py-2">Placed On</th>
                    <th class="border px-4 py-2">Product Names</th>
                    <th class="border px-4 py-2">Total Products</th>
                    <th class="border px-4 py-2">Total Price</th>
                    <th class="border px-4 py-2">Payment Method</th>
                    <th class="border px-4 py-2">Payment Status</th>
                    <th class="border px-4 py-2">Tracking Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($select_orders->rowCount() > 0) {
                    while ($order = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr>
                            <td class="border px-4 py-2"><?= $order['id']; ?></td>
                            <td class="border px-4 py-2"><?= $order['placed_on']; ?></td>
                            <td class="border px-4 py-2"><?= $order['product_names']; ?></td>
                            <td class="border px-4 py-2"><?= $order['product_quantities']; ?></td>
                            <td class="border px-4 py-2">&#8369;<?= $order['total_price']; ?>/-</td>
                            <td class="border px-4 py-2"><?= $order['method']; ?></td>
                            <td class="border px-4 py-2"><?= ucfirst($order['payment_status']); ?></td>
                            <td class="border px-4 py-2"><?= ucfirst($order['tracking_status']); ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo '<tr><td colspan="8" class="text-center border px-4 py-2">No orders found!</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</section>


<?php include 'components/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   $(document).ready(function(){
        // When the "View" button is clicked, show the modal
        $('.view-buttons').click(function(){
            var orderId = $(this).data('order-id');
            $('#modal-' + orderId).removeClass('hidden');
        });

        // Close the modal when the close button is clicked or anywhere outside the modal content
        $('.close-modal').click(function(){
            $(this).closest('.fixed').addClass('hidden');
        });

        $(window).click(function(event) {
            if ($(event.target).hasClass('fixed')) {
                $(event.target).addClass('hidden');
            }
        });
    });
</script>
<script src="js/script.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const viewButtons = document.querySelectorAll(".view-buttons");
    const modals = document.querySelectorAll("[id^='modal-']");
    const closeModalButtons = document.querySelectorAll(".close-modal");
    const refundModal = document.getElementById("refundModal");

    // Open the modal for viewing order details
    viewButtons.forEach(button => {
        button.addEventListener("click", () => {
            const orderId = button.getAttribute("data-order-id");
            const modal = document.getElementById(`modal-${orderId}`);
            modal.classList.remove("hidden");
        });
    });

    // Close modal functionality
    closeModalButtons.forEach(button => {
        button.addEventListener("click", () => {
            button.closest(".fixed").classList.add("hidden");
        });
    });

    // Show refund modal on "Request Refund" button click
    const refundButtons = document.querySelectorAll(".request-refund");
    refundButtons.forEach(button => {
        button.addEventListener("click", () => {
            refundModal.classList.remove("hidden");
            document.getElementById("refundOrderId").value = button.dataset.orderId;
        });
    });

    // Hide refund modal on cancel
    document.getElementById("cancelRefund").addEventListener("click", () => {
        refundModal.classList.add("hidden");
    });
});


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

    document.querySelectorAll('.request-refund').forEach(button => {
    button.addEventListener('click', function() {
        const orderId = this.getAttribute('data-order-id');
        const productName = this.getAttribute('data-product-name');
        const productQuantity = this.getAttribute('data-product-quantity');
        const totalPrice = this.getAttribute('data-total-price');

        // Set hidden input values (these will be submitted with the form)
        document.getElementById('refundOrderId').value = orderId;
        document.getElementById('refundProductName').value = productName;
        document.getElementById('refundProductQuantity').value = productQuantity;
        document.getElementById('refundTotalPrice').value = totalPrice;

        // Populate product details dynamically for display in the modal
        document.getElementById('productDetails').innerHTML = `
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Product Name</label>
                <input type="text" class="w-full border border-gray-300 px-3 py-2 rounded" value="${productName}" readonly>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Product Quantity</label>
                <input type="text" class="w-full border border-gray-300 px-3 py-2 rounded" value="${productQuantity}" readonly>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Total Price</label>
                <input type="text" class="w-full border border-gray-300 px-3 py-2 rounded" value="${totalPrice}" readonly>
            </div>
        `;

        // Show the modal
        document.getElementById('refundModal').classList.remove('hidden');
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