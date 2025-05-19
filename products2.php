<?php
@include 'config.php';

// Handle add to cart
if (isset($_POST['add_to_cart'])) {
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = 1;

   if ($_POST['cart_type'] === 'regular') {
      $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name'");
      if (mysqli_num_rows($select_cart) === 0) {
         mysqli_query($conn, "INSERT INTO `cart`(name, price, image, quantity) VALUES('$product_name', '$product_price', '$product_image', '$product_quantity')");
      }
   } else if ($_POST['cart_type'] === 'cater') {
      $select_cart = mysqli_query($conn, "SELECT * FROM `cart_cater` WHERE name = '$product_name'");
      if (mysqli_num_rows($select_cart) === 0) {
         mysqli_query($conn, "INSERT INTO `cart_cater`(name, price, image, quantity) VALUES('$product_name', '$product_price', '$product_image', '$product_quantity')");
      }
   }
}

// Handle category filter and search
$search_query = "";
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

if (!empty(trim($_GET['search'] ?? ''))) {
   $search_query = mysqli_real_escape_string($conn, $_GET['search']);
   $query = "SELECT * FROM `catering` WHERE name LIKE '%$search_query%'";
   if (!empty($category_filter)) {
      $query .= " AND category = '$category_filter'";
   }
} else if (!empty($category_filter)) {
   $query = "SELECT * FROM `catering` WHERE category = '$category_filter'";
} else {
   $query = "SELECT * FROM `catering`";
}

$cater_products = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Catering Products</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="margin:0; background: url('images/fd.jpg') no-repeat center center fixed; background-size: cover; color:#333;">
<header style="background:linear-gradient(90deg, #34495e, #2c3e50); color:#fff; padding:15px 30px; box-shadow:0 2px 6px rgba(0,0,0,0.2); position:sticky; top:0; z-index:100;">
   <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap;">
      <!-- Logo and Title -->
      <div style="display:flex; align-items:center; gap:10px;">
         <img src="https://cdn-icons-png.flaticon.com/512/1046/1046784.png" alt="Logo" style="width:40px; height:40px; border-radius:50%;">
         <span style="font-size:1.8em; font-weight:bold;"> Food Ordering Catering System</span>
      </div>

      <!-- Navigation Menu -->
      <nav style="display:flex; align-items:center; gap:20px; flex-wrap:wrap;">
         <a href="products.php" style="color:#ecf0f1; text-decoration:none; font-weight:500; transition:0.3s;" onmouseover="this.style.color='#1abc9c'" onmouseout="this.style.color='#ecf0f1'">Menu</a>
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


<main style="padding:20px; max-width:1200px; margin:auto;">
   <h1 style="text-align:center; margin-bottom:30px;">Catering Products</h1>

   <!-- Search Bar -->
   <form method="GET" style="display:flex; justify-content:center; gap:10px; flex-wrap:wrap; margin-bottom:30px;">
      <input 
         type="text" 
         name="search" 
         placeholder="Search catering products..." 
         value="<?php echo htmlspecialchars($search_query); ?>"
         style="padding:10px; width:100%; max-width:400px; border:1px solid #ccc; border-radius:5px;"
      />
      <?php if (!empty($category_filter)) { echo "<input type='hidden' name='category' value='" . htmlspecialchars($category_filter) . "'>"; } ?>
      <button type="submit" style="padding:10px 20px; background:#2ecc71; color:white; border:none; border-radius:5px;">Search</button>
   </form>
   <!-- Category Buttons -->
   <div style="display:flex; justify-content:center; flex-wrap:wrap; gap:10px; margin-bottom:20px;">
      <?php
      $categories = ['Wedding', 'Birthday', 'Dessert', 'Buffet' ,'Social Event Catering', 'All'];
      foreach ($categories as $cat) {
         $active = ($category_filter === $cat || ($cat === 'All' && $category_filter === '')) ? 'background:#3498db; color:#fff;' : 'background:#ecf0f1; color:#333;';
         $cat_query = ($cat === 'All') ? '' : 'category=' . urlencode($cat);
         echo "<a href='?{$cat_query}' style='padding:10px 20px; border:none; border-radius:5px; text-decoration:none; $active font-weight:bold;'>$cat</a>";
      }
      ?>
   </div>


   <!-- Product Grid -->
   <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:30px;">
      <?php
      if (mysqli_num_rows($cater_products) > 0) {
         while ($cater = mysqli_fetch_assoc($cater_products)) {
      ?>
      <form method="post" style="background:white; padding:20px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.1); text-align:center;">
         <img src="uploaded_img/<?php echo $cater['image']; ?>" style="width:100%; height:200px; object-fit:cover; border-radius:10px;">
         <h2 style="margin:15px 0;"><?php echo $cater['name']; ?></h2>
         <h3 style="color:#7f8c8d; font-size:14px;"><?php echo $cater['description']; ?></h3>
         <div style="color:#27ae60; font-size:18px;">₱<?php echo $cater['price']; ?></div>
         <input type="hidden" name="product_name" value="<?php echo $cater['name']; ?>">
         <input type="hidden" name="product_price" value="<?php echo $cater['price']; ?>">
         <input type="hidden" name="product_image" value="<?php echo $cater['image']; ?>">
         <input type="hidden" name="cart_type" value="cater">
         <input type="submit" name="add_to_cart" value="Add to Cart" style="background:#f39c12; color:white; padding:10px 20px; border:none; border-radius:5px; margin-top:10px;">
      </form>
      <?php 
         }
      } else {
         echo "<p style='text-align:center; font-size:18px;'>No products found.</p>";
      }
      ?>
   </div>
</main>

</body>
</html>
