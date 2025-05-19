<?php
@include 'config.php';

// Update quantity
if (isset($_POST['update_update_btn'])) {
    $update_value = $_POST['update_quantity'];
    $update_id = $_POST['update_quantity_id'];
    $update_quantity_query = mysqli_query($conn, "UPDATE `cart_cater` SET quantity = '$update_value' WHERE id = '$update_id'");
    if ($update_quantity_query) {
        header('location:cart1.php');
    };
}

// Remove item
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM `cart_cater` WHERE id = '$remove_id'");
    header('location:cart1.php');
};

// Delete all
if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart_cater`");
    header('location:cart1.php');
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Shopping Cart</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <style>
      html, body {
         height: 100%;
         margin: 0;
         display: flex;
         flex-direction: column;
         font-family: 'Segoe UI', sans-serif;
         background: #f5f5f5;
         color: #333;
      }
      .wrapper {
         flex: 1;
      }
      table {
         width: 100%;
         border-collapse: collapse;
         background: #fff;
         box-shadow: 0 0 10px rgba(0,0,0,0.05);
      }
      th, td {
         padding: 15px;
         text-align: center;
         border-bottom: 1px solid #ddd;
      }
      th {
         background-color: #2c3e50;
         color: white;
      }
      img {
         max-width: 80px;
         height: auto;
      }
      .checkout-btn a,
      .option-btn,
      .delete-btn {
         display: inline-block;
         padding: 8px 16px;
         color: #fff;
         text-decoration: none;
         border-radius: 5px;
         margin: 5px;
      }
      .option-btn { background: #3498db; }
      .delete-btn { background: #e74c3c; }
      .btn.disabled {
         background: #aaa;
         pointer-events: none;
      }
      header {
         background: #2c3e50;
         color: #fff;
         padding: 20px 40px;
      }
      header nav a {
         color: #ecf0f1;
         text-decoration: none;
         margin: 0 10px;
      }
      header nav a.logout {
         background: #e74c3c;
         padding: 6px 12px;
         border-radius: 5px;
      }
   </style>
</head>
<body style="margin:0; background: url('images/fd.jpg') no-repeat center center fixed; background-size: cover; color:#333;">

<header>
   <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap;">
      <div style="font-size:1.5em; font-weight:bold;">FOOD ORDERING AND CATERING SYSTEM</div>
      <nav>
         <a href="products.php">Menu</a>
         <a href="products2.php">Catering Menu</a>
         <a href="cart1.php">Cart</a>
         <a href="user_notif.php">Notifications</a>

         <a href="logout.php" class="logout">Logout</a>
      </nav>
   </div>
</header>

<!-- Page Content Wrapper -->
<div class="wrapper">
   <br><br><br><br><br><br><br><br><br>
   <div style="font-size:3em; font-weight:bold;text-align: center;"> YOUR CART </div>

   <!-- Centered Buttons -->
   <div style="text-align: center; margin-top: 100px;">
      <a href="cart.php" style="
         display: inline-block;
         background-color: #0056b3;
         color: #ffffff;
         padding: 12px 28px;
         text-decoration: none;
         font-size: 16px;
         font-weight: 600;
         border-radius: 5px;
         box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
         transition: background-color 0.3s ease;">food ordered
         <span style="background:red; color:white; padding:2px 8px; border-radius:10px;">
            <?php 
            $select_rows = mysqli_query($conn, "SELECT * FROM `cart`") or die('query failed');
            echo mysqli_num_rows($select_rows); 
            ?>
         </span>
      </a>

      <a href="cart2.php" style="
         display: inline-block;
         background-color: #6c757d;
         color: #ffffff;
         padding: 12px 28px;
         text-decoration: none;
         font-size: 16px;
         font-weight: 600;
         border-radius: 5px;
         margin-left: 20px;
         box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
         transition: background-color 0.3s ease;">
         catering ordered
         <span style="background:red; color:white; padding:2px 8px; border-radius:10px;">
            <?php
               $rows = mysqli_query($conn, "SELECT * FROM `cart_cater`");
               echo mysqli_num_rows($rows);
            ?>
         </span>
      </a>
   </div>
</div>

<!-- Sticky Footer -->
<footer style="background-color: #2c3e50; color: #ecf0f1; padding: 30px 20px; text-align: center; font-size: 15px; border-top: 4px solid #27ae60;">
   <div style="max-width: 1000px; margin: auto;">
      <p style="margin: 5px 0;">📧 Email: 
         <a href="mailto:kylenoelcalamba@gmail.com" style="color: #1abc9c; text-decoration: none;">kylenoelcalamba@gmail.com</a>
      </p>
      <p style="margin: 5px 0;">📞 Phone: 
         <a href="tel:09261536225" style="color: #1abc9c; text-decoration: none;">0926-153-6225</a>
      </p>
      <hr style="border: 0; border-top: 1px solid #7f8c8d; margin: 20px auto; width: 60%;">
      <p style="margin: 0;">&copy; 2027 <strong>Food Ordering and Catering System</strong> — All Rights Reserved</p>
   </div>
</footer>

</body>
</html>
