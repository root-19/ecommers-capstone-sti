<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Archived Products and Users</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>

<body>

<?php include '../components/admin_header.php'; ?>

<section class="show-products">

   <h1 class="heading">Archived Products</h1>

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
                   <th>Deleted At</th>
               </tr>
           </thead>
           <tbody>
               <?php
               $select_archived = $conn->prepare("SELECT * FROM `archived_products`");
               $select_archived->execute();
               if ($select_archived->rowCount() > 0) {
                   while ($fetch_archived = $select_archived->fetch(PDO::FETCH_ASSOC)) { ?>
                       <tr> 
                           <td><?=$fetch_archived['id'];?></td>
                           <td><img src="../uploaded_img/<?php echo htmlspecialchars($fetch_archived['image_01']); ?>" class="product-image" alt=""></td>
                           <td><?php echo htmlspecialchars($fetch_archived['name']); ?></td>
                           <td><?php echo htmlspecialchars($fetch_archived['manufacturer']); ?></td>
                           <td><?php echo htmlspecialchars($fetch_archived['type']); ?></td>
                           <td><?php echo htmlspecialchars($fetch_archived['automobile_type']); ?></td>
                           <td>&#8369;<?php echo htmlspecialchars($fetch_archived['price']); ?></td>
                           <td>&#8369;<?php echo htmlspecialchars($fetch_archived['selling_price']); ?></td>
                           <td><?php echo htmlspecialchars($fetch_archived['details']); ?></td>
                           <td><?php echo htmlspecialchars($fetch_archived['category']); ?></td>
                           <td><?php echo htmlspecialchars($fetch_archived['quantity']); ?></td>
                           <td><?php echo htmlspecialchars($fetch_archived['deleted_at']); ?></td>
                       </tr>
                   <?php }
               } else { ?>
                   <tr>
                       <td colspan="12">No archived products found.</td>
                   </tr>
               <?php } ?>
           </tbody>
       </table>
   </div>

   <h1 class="heading">Archived Users</h1>

   <div class="table-container">
       <table>
           <thead>
               <tr>
                   <th>User ID</th>
                   <th>Name</th>
                   <th>Email</th>
                   
                   <th>Archived At</th>
               </tr>
           </thead>
           <tbody>
               <?php
               $select_archived_users = $conn->prepare("SELECT * FROM `archived_users`");
               $select_archived_users->execute();
               if ($select_archived_users->rowCount() > 0) {
                   while ($fetch_archived_user = $select_archived_users->fetch(PDO::FETCH_ASSOC)) { ?>
                       <tr> 
                           <td><?php echo htmlspecialchars($fetch_archived_user['user_id']); ?></td>
                           <td><?php echo htmlspecialchars($fetch_archived_user['name']); ?></td>
                           <td><?php echo htmlspecialchars($fetch_archived_user['email']); ?></td>
                           <td><?php echo htmlspecialchars($fetch_archived_user['deleted_at']); ?></td>
                       </tr>
                   <?php }
               } else { ?>
                   <tr>
                       <td colspan="6">No archived users found.</td>
                   </tr>
               <?php } ?>
           </tbody>
       </table>
   </div>

   <h1 class="heading">Canceled Orders</h1>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Product Quantities</th>
                <th>Total Price</th>
                <th>Payment Method</th>
                <th>Canceled At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $select_canceled_orders = $conn->prepare("SELECT * FROM `canceled_orders`");
            $select_canceled_orders->execute();
            if ($select_canceled_orders->rowCount() > 0) {
                while ($fetch_canceled_order = $select_canceled_orders->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($fetch_canceled_order['id']); ?></td>
                        <td><?php echo htmlspecialchars($fetch_canceled_order['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($fetch_canceled_order['product_quantities']); ?></td>
                        <td>&#8369;<?php echo htmlspecialchars($fetch_canceled_order['total_price']); ?></td>
                        <td><?php echo htmlspecialchars($fetch_canceled_order['method']); ?></td>
                        <td><?php echo htmlspecialchars($fetch_canceled_order['deleted_at']); ?></td>
                        <td>
                            <form action="send_to_rider.php" method="post" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($fetch_canceled_order['id']); ?>">
                                <button type="submit" name="send_data" class="btn">Send to Rider</button>
                            </form>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="7">No canceled orders found.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div><h1 class="heading">Return And Refund</h1>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Concern</th>
                <th>GCash Number</th>
                <th>Address</th>
                <th>Image</th> <!-- New Column for Image -->
            </tr>
        </thead>
        <tbody>
        <?php
            $select_canceled_orders = $conn->prepare("SELECT * FROM `refunds`");
            $select_canceled_orders->execute();
            if ($select_canceled_orders->rowCount() > 0) {
                while ($fetch_canceled_order = $select_canceled_orders->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($fetch_canceled_order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($fetch_canceled_order['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($fetch_canceled_order['concern']); ?></td>
                        <td>&#8369;<?php echo htmlspecialchars($fetch_canceled_order['gcash_number']); ?></td>
                        <td><?php echo htmlspecialchars($fetch_canceled_order['address']); ?></td>
                        <td>
    <img src="../uploads/<?php echo htmlspecialchars($fetch_canceled_order['image_path']); ?>" alt="Refund Image" style="width: 100px; height: auto;">
</td>

                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="6">No refund and return found.</td> <!-- Adjust colspan to match the number of columns -->
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</div>

</section>

<script src="../js/admin_script.js"></script>
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

.empty {
    text-align: center;
    padding: 20px;
    color: #888;
}
</style>