<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['id'])){
   $user_id = $_SESSION['id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};

if(isset($_POST['delete'])){
   $cart_id = $_POST['cart_id'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
}

if(isset($_GET['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   header('location:cart.php');
}

if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);
   $message[] = 'cart quantity updated';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="products shopping-cart">
        <div class="container">
            <h3 class="heading text-center my-4">Shopping Cart</h3>

            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th> 
                            <th scope="col">Image</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Subtotal</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $grand_total = 0;
                        $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                        $select_cart->execute([$user_id]);
                        if ($select_cart->rowCount() > 0) {
                            while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                                <tr>
                                <td><?= $fetch_cart['id']; ?></td>
                                    <td>
                                    
                                        <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
                                       
                                        <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="" class="img-thumbnail" style="width: 100px; height: 100px;">
                                    </td>
                                    <td><?= $fetch_cart['name']; ?></td>
                                    <td>&#8369;<?= $fetch_cart['price']; ?>/-</td>
                                    <td>
                                        <form action="" method="post">
                                            <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
                                            <input type="number" name="qty" class="form-control" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="<?= $fetch_cart['quantity']; ?>">
                                            <button type="submit" class="btn btn-sm btn-success mt-2" name="update_qty">Update</button>
                                        </form>
                                    </td>
                                    <td>&#8369;<?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</td>
                                    <td>
                                        <form action="" method="post">
                                            <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
                                            <input type="submit" value="Delete" onclick="return confirm('Delete this item from cart?');" class="btn btn-danger" name="delete">
                                        </form>
                                    </td>
                                </tr>
                        <?php
                                $grand_total += $sub_total;
                            }
                        } else {
                            echo '<tr><td colspan="6" class="text-center">Your cart is empty</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="cart-total text-center">
                <p class="fw-bold">Grand Total: <span>&#8369;<?= $grand_total; ?>/-</span></p>
                <a href="shop.php" class="btn btn-primary">Continue Shopping</a>
                <a href="cart.php?delete_all" class="btn btn-danger <?= ($grand_total > 1) ? '' : 'disabled'; ?>" onclick="return confirm('Delete all items from cart?');">Delete All Items</a>
                <a href="checkout.php" class="btn btn-success <?= ($grand_total > 1) ? '' : 'disabled'; ?>">Proceed to Checkout</a>
            </div>
        </div>
    </section>

    <?php include 'components/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>

</body>

</html>



