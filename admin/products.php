<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit();
}

if (isset($_POST['add_product'])) {

    // Check if the required POST variables are set
    $name = isset($_POST['name']) ? filter_var($_POST['name'], FILTER_SANITIZE_STRING) : '';
    $manufacturer = isset($_POST['manufacturer']) ? filter_var($_POST['manufacturer'], FILTER_SANITIZE_STRING) : '';
    $type = isset($_POST['type']) ? filter_var($_POST['type'], FILTER_SANITIZE_STRING) : '';
    $automobile_type = isset($_POST['automobile_type']) ? filter_var($_POST['automobile_type'], FILTER_SANITIZE_STRING) : '';
    $price = isset($_POST['price']) ? filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : '';
    $selling_price = isset($_POST['selling_price']) ? filter_var($_POST['selling_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : '';
    $details = isset($_POST['details']) ? filter_var($_POST['details'], FILTER_SANITIZE_STRING) : '';
    $category = isset($_POST['category']) ? filter_var($_POST['category'], FILTER_SANITIZE_STRING) : '';
    $quantity = isset($_POST['quantity']) ? filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT) : '';
    $date = isset($_POST['date']) ? filter_var($_POST['date'], FILTER_SANITIZE_STRING) : '';

    // Handle file uploads
    $image_01 = isset($_FILES['image_01']['name']) ? filter_var($_FILES['image_01']['name'], FILTER_SANITIZE_STRING) : '';
    $image_size_01 = isset($_FILES['image_01']['size']) ? $_FILES['image_01']['size'] : 0;
    $image_tmp_name_01 = isset($_FILES['image_01']['tmp_name']) ? $_FILES['image_01']['tmp_name'] : '';
    $image_folder_01 = '../uploaded_img/' . $image_01;

    $image_02 = isset($_FILES['image_02']['name']) ? filter_var($_FILES['image_02']['name'], FILTER_SANITIZE_STRING) : '';
    $image_size_02 = isset($_FILES['image_02']['size']) ? $_FILES['image_02']['size'] : 0;
    $image_tmp_name_02 = isset($_FILES['image_02']['tmp_name']) ? $_FILES['image_02']['tmp_name'] : '';
    $image_folder_02 = '../uploaded_img/' . $image_02;

    $image_03 = isset($_FILES['image_03']['name']) ? filter_var($_FILES['image_03']['name'], FILTER_SANITIZE_STRING) : '';
    $image_size_03 = isset($_FILES['image_03']['size']) ? $_FILES['image_03']['size'] : 0;
    $image_tmp_name_03 = isset($_FILES['image_03']['tmp_name']) ? $_FILES['image_03']['tmp_name'] : '';
    $image_folder_03 = '../uploaded_img/' . $image_03;

    // Check if product already exists
    $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
    $select_products->execute([$name]);

    if ($select_products->rowCount() > 0) {
        $message[] = 'Product name already exists!';
    } else {
        // Check image sizes before proceeding
        if ($image_size_01 > 2000000 || $image_size_02 > 2000000 || $image_size_03 > 2000000) {
            $message[] = 'Image size is too large!';
        } else {
            // Insert product into the database
            $insert_products = $conn->prepare("INSERT INTO `products`(name, manufacturer, type, automobile_type, price, selling_price, details, image_01, image_02, image_03, category, quantity, date) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $insert_products->execute([$name, $manufacturer, $type, $automobile_type, $price, $selling_price, $details, $image_01, $image_02, $image_03, $category, $quantity, $date]);

            if ($insert_products) {
                // Move uploaded files to the server
                move_uploaded_file($image_tmp_name_01, $image_folder_01);
                move_uploaded_file($image_tmp_name_02, $image_folder_02);
                move_uploaded_file($image_tmp_name_03, $image_folder_03);
                $message[] = 'New product added!';
            }
        }
    }
}

// Handle deletion and archiving
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    // Fetch product data before deletion
    $fetch_product = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
    $fetch_product->execute([$delete_id]);
    $product_data = $fetch_product->fetch(PDO::FETCH_ASSOC);

    if ($product_data) {
        // Insert the product data into the archive table (excluding the date)
        $archive_product = $conn->prepare("INSERT INTO `archived_products` (name, manufacturer, type, automobile_type, price, selling_price, details, image_01, image_02, image_03, category, quantity) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)");
        $archive_product->execute([
            $product_data['name'],
            $product_data['manufacturer'],
            $product_data['type'],
            $product_data['automobile_type'],
            $product_data['price'],
            $product_data['selling_price'],
            $product_data['details'],
            $product_data['image_01'],
            $product_data['image_02'],
            $product_data['image_03'],
            $product_data['category'],
            $product_data['quantity']
        ]);
        // print_r([$name, $manufacturer, $type, $automobile_type, $price, $selling_price, $details, $image_01, $image_02, $image_03, $category, $quantity, $date]);
        // exit();

        // Delete product images from the server
        if (file_exists('../uploaded_img/' . $product_data['image_01'])) {
            unlink('../uploaded_img/' . $product_data['image_01']);
        }
        if (file_exists('../uploaded_img/' . $product_data['image_02'])) {
            unlink('../uploaded_img/' . $product_data['image_02']);
        }
        if (file_exists('../uploaded_img/' . $product_data['image_03'])) {
            unlink('../uploaded_img/' . $product_data['image_03']);
        }

        // Remove the product from the products table
        $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
        $delete_product->execute([$delete_id]);

        // Redirect back to the products page after deletion
        header('location:products.php');
    } else {
        echo "Product not found!";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>

<body>

<?php include '../components/admin_header.php'; ?>

<div class="pop-up">
   <button id="addProductBtn">Add Product</button>
   <button class="this-update" id="ProductBtn">update product</button>
</div>

<!-- Overlay for background dim effect -->
<div class="overlay" id="overlay"></div>

<!-- Pop-up Form for Searching and Updating -->
<section class="add-products" id="updateProductForm">
    <h1 class="heading">Update Product Quantity</h1>

    <!-- Search bar to find products -->
    <div class="inputBox">
        <span>Search Product by Name</span>
        <input type="text" id="productSearch" class="box" placeholder="Enter product name" oninput="searchProduct(this.value)">
    </div>

    <!-- Search suggestions will be displayed here -->
    <div id="searchResults" class="search-results"></div>

    <!-- Quantity Update Form -->
    <form id="updateQuantityForm" action="update_product.php" method="POST">
        <input type="hidden" name="product_id" id="product_id">
        <div class="inputBox">
            <span>New Quantity</span>
            <input type="number" name="new_quantity" id="new_quantity" class="box" min="1" required placeholder="Enter new quantity">
        </div>
        <input type="submit" value="Update Quantity" class="btn">
    </form>
</section>

<section class="add-products" id="addProductForm">
   <h1 class="heading">Add Product</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>Product Name (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="Enter product name" name="name">
         </div>
         <div class="inputBox">
            <span>Manufacturer/Brand (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="Enter manufacturer/brand" name="manufacturer">
         </div>
         <div class="inputBox">
            <span>Type (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="Enter type" name="type">
         </div>
         <div class="inputBox">
            <span>Automobile Type (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="Enter automobile type" name="automobile_type">
         </div>
         <div class="inputBox">
            <span>Price (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="Enter price" name="price">
         </div>
         <div class="inputBox">
            <span>Selling Price (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="Enter selling price" name="selling_price">
         </div>
         <div class="inputBox">
            <span>Details (required)</span>
            <textarea name="details" class="box" placeholder="Enter product details" required></textarea>
         </div>
         <div class="inputBox">
            <span>Date (required)</span>
            <input type="date" class="box" required name="date">
         </div>
         <div class="inputBox">
            <span>Category (required)</span>
            <select name="category" class="box" required>
               <option value="" disabled selected>Choose category</option>
               <option value="Car Exhaust">Car Exhaust</option>
               <option value="Motorcycle Exhaust">Motorcycle Exhaust</option>
               <option value="Exhaust Auxiliaries">Exhaust Auxiliaries</option>
               <option value="Air Filter">Air Filter</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Quantity (required)</span>
            <input type="number" class="box" required placeholder="Enter quantity" name="quantity">
         </div>
         <div class="inputBox">
            <span>Image 01 (required)</span>
            <input type="file" class="box" accept="image/*" name="image_01" required>
         </div>
         <div class="inputBox">
            <span>Image 02</span>
            <input type="file" class="box" accept="image/*" name="image_02">
         </div>
         <div class="inputBox">
            <span>Image 03</span>
            <input type="file" class="box" accept="image/*" name="image_03">
         </div>
      </div>
      <input type="submit" value="Add Product" class="btn" name="add_product">
   </form>
</section>

<section class="show-products">

   <h1 class="heading">Products</h1>

   <div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Image 01</th>
                <th>Name</th>
                <th>Manufacturer</th>
                <th>Type</th>
                <th>Automobile Type</th>
                <th>Price</th>
                <th>Selling Price</th>
                <th>Details</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $select_products = $conn->prepare("SELECT * FROM `products`");
            $select_products->execute();
            if($select_products->rowCount() > 0){
                while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){ ?>
                <tr> 
                    <td><?=$fetch_product['id'];?></td>
                    <td><img src="../uploaded_img/<?php echo htmlspecialchars($fetch_product['image_01']); ?>" class="product-image" alt=""></td>
                    <td><?php echo htmlspecialchars($fetch_product['name']); ?></td>
                    <td><?php echo htmlspecialchars($fetch_product['manufacturer']); ?></td>
                    <td><?php echo htmlspecialchars($fetch_product['type']); ?></td>
                    <td><?php echo htmlspecialchars($fetch_product['automobile_type']); ?></td>
                    <td>&#8369;<?php echo htmlspecialchars($fetch_product['price']); ?></td>
                    <td>&#8369;<?php echo htmlspecialchars($fetch_product['selling_price']); ?></td>
                    <td><?php echo htmlspecialchars($fetch_product['details']); ?></td>
                    <td><?php echo htmlspecialchars($fetch_product['category']); ?></td>
                    <td><?php echo htmlspecialchars($fetch_product['quantity']); ?></td>

                    
                    <!-- Editable Quantity Form -->
                    <!-- <td>
                        <form action="update_product.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $fetch_product['id']; ?>">
                            <input type="number" name="new_quantity" value="<?php echo htmlspecialchars($fetch_product['quantity']); ?>" min="0">
                            <button type="submit" class="option-btn">Update</button>
                        </form>
                    </td> -->

                    <td class="actions">
                        <a href="products.php?delete=<?php echo $fetch_product['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                    </td>
                </tr>
                <?php }
            } else {
                echo '<tr><td colspan="13" class="empty">No products available</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

   </div>

</section>
<script>
   // Get the button and form elements
   const addProductBtn = document.getElementById('addProductBtn');
   const addProductForm = document.getElementById('addProductForm');
   const overlay = document.getElementById('overlay');

   // Show the form when the button is clicked
   addProductBtn.addEventListener('click', function() {
      addProductForm.classList.add('active');
      overlay.classList.add('active');
   });

   // Hide the form when the overlay is clicked
   overlay.addEventListener('click', function() {
      addProductForm.classList.remove('active');
      overlay.classList.remove('active');
   });

   // Show pop-up on button click
document.getElementById('ProductBtn').addEventListener('click', function() {
    document.getElementById('updateProductForm').classList.add('active');
    document.getElementById('overlay').classList.add('active');
});

// Hide pop-up when clicking outside the form
document.getElementById('overlay').addEventListener('click', function() {
    document.getElementById('updateProductForm').classList.remove('active');
    document.getElementById('overlay').classList.remove('active');
});

// Function to search for products as the user types
function searchProduct(query) {
    if (query.length === 0) {
        document.getElementById('searchResults').innerHTML = '';
        return;
    }

    // AJAX to fetch product suggestions
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'search_product.php?query=' + encodeURIComponent(query), true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById('searchResults').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

// Function to fill the form when a product is selected
function selectProduct(id, name, quantity) {
    document.getElementById('product_id').value = id;
    document.getElementById('new_quantity').value = quantity;
    document.getElementById('productSearch').value = name;
    document.getElementById('searchResults').innerHTML = '';
}

</script>
</body>
</html>



<style>
  table img {
           width: 100px; 
           height: auto; 
           object-fit: cover; 
       }
.table-container {
    overflow-x: auto;
    margin: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    text-align: center;
}

th, td {
    border: 1px solid #ddd;
    padding: 12px;
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

.product-image {
    width: 150px;
    height: auto;
    object-fit: cover;
}

.actions {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.option-btn, .delete-btn {
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 4px;
    color: #fff;
    display: inline-block;
}

.option-btn {
    background-color: #4CAF50; /* Green */
}

.option-btn:hover {
    background-color: #45a049;
}

.delete-btn {
    background-color: #f44336; /* Red */
}

.delete-btn:hover {
    background-color: #e53935;
}

.empty {
    text-align: center;
    padding: 20px;
    color: #888;
}

</style>