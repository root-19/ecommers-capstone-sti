<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
} else {
    header('location:user_login.php');
    exit();
}

$message = [];
$invoice_html = '';

if (isset($_POST['order'])) {
    $method = $_POST['method'];
    $method = filter_var($method, FILTER_SANITIZE_STRING);
    $address = !empty($_POST['address']) ? 'Address: ' . $_POST['address'] : 'No address provided';
    $address = filter_var($address, FILTER_SANITIZE_STRING);
    $total_price = $_POST['total_price'];

    if ($method === 'cash on delivery') {
        $pending_orders_check = $conn->prepare("SELECT COUNT(*) AS pending_count FROM `orders` WHERE user_id = ? AND method = 'cash on delivery' AND payment_status = 'pending'");
        $pending_orders_check->execute([$user_id]);
        $pending_orders_count = $pending_orders_check->fetchColumn();

        if ($pending_orders_count >= 3) {
            $message[] = 'You have pending orders with Cash on Delivery. You can only place one new Cash on Delivery order until your existing pending orders are completed.';
        } else {
            $invoice_html = processOrder($conn, $user_id, $method, $address, $total_price);
        }
    } else {
        $invoice_html = processOrder($conn, $user_id, $method, $address, $total_price);
    }
}

function processOrder($conn, $user_id, $method, $address, $total_price) {
    $product_names = '';
    $product_quantities = '';
    $receipt_image = '';

    // Handle receipt image upload for GCash payments
    if ($method === 'gcash' && isset($_FILES['receipt_image']['name']) && !empty($_FILES['receipt_image']['name'])) {
        $receipt_image_name = $_FILES['receipt_image']['name'];
        $receipt_image_tmp_name = $_FILES['receipt_image']['tmp_name'];
        $receipt_image_size = $_FILES['receipt_image']['size'];
        $receipt_image_folder = 'uploads/' . $receipt_image_name;

        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
        $file_extension = pathinfo($receipt_image_name, PATHINFO_EXTENSION);
        $file_extension = strtolower($file_extension);

        if (in_array($file_extension, $allowed_extensions)) {
            if ($receipt_image_size <= 2000000) {
                move_uploaded_file($receipt_image_tmp_name, $receipt_image_folder);
                $receipt_image = $receipt_image_name;
            } else {
                $message[] = 'File size is too large';
                return '';
            }
        } else {
            $message[] = 'Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.';
            return '';
        }
    }

    // Retrieve cart items for the user
    $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $check_cart->execute([$user_id]);

    if ($check_cart->rowCount() > 0) {
        $cart_items = $check_cart->fetchAll(PDO::FETCH_ASSOC);
        $cart_names = [];
        $cart_quantities = [];
        foreach ($cart_items as $item) {
            $cart_names[] = $item['name'];
            $cart_quantities[] = $item['quantity'];
        }

        $product_names = implode(', ', $cart_names);
        $product_quantities = implode(', ', $cart_quantities);

        // Insert order details into the 'orders' table
        $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, method, address, product_names, product_quantities, total_price, receipt_image, payment_status) VALUES(?,?,?,?,?,?,?, 'pending')");
        $insert_order->execute([$user_id, $method, $address, $product_names, $product_quantities, $total_price, $receipt_image]);

        // Update product quantities
        foreach ($cart_items as $item) {
            $product_name = $item['name'];
            $cart_quantity = $item['quantity'];

            $update_product = $conn->prepare("UPDATE `products` SET quantity = quantity - ? WHERE name = ?");
            $update_product->execute([$cart_quantity, $product_name]);
        }

        // Delete items from the cart
        $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
        $delete_cart->execute([$user_id]);

        // Fetch the user's name from the 'users' table
        $get_user_name = $conn->prepare("SELECT name FROM `users` WHERE id = ?");
        $get_user_name->execute([$user_id]);
        $user = $get_user_name->fetch(PDO::FETCH_ASSOC);
        $user_name = $user['name'];

        // Generate the invoice with the user's name
        $message[] = 'Order placed successfully!';
        return generateInvoice($user_name, $product_names, $product_quantities, $total_price, $method);
    } else {
        $message[] = 'Your cart is empty!';
        return '';
    }
}

