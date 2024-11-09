<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['id'])){
   $user_id = $_SESSION['id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

// Get the category from the URL
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Prepare SQL query based on category
if ($category) {
   $select_products = $conn->prepare("SELECT * FROM `products` WHERE `category` = :category LIMIT 6");
   $select_products->bindParam(':category', $category);
} else {
   $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
}

$select_products->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>HP Performance Home</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<div class="home-bg">

<section class="home">

   <div class="swiper home-slider">
   
   <div class="swiper-wrapper">

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/home-img-1.png" alt="">
         </div>
         <div class="content">
            <span>upto 50% off</span>
            <h3>Latest Exhaust Pipes for Car</h3>
            <a href="shop.php" class="btn">shop now</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/home-img-2.png" alt="">
         </div>
         <div class="content">
            <span>upto 50% off</span>
            <h3>Latest Exhaust Pipes for Car</h3>
            <a href="shop.php" class="btn">shop now</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/home-img-3.png" alt="">
         </div>
         <div class="content">
            <span>upto 50% off</span>
            <h3>Latest Exhaust Pipes for Motorcycle</h3>
            <a href="shop.php" class="btn">shop now</a>
         </div>
      </div>

   </div>

      <div class="swiper-pagination"></div>

   </div>

</section>

</div>

<section class="category">

   <h1 class="heading">shop by category</h1>

   <div class="swiper category-slider">

   <div class="swiper-wrapper">

   <a href="category.php?category=Car Exhaust" class="swiper-slide slide">
      <img src="images/icon-1.png" alt="">
      <h3>Car Exhaust</h3>
   </a>

   <a href="category.php?category=Motorcycle Exhaust" class="swiper-slide slide">
      <img src="images/icon-2.png" alt="">
      <h3>Motorcycle Exhaust</h3>
   </a>

   <a href="category.php?category=Exhaust Auxiliaries" class="swiper-slide slide">
      <img src="images/icon-3.png" alt="">
      <h3>Exhaust Auxiliaries</h3>
   </a>

   <a href="category.php?category=Air Filter" class="swiper-slide slide">
      <img src="images/icon-4.png" >
      <h3>Air Filter</h3>
   </a>

   <a href="category.php?category=others" class="swiper-slide slide">
      <img src="images/icon-5.png" alt="">
      <h3>Others</h3>
   </a>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<section class="home-products">

   <h1 class="heading">latest products</h1>

   <div class="swiper products-slider">

   <div class="swiper-wrapper">
   <?php
if ($select_products->rowCount() > 0) {
   while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
?>
<form action="" method="post" class="swiper-slide slide">
   <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
   <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
   <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
   <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
   <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
   <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
   <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
   <div class="name"><?= $fetch_product['name']; ?></div>
   <div class="flex">
      <div class="price"><span>&#8369;</span><?= $fetch_product['price']; ?><span>/-</span></div>
      <input type="number" name="qty" class="qty" min="1" max="<?= $fetch_product['quantity']; ?>" value="1">
   </div>
   <div class="quantity">Available: <?= $fetch_product['quantity']; ?> pcs</div>
   
   <?php if ($fetch_product['quantity'] > 0) { ?>
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   <?php } else { ?>
      <button class="btn" disabled>Out of Stock</button>
   <?php } ?>
</form>

<?php
   }
} else {
   echo '<p class="empty">no products found in this category!</p>';
}
?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>









<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".home-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
    },
});

 var swiper = new Swiper(".category-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
         slidesPerView: 2,
       },
      650: {
        slidesPerView: 3,
      },
      768: {
        slidesPerView: 4,
      },
      1024: {
        slidesPerView: 5,
      },
   },
});

var swiper = new Swiper(".products-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      550: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>