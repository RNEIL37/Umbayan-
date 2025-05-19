<?php
@include 'session.php';
?>

<?php
@include 'config.php';

if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = 1;

    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name'");

    if (mysqli_num_rows($select_cart) === 0) {
        mysqli_query($conn, "INSERT INTO `cart`(name, price, image, quantity) VALUES('$product_name', '$product_price', '$product_image', '$product_quantity')");
    }
}
// Search logic
$search_term = '';
if (isset($_POST['search'])) {
    $search_term = mysqli_real_escape_string($conn, $_POST['search_term']);
    $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%$search_term%' OR description LIKE '%$search_term%'");
} else {
    $select_products = mysqli_query($conn, "SELECT * FROM `products`");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Products</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <style>
      /* Unique category card styling */
      .category-card {
         background: #fefefe;
         border-radius: 15px;
         box-shadow: 0 8px 16px rgba(0,0,0,0.15);
         margin-bottom: 50px;
         padding: 20px 30px 40px 30px;
         position: relative;
      }
      /* Ribbon style for category heading */
      .category-ribbon {
         position: absolute;
         top: -12px;
         left: 30px;
         background: #2980b9;
         color: white;
         padding: 8px 20px;
         font-weight: 700;
         font-size: 1.4em;
         border-radius: 25px;
         box-shadow: 0 4px 8px rgba(41,128,185,0.4);
         letter-spacing: 1px;
      }
      /* Grid for products inside category */
      .products-grid {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
         gap: 25px;
         margin-top: 60px;
      }
      /* Product card */
      .product-form {
         background: white;
         padding: 20px;
         border-radius: 12px;
         box-shadow: 0 5px 15px rgba(0,0,0,0.1);
         text-align: center;
         transition: transform 0.3s ease, box-shadow 0.3s ease;
         cursor: pointer;
      }
      .product-form:hover {
         transform: translateY(-6px);
         box-shadow: 0 10px 25px rgba(0,0,0,0.15);
      }
      .product-img-wrapper {
         overflow: hidden;
         border-radius: 12px;
      }
      .product-img-wrapper img {
         width: 100%;
         height: 180px;
         object-fit: cover;
         border-radius: 12px;
         transition: transform 0.3s ease;
      }
      .product-img-wrapper img:hover {
         transform: scale(1.1);
      }
      .product-name {
         margin: 18px 0 12px;
         font-size: 1.2em;
         color: #34495e;
         font-weight: 600;
      }
      .product-desc {
         color: #7f8c8d;
         font-size: 14px;
         min-height: 48px;
         margin-bottom: 15px;
      }
      .product-price {
         font-size: 18px;
         color: #27ae60;
         margin-bottom: 12px;
         font-weight: 700;
      }
      .add-cart-btn {
         background: #3498db;
         color: white;
         padding: 10px 20px;
         border: none;
         border-radius: 8px;
         cursor: pointer;
         font-weight: 600;
         transition: background-color 0.3s ease;
      }
      .add-cart-btn:hover {
         background: #2980b9;
      }
   </style>

</head>
<body style="margin:0; background: url('images/fd.jpg') no-repeat center center fixed; background-size: cover; color:#333;">

<?php
if (isset($message)) {
   foreach ($message as $msg) {
      echo '<div style="background:#ffdddd; padding:10px; margin:10px; border-left:5px solid red;">
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.style.display=\'none\'" style="float:right; cursor:pointer;"></i>
      </div>';
   }
}
?>
<header style="background:linear-gradient(90deg, #34495e, #2c3e50); color:#fff; padding:15px 30px; box-shadow:0 2px 6px rgba(0,0,0,0.2); position:sticky; top:0; z-index:100;">
   <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap;">
      <!-- Logo and Title -->
      <div style="display:flex; align-items:center; gap:10px;">
         <img src="https://cdn-icons-png.flaticon.com/512/1046/1046784.png" alt="Logo" style="width:40px; height:40px; border-radius:50%;">
         <span style="font-size:1.8em; font-weight:bold;"> Food Ordering Catering System</span>
      </div>

      <!-- Navigation Menu -->
      <nav style="display:flex; align-items:center; gap:20px; flex-wrap:wrap;">
         <a href="products.php?id=<?php.$fac_session_id?>'" style="color:#ecf0f1; text-decoration:none; font-weight:500; transition:0.3s;" onmouseover="this.style.color='#1abc9c'" onmouseout="this.style.color='#ecf0f1'">Menu</a>
         <a href="products2.php" style="color:#ecf0f1; text-decoration:none; font-weight:500; transition:0.3s;" onmouseover="this.style.color='#1abc9c'" onmouseout="this.style.color='#ecf0f1'">Catering Menu</a>
         <a href="cart1.php" style="color:#ecf0f1; text-decoration:none; font-weight:500; transition:0.3s;" onmouseover="this.style.color='#1abc9c'" onmouseout="this.style.color='#ecf0f1'">Cart</a>
         <a href="user_notif.php" style="color:#ecf0f1; text-decoration:none; font-weight:500; position:relative; transition:0.3s;" onmouseover="this.style.color='#1abc9c'" onmouseout="this.style.color='#ecf0f1'">
            Notifications
            <span style="position:absolute; top:-10px; right:-15px; background:red; color:white; padding:2px 8px; border-radius:50%; font-size:0.8em;">
               <?php 
               $select_rows = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
               echo mysqli_num_rows($select_rows); 
               ?>
            </span>
         </a>
         <a href="logout.php" style="background:#e74c3c; padding:8px 14px; color:white; border-radius:5px; font-weight:500; text-decoration:none; transition:0.3s;" onmouseover="this.style.background='#c0392b'" onmouseout="this.style.background='#e74c3c'">
            Logout
         </a>
      </nav>
   </div>
</header>

<main style="padding:40px; max-width:1200px; margin:auto;">
   <h1 style="text-align:center; margin-bottom:30px; color:#2980b9;">Browse Products by Category</h1>

   <!-- Search bar -->
   <form method="post" style="text-align:center; margin-bottom:20px;">
      <input type="text" name="search_term" placeholder="Search products..." 
         value="<?php echo htmlspecialchars($search_term); ?>"
         style="padding:10px; width:80%; max-width:400px; border:1px solid #ccc; border-radius:6px; font-size:16px;">
      <button type="submit" name="search"
         style="padding:10px 20px; background:#27ae60; color:white; border:none; border-radius:6px; cursor:pointer;">
         Search
      </button>
   </form>
<!-- Unique category buttons -->
<form method="post" style="text-align:center; margin-bottom:40px;">
   <style>
      .category-button {
         display: inline-block;
         padding: 10px 20px;
         margin: 8px;
         border: none;
         border-radius: 25px;
         font-weight: 600;
         font-size: 14px;
         color: white;
         cursor: pointer;
         box-shadow: 0 4px 10px rgba(0,0,0,0.1);
         transition: all 0.3s ease;
      }
      .category-button:hover {
         transform: translateY(-2px);
         opacity: 0.9;
      }
      /* Predefined colors based on position */
      .category-button:nth-child(1) { background-color: #34495e; }
      .category-button:nth-child(2) { background-color:  #34495e; }
      .category-button:nth-child(3) { background-color:  #34495e; }
      .category-button:nth-child(4) { background-color:  #34495e; }
      .category-button:nth-child(5) { background-color:  #34495e; }
      .category-button:nth-child(6) { background-color:  #34495e; }
      .category-button:nth-child(7) { background-color:  #34495e; }
      .category-button:nth-child(8) { background-color:  #34495e; }
      .category-button:nth-child(9) { background-color:  #34495e; }
   </style>

   <button type="submit" name="filter_category" value="" class="category-button">All</button>
   <?php
   $categories = mysqli_query($conn, "SELECT DISTINCT category FROM `products`");
   $index = 2;
   while ($cat = mysqli_fetch_assoc($categories)) {
      $cat_name = htmlspecialchars($cat['category']);
      echo "<button type='submit' name='filter_category' value='{$cat_name}' class='category-button'>{$cat_name}</button>";
      $index++;
   }
   ?>
</form>


   <?php
   // Filter by selected category
   $selected_category = '';
   if (isset($_POST['filter_category'])) {
       $selected_category = mysqli_real_escape_string($conn, $_POST['filter_category']);
   }

   // Build query
   $query = "SELECT * FROM `products` WHERE 1";
   if (!empty($selected_category)) {
       $query .= " AND category = '$selected_category'";
   }
   if (!empty($search_term)) {
       $query .= " AND (name LIKE '%$search_term%' OR description LIKE '%$search_term%')";
   }

   $products = mysqli_query($conn, $query);

   if (mysqli_num_rows($products) > 0) {
      echo "<div class='products-grid'>";
      while ($product = mysqli_fetch_assoc($products)) {
   ?>

   <form method="post" class="product-form">
      <div class="product-img-wrapper">
         <img src="uploaded_img/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
      </div>
      <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
      <p class="product-desc"><?php echo htmlspecialchars($product['description']); ?></p>
      <div class="product-price">₱<?php echo htmlspecialchars($product['price']); ?></div>
      <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
      <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($product['price']); ?>">
      <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($product['image']); ?>">
      <input type="submit" name="add_to_cart" value="Add to Cart" class="add-cart-btn">
   </form>

   <?php
      }
      echo "</div>";
   } else {
      echo "<p style='text-align:center;'>No products found.</p>";
   }
   ?>
</main>


</body>
</html>