function generateInvoice($user_name, $product_names, $product_quantities, $total_price, $method) {
    $invoice_number = round(200010 + rand(1, 1000));
    return "
    <div id='invoice' style='text-align: center; background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 8px; max-width: 600px; margin: auto;'>
        <!-- Company Logo -->
        <img src='./uploaded_img/ecommer.jpg' alt='Company Logo' style='width: 150px; height: auto; margin-bottom: 20px;'>
        <h1 style='font-size: 28px; color: #333;'>HP Performance Exhaust</h1>
        <p style='font-size: 14px; color: #555;'>Phone: +123456789 | Email: support@yourcompany.com</p>
        
        
        <h3 style='font-size: 20px; color: #333;'>Order Details:</h3>
        <table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
            <thead>
                <tr style='background-color: #e0e0e0;'>
                    <th style='border: 1px solid #ddd; padding: 10px; text-align: left;'>Name</th>
                    <th style='border: 1px solid #ddd; padding: 10px; text-align: left;'>Product</th>
                    <th style='border: 1px solid #ddd; padding: 10px; text-align: left;'>Payment Method</th>
                    <th style='border: 1px solid #ddd; padding: 10px; text-align: left;'>Quantity</th>
                    <th style='border: 1px solid #ddd; padding: 10px; text-align: left;'>Invoice</th>



                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style='border: 1px solid #ddd; padding: 10px;'>$user_name</td>
                    <td style='border: 1px solid #ddd; padding: 10px;'>$product_names</td>
                    <td style='border: 1px solid #ddd; padding: 10px;'>$method</td>
                    <td style='border: 1px solid #ddd; padding: 10px;'>$product_quantities</td>
                     <td style='border: 1px solid #ddd; padding: 10px;'>#$invoice_number</td>
                </tr>
   
            </tbody>
        </table>
        
        <h3 style='font-size: 20px; color: #333; margin-top: 20px;'>Total Price: &#8369;$total_price</h3>
    </div>
    ";
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        document.addEventListener('DOMContentLoaded', function () {
    <?php if (!empty($invoice_html)) { ?>
        Swal.fire({
            title: 'Order Success',
            html: `<?= $invoice_html ?>`,
            showCloseButton: true,
            confirmButtonText: 'Download Invoice',
        }).then((result) => {
            if (result.isConfirmed) {
                var element = document.getElementById('invoice');
                var opt = {
                    margin: 1,
                    filename: 'invoice.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                };
                html2pdf().from(element).set(opt).save();
            }
        });
    <?php } ?>
});

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
            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);
            if ($select_cart->rowCount() > 0) {
                while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                    $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
                    echo '<p>' . $fetch_cart['name'] . ' <span>(&#8369;' . $fetch_cart['price'] . '/- x ' . $fetch_cart['quantity'] . ')</span></p>';
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
            <!-- Button to show address input -->
            <button type="button" id="show-address-btn" class="box">Add Address</button>
        </div>

        <!-- Address input (hidden by default) -->
        <div class="inputBox" id="address-input-box" style="display: none;">
            <input type="text" name="address" placeholder="Enter your address" class="box" maxlength="100">
        </div>
        </div>

        <div id="qr-code" class="qr-code" style="display: none;">
            <p>Scan the QR code to pay with Gcash:</p>
            <img src="./uploaded_img/gcash.jpg" alt="Gcash QR Code">
            <input type="file" name="receipt_image" class="box">
        </div>

        <input type="submit" value="Place Order" name="order" class="btn">
    </form>

</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>

<script>
  
    const showAddressBtn = document.getElementById('show-address-btn');
    const addressInputBox = document.getElementById('address-input-box');

 
    showAddressBtn.addEventListener('click', function() {
        if (addressInputBox.style.display === 'none') {
            addressInputBox.style.display = 'block';
            showAddressBtn.innerText = 'Hide Address';  
        } else {
            addressInputBox.style.display = 'none'; 
            showAddressBtn.innerText = 'Add Address'; 
        }
    });
</script>
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
        #invoice {
    width: 100%;
    margin: 70px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border: 2px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

#invoice h1 {
    text-align: center;
    font-size: 36px;
    color: #333;
    margin-bottom: 20px;
}

#invoice p {
    font-size: 18px;
    color: #555;
    line-height: 1.5;
    margin: 10px 0;
}

#invoice h2 {
    margin-top: 20px;
    font-size: 28px;
    color: #333;
}

#invoice h3 {
    font-size: 24px;
    color: #d9534f;
    margin-top: 20px;
    text-align: right;
}

#invoice p:last-child {
    margin-bottom: 0;
}


    </style>