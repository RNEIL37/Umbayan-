<?php
@include 'config.php';

if (isset($_POST['order_btn'])) {
    $full_name = $_POST['full_name'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $loc_ven = $_POST['loc_ven'];
    $full_address = $_POST['full_address'];
    $date = $_POST['date'];
    $payment = $_POST['payment'];

    $cart_query = mysqli_query($conn, "SELECT * FROM `cart_cater`");
    $price_total = 0;
    $total_product = '';

    if (mysqli_num_rows($cart_query) > 0) {
        while ($product_item = mysqli_fetch_assoc($cart_query)) {
            $product_name = $product_item['name'] . ' (' . $product_item['quantity'] . ')';
            $total_product .= $product_name . ', ';
            $product_price = $product_item['price'] * $product_item['quantity'];
            $price_total += $product_price;
        }
    }

    $detail_query = mysqli_query($conn, "INSERT INTO order_cater(full_name,number1,email,loc_ven,full_address,date1,payment,total_price,status) VALUES('$full_name','$number','$email','$loc_ven','$full_address','$date','$payment','$price_total','PENDING')");

    if ($cart_query && $detail_query) {
        echo "
        <div class='modal fade show d-block' tabindex='-1' style='background-color: rgba(0,0,0,0.6);'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title'>Thank You for Your Order!</h5>
                        <button type='button' class='btn-close' onclick=\"this.closest('.modal').classList.remove('d-block');\" aria-label='Close'></button>
                    </div>
                    <div class='modal-body'>
                        <p><strong>Order Summary:</strong> $total_product</p>
                        <p><strong>Total:</strong> ₱$price_total</p>
                        <p><strong>Name:</strong> $full_name</p>
                        <p><strong>Number:</strong> $number</p>
                        <p><strong>Email:</strong> $email</p>
                        <p><strong>Payment Mode:</strong> $payment</p>
                        <small class='text-muted'>*Pay when product arrives*</small>
                    </div>
                    <div class='modal-footer'>
                        <a href='products.php' class='btn btn-success'>Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<header style="background:#2c3e50; color:#fff; padding:20px 40px;">
   <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap;">
      <div style="font-size:1.5em; font-weight:bold;">FOOD ORDERING AND CATERING SYSTEM</div>
      <nav style="display:flex; gap:20px; flex-wrap:wrap;">
         <a href="products.php" style="color:#ecf0f1; text-decoration:none;">Menu</a>
         <a href="products2.php" style="color:#ecf0f1; text-decoration:none;">Catering Menu</a>
         <a href="cart1.php" style="color:#ecf0f1; text-decoration:none;">Cart</a>
         <a href="user_notif.php" style="color:#ecf0f1; text-decoration:none;">Notifications 
            <span style="background:red; color:white; padding:2px 8px; border-radius:10px;">
               <?php 
               $select_rows = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
               echo mysqli_num_rows($select_rows); 
               ?>
            </span>
         </a>
         <a href="logout.php" style="background:#e74c3c; padding:6px 12px; color:white; border-radius:5px; text-decoration:none;">Logout</a>
      </nav>
   </div>
</header>

<main class="container my-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="text-center mb-4">Complete Your Order</h4>
            <form method="post">
                <?php
                $select_cart = mysqli_query($conn, "SELECT * FROM `cart_cater`");
                $total = 0;
                echo "<div class='mb-3 p-3 bg-light rounded'>";
                if (mysqli_num_rows($select_cart) > 0) {
                    while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                        $total_price = $fetch_cart['price'] * $fetch_cart['quantity'];
                        $total += $total_price;
                        echo "<p>{$fetch_cart['name']} (x{$fetch_cart['quantity']})</p>";
                    }
                } else {
                    echo "<p>Your cart is empty!</p>";
                }
                echo "<strong>Total: ₱$total</strong></div>";
                ?>

                <div class="mb-3">
                    <input type="text" name="full_name" class="form-control" placeholder="Your Name" required>
                </div>
                <div class="mb-3">
                    <input type="number" name="number" class="form-control" placeholder="Your Number" required>
                </div>
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="loc_ven" class="form-control" placeholder="Catering Location" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="full_address" class="form-control" placeholder="Full Address" required>
                </div>
                <div class="mb-3">
                    <input type="date" name="date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <select name="payment" class="form-select">
                        <option value="cash on delivery">Cash on Delivery</option>
                    </select>
                </div>
                <div class="d-grid">
                    <input type="submit" name="order_btn" value="Order Now" class="btn btn-success">
                </div>
            </form>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
