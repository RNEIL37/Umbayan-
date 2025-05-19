<?php

@include 'config.php';

// Update quantity
if(isset($_POST['update_update_btn'])){
   $update_value = $_POST['update_quantity'];
   $update_id = $_POST['update_quantity_id'];
   mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_value' WHERE id = '$update_id'");
   header('location:cart.php');
}

// Remove item
if(isset($_GET['remove'])){
   $remove_id = $_GET['remove'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'");
   header('location:cart.php');
}

// Delete all
if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `cart`");
   header('location:cart.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Shopping Cart</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <style>
      body {
         margin: 0;
         font-family: 'Segoe UI', sans-serif;
         background: #f5f5f5;
         color: #333;
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
<body>

<header>
   <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap;">
      <div style="font-size:1.5em; font-weight:bold;">FOOD ORDERING AND CATERING SYSTEM</div>
      <nav>
         <a href="products.php">Menu</a>
         <a href="products2.php">Catering Menu</a>
         <a href="cart.php">Cart 
         </a>
         <a href="user_notif.php">Notifications</a>
         <a href="logout.php" class="logout">Logout</a>
      </nav>
   </div>
</header>

<main style="padding:40px;">
   <h1 style="text-align:center; margin-bottom:40px;">Food Ordering Cart</h1>

   <div class="shopping-cart">

      <table>
   <thead>
      <tr>
         <th>Image</th>
         <th>Name</th>
         <th>Price</th>
         <th>Quantity</th>
         <th>Total</th>
         <th>User Form ID</th>  <!-- New column -->
         <th>Action</th>
      </tr>
   </thead>
   <tbody>
      <?php 
      $select_cart = mysqli_query($conn, "SELECT * FROM cart");
      $grand_total = 0;

      if(mysqli_num_rows($select_cart) > 0){
         while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
            $grand_total += $sub_total;
      ?>
      <tr>
         <td><img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt=""></td>
         <td><?php echo $fetch_cart['name']; ?></td>
         <td>₱<?php echo $fetch_cart['price']; ?></td>
         <td>
            <form method="post" action="">
               <input type="hidden" name="update_quantity_id" value="<?php echo $fetch_cart['id']; ?>">
               <input type="number" name="update_quantity" min="1" value="<?php echo $fetch_cart['quantity']; ?>" style="width:60px;">
               <input type="submit" name="update_update_btn" value="Update" style="padding:5px 10px;">
            </form>
         </td>
         <td>₱<?php echo $sub_total; ?></td>
         <td><?php echo $fetch_cart['id']; ?></td>  <!-- Display user_form id here -->
         <td>
            <a href="cart.php?remove=<?php echo $fetch_cart['id']; ?>" onclick="return confirm('Remove item from cart?');" class="delete-btn">
               <i class="fas fa-trash"></i> Remove
            </a>
         </td>
      </tr>
      <?php
         }
      } else {
         echo '<tr><td colspan="7">No items in your cart</td></tr>';
      }
      ?>
      <tr>
         <td><a href="products.php" class="option-btn">Continue Shopping</a></td>
         <td colspan="4"><strong>Grand Total:</strong></td>
         <td><strong>₱<?php echo $grand_total; ?></strong></td>
         <td>
            <a href="cart.php?delete_all" onclick="return confirm('Are you sure you want to delete all?');" class="delete-btn">
               <i class="fas fa-trash"></i> Delete All
            </a>
         </td>
      </tr>
   </tbody>
</table>


      <div class="checkout-btn" style="text-align:right; margin-top:20px;">
         <a href="cart1.php" class="option-btn <?= ($grand_total > 0) ? '' : 'disabled'; ?>">back</a>
         <a href="checkout.php" class="option-btn <?= ($grand_total > 0) ? '' : 'disabled'; ?>">Proceed to Checkout</a>
      </div>

   </div>
</main>

</body>
</html>
