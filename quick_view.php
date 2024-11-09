<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['id'])){
   $user_id = $_SESSION['id'];
}else{
   $user_id = '';
};

if (isset($_POST['submit_review'])) {
   $review_message = $_POST['review_message'];
   $rating = $_POST['rating'];

   // Handle image upload
   $image_name = $_FILES['review_image']['name'];
   $image_tmp_name = $_FILES['review_image']['tmp_name'];
   $image_path = 'uploaded_img/' . $image_name;
   move_uploaded_file($image_tmp_name, $image_path);

   // Insert the review into the database
   $sql = "INSERT INTO reviews (user_id, review_message, rating, image_path) VALUES (?, ?, ?, ?)";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$user_id, $review_message, $rating, $image_path]);
}

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>quick view</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="quick-view">

   <h1 class="heading">quick view</h1>

   <?php
     $pid = $_GET['pid'];
     $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?"); 
     $select_products->execute([$pid]);
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <div class="row">
         <div class="image-container">
            <div class="main-image">
               <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
            </div>
            <div class="sub-image">
               <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
               <img src="uploaded_img/<?= $fetch_product['image_02']; ?>" alt="">
               <img src="uploaded_img/<?= $fetch_product['image_03']; ?>" alt="">
            </div>
         </div>
         <div class="content">
            <div class="name"><?= $fetch_product['name']; ?></div>
            <div class="flex">
               <div class="price"><span>&#8369;</span><?= $fetch_product['price']; ?><span>/-</span></div>
               <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
            </div>
            <div class="details"><?= $fetch_product['details']; ?></div>
            <div class="flex-btn">
               <input type="submit" value="add to cart" class="btn" name="add_to_cart">
               <input class="option-btn" type="submit" name="add_to_wishlist" value="add to wishlist">
               <a href="reviews.php">
                <button type="button" class="option-btn">See Reviews</button>
               </a>
            </div>
         </div>

         
      </div>
   </form>
     <!-- Review Form Section -->
<section class="review-form-section">
   <div class="review-form-container">
      <h2>Add Your Review</h2>
      <form action="" method="post" enctype="multipart/form-data" class="review-form">
         <textarea name="review_message" placeholder="Write your review..." required></textarea>
         <input type="file" name="review_image" required>
         <label for="rating">Rate us:</label>
         <select name="rating" required>
            <option value="5">5 Stars</option>
            <option value="4">4 Stars</option>
            <option value="3">3 Stars</option>
            <option value="2">2 Stars</option>
            <option value="1">1 Star</option>
         </select>
         <button type="submit" name="submit_review" class="btn">Submit Review</button>
      </form>
   </div>
</section>

   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

<style>
.container {
   display: flex;
   align-items: flex-start;
   gap: 20px;
   padding-top: 600px; /* Pushes the container down by 600px */
   padding-bottom: 50px; /* Adjust as needed for bottom spacing */
}
.box, .review-form-section {
   flex: 1;
}

.review-form-section {
   /* padding: 20px; */
   border: 1px solid #ddd;
   background: #f9f9f9;
   margin-left: 600px;
   top: 50%;
}
</style>
 

</section>





<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>