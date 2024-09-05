<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:user_login.php');
    exit();
}

if (isset($_POST['order'])) {
    $method = $_POST['method'];
    $method = filter_var($method, FILTER_SANITIZE_STRING);
    $address = !empty($_POST['address']) ? 'Address: ' . $_POST['address'] : 'No address provided';
    $address = filter_var($address, FILTER_SANITIZE_STRING);
    $total_price = $_POST['total_price'];

    $product_names = '';
    $product_quantities = '';

    $receipt_image = '';

    // Handle GCash receipt image
    if ($method === 'gcash' && isset($_FILES['receipt_image']['name']) && !empty($_FILES['receipt_image']['name'])) {
        $receipt_image_name = $_FILES['receipt_image']['name'];
        $receipt_image_tmp_name = $_FILES['receipt_image']['tmp_name'];
        $receipt_image_size = $_FILES['receipt_image']['size'];
        $receipt_image_folder = 'uploads/' . $receipt_image_name;

        // Validate image file type and size
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
        $file_extension = pathinfo($receipt_image_name, PATHINFO_EXTENSION);
        $file_extension = strtolower($file_extension);

        if (in_array($file_extension, $allowed_extensions)) {
            if ($receipt_image_size <= 2000000) { // 2MB limit
                move_uploaded_file($receipt_image_tmp_name, $receipt_image_folder);
                $receipt_image = $receipt_image_name;
            } else {
                $message[] = 'File size is too large';
            }
        } else {
            $message[] = 'Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.';
        }
    }

    // Check if the user's cart has items
    $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $check_cart->execute([$user_id]);

    if ($check_cart->rowCount() > 0) {

        // Fetch cart items
        $cart_items = $check_cart->fetchAll(PDO::FETCH_ASSOC);

        if (!$cart_items) {
            die('No cart items found or failed to fetch cart data.');
        }

        // Debugging: Print cart items structure to inspect
        // echo "<pre>";
        // print_r($cart_items);
        // echo "</pre>";
        // exit(); // Stop execution to inspect cart items

        // Prepare product names and quantities
        $cart_names = [];
        $cart_quantities = [];
        foreach ($cart_items as $item) {
            $cart_names[] = $item['name'];
            $cart_quantities[] = $item['quantity'];
        }

        // Combine names and quantities as strings
        $product_names = implode(', ', $cart_names);
        $product_quantities = implode(', ', $cart_quantities);

        // Insert the order into the orders table
        $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, method, address, product_names, product_quantities, total_price, receipt_image) VALUES(?,?,?,?,?,?,?)");
        $insert_order->execute([$user_id, $method, $address, $product_names, $product_quantities, $total_price, $receipt_image]);

        // Update the product quantities in the products table
        foreach ($cart_items as $item) {
            $product_name = $item['name'];
            $cart_quantity = $item['quantity'];

            // Deduct the purchased quantity from the available product quantity
            $update_product = $conn->prepare("UPDATE `products` SET quantity = quantity - ? WHERE name = ?");
            $update_product->execute([$cart_quantity, $product_name]);
        }

        // Clear the cart after order placement
        $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
        $delete_cart->execute([$user_id]);

        $message[] = 'Order placed successfully!';
    } else {
        $message[] = 'Your cart is empty!';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <!-- Font awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        .qr-code {
            display: none;
            text-align: center;
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .qr-code img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .qr-code p {
            font-size: 16px;
            color: #333;
            margin-bottom: 15px;
        }

        .qr-code input[type="file"] {
            display: block;
            margin-top: 10px;
            margin-left: auto;
            margin-right: auto;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
        }

        .qr-code input[type="file"]:hover {
            background-color: #f1f1f1;
        }
    </style>

    <script>
        function toggleQRCode() {
            var method = document.getElementById('payment-method').value;
            var qrCodeDiv = document.getElementById('qr-code');
            if (method === 'gcash') {
                qrCodeDiv.style.display = 'block';
            } else {
                qrCodeDiv.style.display = 'none';
            }
        }
    </script>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="checkout-orders">

    <form action="" method="POST" enctype="multipart/form-data">

        <h3>Your Orders</h3>

        <div class="display-orders">
            <?php
            $grand_total = 0;
            $cart_items = [];
            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);
            if ($select_cart->rowCount() > 0) {
                while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                    $cart_items[] = $fetch_cart['name'] . ' (' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ')';
                    $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
            ?>
                <p> <?= $fetch_cart['name']; ?> <span>(<?= '&#8369;' . $fetch_cart['price'] . '/- x ' . $fetch_cart['quantity']; ?>)</span> </p>
            <?php
                }
            } else {
                echo '<p class="empty">Your cart is empty!</p>';
            }
            ?>
            <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
            <div class="grand-total">Grand Total: <span>&#8369;<?= $grand_total; ?>/-</span></div>
        </div>

        <h3>Place Your Order</h3>

        <div class="flex">
            <div class="inputBox">
                <span>Payment Method:</span>
                <select name="method" id="payment-method" class="box" onchange="toggleQRCode()" required>
                    <option value="cash on delivery">Cash on Delivery</option>
                    <option value="gcash">Gcash</option>
                </select>
            </div>
            <div class="inputBox">
                <span>Address (optional):</span>
                <input type="text" name="address" placeholder="Enter your address" class="box" maxlength="100">
            </div>
        </div>

        <div id="qr-code" class="qr-code">
            <p>Scan the QR code to pay with Gcash:</p>
            <img src="./uploaded_img/gcash .jpg" alt="Gcash QR Code">
            <p>Upload your receipt:</p>
            <input type="file" name="receipt_image" accept="image/*" class="box">
        </div>

        <input type="submit" name="order" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>" value="Place Order">

    </form>

</section>

</body>
</html>
