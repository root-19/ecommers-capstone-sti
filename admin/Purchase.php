<?php
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_POST['order_purchase'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        $manufacturer = $_POST['manufacturer'][$product_id];

        // Fetch current product details
        $productQuery = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
        $productQuery->execute([$product_id]);
        $product = $productQuery->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $name = $product['name'];
            $type = $product['type'];
            $automobile_type = $product['automobile_type'];
            $category = $product['category'];
            $price = $product['price'];
            $purchase_date = date('Y-m-d');
            
            // Generate a unique code number for the purchase
            $code_number = str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);

            // Check if quantity is greater than zero and update the purchase table
            if ($quantity > 0) {
                // Insert into the purchase table
                $insert_purchase = $conn->prepare("INSERT INTO `purchase` 
                    (product_id, name, manufacturer, type, automobile_type, category, price, quantity, purchase_date, code_number) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insert_purchase->execute([
                    $product_id, $name, $manufacturer, $type, $automobile_type, $category, $price, $quantity, $purchase_date, $code_number
                ]);
            }
        }
    }
}

// Handle receiving orders and moving them to receive_order table
if (isset($_POST['receive_order'])) {
    $product_id = $_POST['product_id'];

    // Get the order details from the purchase table
    $purchaseQuery = $conn->prepare("SELECT * FROM `purchase` WHERE product_id = ?");
    $purchaseQuery->execute([$product_id]);
    $purchase = $purchaseQuery->fetch(PDO::FETCH_ASSOC);

    if ($purchase) {
        $code_number = $purchase['code_number'];

        // Insert into the receive_order table
        $insert_receive = $conn->prepare("INSERT INTO `receive_order` 
            (product_id, name, manufacturer, type, automobile_type, category, price, quantity, code_number, receive_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $receive_date = date('Y-m-d');
        $insert_receive->execute([
            $purchase['product_id'], 
            $purchase['name'], 
            $purchase['manufacturer'], 
            $purchase['type'], 
            $purchase['automobile_type'], 
            $purchase['category'], 
            $purchase['price'], 
            $purchase['quantity'], 
            $code_number, 
            $receive_date
        ]);

        if ($insert_receive) {
            // Update product quantity in the products table
            $update_product = $conn->prepare("UPDATE `products` SET quantity = quantity + ? WHERE id = ?");
            $update_product->execute([$purchase['quantity'], $product_id]);

            // Delete from the purchase table as it is now received
            $delete_purchase = $conn->prepare("DELETE FROM `purchase` WHERE product_id = ?");
            $delete_purchase->execute([$product_id]);

            $message[] = 'Order received and product quantity updated successfully!';
        } else {
            $message[] = 'Failed to receive order.';
        }
    } else {
        $message[] = 'Product not found in purchase table.';
    }
}

// Fetch purchase history
$purchases = $conn->query("SELECT * FROM `purchase`")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all products from the products table
$stmt = $conn->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch received orders
$receivedOrders = $conn->query("SELECT * FROM `receive_order` ORDER BY receive_date DESC")->fetchAll(PDO::FETCH_ASSOC);
?>


<?php include '../components/admin_header.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<br>

<!-- Button to open the Restock Pop-up -->
<div class="flex justify-start ml-4">
    <button class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 focus:ring-2 focus:ring-red-400 transition-all text-3xl" id="restockProductBtn">
        Order Product
    </button>
</div>

<!-- Product Modal -->
<form method="POST">
    
<div id="restockForm" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-7xl max-h-[90vh] overflow-y-auto">
        <button class="close text-gray-400 float-right text-xl font-semibold">&times;</button>
        <h2 class="text-xl font-bold mb-4 text-center">Restock Product</h2>
        

            <!-- Search Bar inside the modal -->
            <div class="mt-4 ml-4">
                <input type="text" id="searchInput" class="border border-gray-300 p-2 rounded w-full" placeholder="Search manufacturer...">
            </div>

            <div class="flex justify-between gap-4 mb-4">
    <!-- Left Side - Supplier Company Information -->
    <div class="flex-1 p-4 border border-gray-200 rounded-lg">
        <h3 class="font-bold text-lg mb-2">Supplier Information</h3>
        <p><strong>Company Name:</strong> Supplier Company Name</p>
        <p><strong>Email Address:</strong> supplier@example.com</p>
        <p><strong>Address:</strong> 123 Supplier St., City, Country</p>
    </div>
    
    <!-- Right Side - Your Company Information -->
    <div class="flex-1 p-4 border border-gray-200 rounded-lg">
        <h3 class="font-bold text-lg mb-2">Your Company Information</h3>
        <p><strong>Company Name:</strong> Your Company Name</p>
        <p><strong>Email Address:</strong> yourcompany@example.com</p>
        <p><strong>Address:</strong> 456 Company Ave., City, Country</p>
    </div>
</div>



            <!-- Product Table (Initially hidden) -->
            <div class="overflow-x-auto mb-4" style="max-height: 60vh; overflow-y: auto;">
                <table class="min-w-full border-collapse border border-gray-200" id="productTable" style="display: none;">
                    <thead>
                        <tr class="bg-gray-100 text-sm font-semibold text-gray-700">
                            <th class="p-2 border border-gray-200">Name</th>
                            <th class="p-2 border border-gray-200">Manufacturer</th>
                            <th class="p-2 border border-gray-200">Type</th>
                            <th class="p-2 border border-gray-200">Automobile Type</th>
                            <th class="p-2 border border-gray-200">Price</th>
                            <th class="p-2 border border-gray-200">Category</th>
                            <th class="p-2 border border-gray-200">Quantity</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                        <!-- Product rows will be dynamically inserted here after searching -->
                    </tbody>
                </table>
            </div>

            <!-- Order Purchase Button (Fixed to the bottom of the modal) -->
            <button type="submit" name="order_purchase" class="w-full mt-4 bg-red-500 text-white py-2 rounded hover:bg-red-600 transition-all">
                Order Purchase
            </button>
        </div>
    </div>
</form>

<script>
    // Open the modal when clicking on the 'Order Product' button
    document.getElementById('restockProductBtn').addEventListener('click', function() {
        document.getElementById('restockForm').classList.remove('hidden');
    });

    // Close the modal when clicking on the close button
    document.querySelector('.close').addEventListener('click', function() {
        document.getElementById('restockForm').classList.add('hidden');
    });

    // Search functionality for manufacturer inside the modal
    document.getElementById('searchInput').addEventListener('input', function() {
        let query = this.value;

        // Fetch products based on search query
        if (query.length > 0) {
            fetch(`search_product.php?query=${query}`)
                .then(response => response.text())
                .then(data => {
                    // Show the product table if results are found
                    if (data.trim() !== '') {
                        document.getElementById('productTable').style.display = 'table';
                        document.getElementById('productTableBody').innerHTML = data;
                    } else {
                        document.getElementById('productTable').style.display = 'none';
                    }
                });
        } else {
            document.getElementById('productTable').style.display = 'none';
        }
    });
</script>

<!-- Purchases Table -->
<div class="mt-8 max-w-7xl mx-auto">
    <h2 class="text-xl font-bold mb-4 text-center">Purchases</h2>
    <table class="w-full text-left bg-white rounded-lg overflow-hidden shadow">
        <thead class="bg-gray-200 text-gray-600">
            <tr>
                <?php $columns = ['Item No.', 'Code Number', 'Product Name', 'Price', 'Quantity', 'Total Price', 'Action'];
                foreach ($columns as $column): ?>
                    <th class="py-3 px-4 text-center font-medium"><?= $column ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php 
            $itemNumber = 1;
            foreach ($purchases as $purchase): ?>
                <tr class="hover:bg-gray-50 border-b">
                    <td class="py-2 px-4 text-center"><?= 'Item ' . $itemNumber ?></td>
                    <td class="py-2 px-4 text-center"><?= htmlspecialchars($purchase['code_number']) ?></td>
                    <td class="py-2 px-4"><?= htmlspecialchars($purchase['name']) ?></td>
                    <td class="py-2 px-4 text-center"><?= htmlspecialchars($purchase['price']) ?></td>
                    <td class="py-2 px-4 text-center"><?= htmlspecialchars($purchase['quantity']) ?></td>
                    <td class="py-2 px-4 text-center"><?= htmlspecialchars($purchase['price'] * $purchase['quantity']) ?></td>
                    <td class="py-2 px-4 text-center">
                        <button onclick="openPurchaseModal(<?= htmlspecialchars(json_encode($purchase)) ?>)" class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 transition-all">
                            View
                        </button>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="product_id" value="<?= $purchase['product_id'] ?>">
                            <button type="submit" name="receive_order" class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition-all">
                                Receive
                            </button>
                        </form>
                    </td>
                </tr>
                <?php $itemNumber++;  ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="purchaseModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
        <button onclick="closePurchaseModal()" class="text-gray-400 float-right text-xl font-semibold">&times;</button>
        <h2 class="text-xl font-bold mb-4 text-center">Purchase Details</h2>
        <div id="purchaseModalContents">
            <!-- Purchase details will be populated here -->
        </div>
    </div>
</div>

<script>
    function openPurchaseModal(purchase) {
        document.getElementById("purchaseModalContents").innerHTML = `
            <p><strong>Code Number:</strong> ${purchase.code_number}</p>
            <p><strong>Product Name:</strong> ${purchase.name}</p>
            <p><strong>Price:</strong> ${purchase.price}</p>
            <p><strong>Quantity:</strong> ${purchase.quantity}</p>
            <p><strong>Total Price:</strong> ${purchase.price * purchase.quantity}</p>
        `;
        document.getElementById("purchaseModal").classList.remove("hidden");
    }

    function closePurchaseModal() {
        document.getElementById("purchaseModal").classList.add("hidden");
    }
</script>

<!-- <!-- Purchase Order History Table -->
<div class="mt-12 max-w-7xl mx-auto">
    <h2 class="text-xl font-bold mb-4 text-center">Purchase Order History</h2>
    <table class="w-full text-left bg-white rounded-lg overflow-hidden shadow">
        <thead class="bg-gray-200 text-gray-600">
            <tr>
                <?php $historyColumns = ['Code Number', 'Product Name', 'Manufacturer', 'Type', 'Automobile Type', 'Category', 'Price', 'Quantity', 'Total Price', 'Receive Date', 'Action'];
                foreach ($historyColumns as $column): ?>
                    <th class="py-3 px-4 text-center font-medium"><?= $column ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($receivedOrders as $order): ?>
                <tr class="hover:bg-gray-50 border-b">
                    <td class="py-2 px-4 text-center"><?= htmlspecialchars($order['code_number']) ?></td>
                    <td class="py-2 px-4"><?= htmlspecialchars($order['name']) ?></td>
                    <td class="py-2 px-4"><?= htmlspecialchars($order['manufacturer']) ?></td>
                    <td class="py-2 px-4"><?= htmlspecialchars($order['type']) ?></td>
                    <td class="py-2 px-4"><?= htmlspecialchars($order['automobile_type']) ?></td>
                    <td class="py-2 px-4"><?= htmlspecialchars($order['category']) ?></td>
                    <td class="py-2 px-4 text-center"><?= htmlspecialchars($order['price']) ?></td>
                    <td class="py-2 px-4 text-center"><?= htmlspecialchars($order['quantity']) ?></td>
                    <td class="py-2 px-4 text-center"><?= htmlspecialchars($order['price'] * $order['quantity']) ?></td>
                    <td class="py-2 px-4 text-center"><?= htmlspecialchars($order['receive_date']) ?></td>
                    <td class="py-2 px-4 text-center">
                        <button onclick="openHistoryModal(<?= htmlspecialchars(json_encode($order), ENT_QUOTES, 'UTF-8') ?>)" class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 transition-all">
                            View
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Purchase Order History Modal -->
<div id="historyModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
        <button onclick="closeHistoryModal()" class="text-gray-400 float-right text-xl font-semibold">&times;</button>
        <h2 class="text-xl font-bold mb-4 text-center">Order History Details</h2>
        <div id="historyModalContents">
            <!-- History details will be populated here -->
        </div>
    </div>
</div>

<script>
    function openHistoryModal(order) {
        document.getElementById("historyModalContents").innerHTML = `
            <p><strong>Code Number:</strong> ${order.code_number}</p>
            <p><strong>Product Name:</strong> ${order.name}</p>
            <p><strong>Manufacturer:</strong> ${order.manufacturer}</p>
            <p><strong>Type:</strong> ${order.type}</p>
            <p><strong>Automobile Type:</strong> ${order.automobile_type}</p>
            <p><strong>Category:</strong> ${order.category}</p>
            <p><strong>Price:</strong> ${order.price}</p>
            <p><strong>Quantity:</strong> ${order.quantity}</p>
            <p><strong>Total Price:</strong> ${order.price * order.quantity}</p>
            <p><strong>Receive Date:</strong> ${order.receive_date}</p>
        `;
        document.getElementById("historyModal").classList.remove("hidden");
    }

    function closeHistoryModal() {
        document.getElementById("historyModal").classList.add("hidden");
    }
</script>



<!-- CSS for the modal pop-up -->
<style>
    .close { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
    .close:hover { color: black; }
    #searchResults div { cursor: pointer; padding: 5px; }
    #searchResults div:hover { background-color: #f1f1f1; }
</style>

<!-- JavaScript for modal functionality and search -->
<script>
    const modal = document.getElementById('restockForm');
    const btn = document.getElementById('restockProductBtn');
    const span = document.getElementsByClassName('close')[0];

    btn.onclick = function() {
        modal.classList.remove('hidden');
    }

    span.onclick = function() {
        modal.classList.add('hidden');
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.classList.add('hidden');
        }
    }

    function searchProduct(query) {
        if (query.length < 2) {
            document.getElementById('searchResults').innerHTML = '';
            return;
        }

        fetch(`search_product.php?query=${query}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('searchResults').innerHTML = data;
            });
    }

    function selectProduct(product) {
        document.getElementById('product_id').value = product.id;
        document.getElementById('name').value = product.name;
        document.getElementById('manufacturer').value = product.manufacturer;
        document.getElementById('type').value = product.type;
        document.getElementById('automobile_type').value = product.automobile_type;
        document.getElementById('category').value = product.category;
        document.getElementById('price').value = product.price;
        document.getElementById('searchResults').innerHTML = '';
    }
</script>

