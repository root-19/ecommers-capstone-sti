<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['id'])){
   $user_id = $_SESSION['id'];
}else{
   $user_id = '';
}

// Handle review form submission
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

// Fetch all reviews from the database
$sql = "SELECT reviews.review_message, reviews.rating, reviews.image_path, reviews.created_at, users.name 
        FROM reviews 
        JOIN users ON reviews.user_id = users.id
        ORDER BY reviews.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC)

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>HP About</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">


   <style>
      .review-form {
         margin: 20px 0;
         padding: 20px;
         border: 1px solid #ddd;
         background-color: #f9f9f9;
      }
      .stars i.checked {
         color: gold;
      }
      .stars i.unchecked {
         color: #ddd;
      }

      .review-form-section {
    display: flex;
    justify-content: center;
    align-items: center;
    /* min-height: 100vh; */
      }

.review-form-container {
    background-color: #fff;
    padding: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); 
    width: 90%;
    max-width: 500px;
}


.review-form textarea,
.review-form input,
.review-form select,
.review-form button {
    width: 100%;
    margin-bottom: 15px;
    padding: 10px; 
    border: 1px solid #ccc;
    border-radius: 4px;
}

.review-form button {
    background-color: #dc2626;
    color: white;
    cursor: pointer; 
}
.photo{
   margin-left:100px;
}
 </style>

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="about">
   <div class="row">
      <div class="image">
         <img src="images/about-img.svg" alt="">
      </div>
      <div class="content">
         <h3>Why choose us?</h3>
         <p>We strive to meet your chuchuchuchu.</p>
         <a href="contact.php" class="btn">Contact Us</a>
      </div>
   </div>
</section>

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



<!-- Display Reviews Section -->
<section class="reviews">
   <h1 class="heading">Client's Reviews</h1>

   <div class="swiper reviews-slider">
      <div class="swiper-wrapper">

         <?php if (count($reviews) > 0): ?>
            <?php foreach ($reviews as $row): ?>
               <div class="swiper-slide slide">
                  <img src="<?php echo $row['image_path']; ?>" alt="Review Image" class="photo">
                  <p><?php echo $row['review_message']; ?></p>
                  <div class="stars">
                     <!-- Display the number of filled stars based on the user's rating -->
                     <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star <?php echo ($i <= $row['rating']) ? 'checked' : 'unchecked'; ?>"></i>
                     <?php endfor; ?>
                  </div>
                  <h3><?php echo htmlspecialchars($row['name']); ?></h3>
               </div>
            <?php endforeach; ?>
         <?php else: ?>
            <p>No reviews yet. Be the first to add one!</p>
         <?php endif; ?>

      </div>
      <div class="swiper-pagination"></div>
   </div>

</section>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script>
   var swiper = new Swiper(".reviews-slider", {
      loop: true,
      pagination: {
         el: ".swiper-pagination",
         clickable: true,
      },
   });
</script>

</body>
</html>


</body>
</html>









<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".reviews-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
        slidesPerView:1,
      },
      768: {
        slidesPerView: 2,
      },
      991: {
        slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>