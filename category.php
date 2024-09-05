<?php
include 'components/connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:user_login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Get the category from the URL
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Prepare SQL query based on category
if ($category) {
    $select_products = $conn->prepare("SELECT * FROM `products` WHERE `category` = :category");
    $select_products->bindParam(':category', $category);
} else {
    $select_products = $conn->prepare("SELECT * FROM `products`");
}

$select_products->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category - HP Performance</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="category-products">
    <h1 class="heading"><?= htmlspecialchars($category); ?> Products</h1>

    <div class="products-container">
        <?php
        if ($select_products->rowCount() > 0) {
            while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <form action="" method="post" class="product-item">
                    <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_product['id']); ?>">
                    <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_product['name']); ?>">
                    <input type="hidden" name="price" value="<?= htmlspecialchars($fetch_product['price']); ?>">
                    <input type="hidden" name="image" value="<?= htmlspecialchars($fetch_product['image_01']); ?>">
                    <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
                    <a href="quick_view.php?pid=<?= htmlspecialchars($fetch_product['id']); ?>" class="fas fa-eye"></a>
                    <img src="uploaded_img/<?= htmlspecialchars($fetch_product['image_01']); ?>" alt="">
                    <div class="name"><?= htmlspecialchars($fetch_product['name']); ?></div>
                    <div class="flex">
                        <div class="price"><span>$</span><?= htmlspecialchars($fetch_product['price']); ?><span>/-</span></div>
                        <input type="number" name="qty" class="qty" min="1" max="99" value="1">
                    </div>
                    <input type="submit" value="add to cart" class="btn" name="add_to_cart">
                </form>
                <?php
            }
        } else {
            echo '<p class="empty">No products found in this category!</p>';
        }
        ?>
    </div>
</section>

<?php include 'components/footer.php'; ?>

</body>
</html>
