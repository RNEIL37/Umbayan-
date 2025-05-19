<?php
@include 'config.php';

$order_success = false;
$order_details = '';

if (isset($_POST['order_btn'])) {
    $name = $_POST['name'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $method = $_POST['method'];
    $purok = $_POST['purok'];
    $barangay = $_POST['barangay'];
    $municipal = $_POST['municipal'];
    $province = $_POST['province'];
    $country = $_POST['country'];
    $pin_code = $_POST['pin_code'];

    $cart_query = mysqli_query($conn, "SELECT * FROM `cart`");
    $price_total = 0;
    $product_name = [];

    if (mysqli_num_rows($cart_query) > 0) {
        while ($product_item = mysqli_fetch_assoc($cart_query)) {
            $product_name[] = $product_item['name'] . ' (' . $product_item['quantity'] . ')';
            $product_price = $product_item['price'] * $product_item['quantity'];
            $price_total += $product_price;
        }

        $total_products = implode(', ', $product_name);
        $detail_query = mysqli_query($conn, "INSERT INTO `orders`(name, number, email, method, purok, barangay, municipal, province, country, pin_code, total_products, total_price, status) VALUES('$name','$number','$email','$method','$purok','$barangay','$municipal','$province','$country','$pin_code','$total_products','$price_total','PENDING')") or die('query failed');

        if ($detail_query) {
            $order_success = true;
            $order_details = "
                <h5 class='text-center mb-3'>Thank you for shopping!</h5>
                <p><strong>Order:</strong> $total_products</p>
                <p><strong>Total:</strong> ₱$price_total</p>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Number:</strong> $number</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Address:</strong> $purok, $barangay, $municipal, $province, $country - $pin_code</p>
                <p><strong>Payment Method:</strong> $method</p>
                <p class='text-muted'>*Pay when product arrives*</p>
                <div class='text-center'>
                    <a href='products.php' class='btn btn-success'>Continue Shopping</a>
                </div>
            ";
        }
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
    <div class="container my-5">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="text-center mb-4">Complete Your Order</h2>
                <form method="post">
                    <div class="row g-3">
                        <?php
                        $fields = [
                            ['name', 'Your Full Name', 'text'],
                            ['number', 'Your Number', 'number'],
                            ['email', 'Your Email', 'email'],
                            ['purok', 'Purok', 'text'],
                            ['barangay', 'Barangay', 'text'],
                            ['municipal', 'Municipal', 'text'],
                            ['province', 'Province', 'text'],
                            ['country', 'Country', 'text'],
                            ['pin_code', 'Pin Code', 'text'],
                        ];
                        foreach ($fields as [$id, $label, $type]) {
                            echo "<div class='col-md-6'>
                                    <label for='$id' class='form-label'>$label</label>
                                    <input type='$type' id='$id' name='$id' class='form-control' required>
                                  </div>";
                        }
                        ?>
                        <div class="col-md-6">
                            <label for="method" class="form-label">Payment Method</label>
                            <select name="method" id="method" class="form-select">
                                <option value="cash on delivery" selected>Cash on Delivery</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <input type="submit" name="order_btn" value="Order Now" class="btn btn-primary px-5">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Modal -->
    <?php if ($order_success): ?>
    <div class="modal fade show" style="display:block; background:rgba(0,0,0,0.5);" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title">Order Confirmation</h5>
                    <button type="button" class="btn-close" onclick="document.querySelector('.modal').style.display='none'"></button>
                </div>
                <div class="modal-body">
                    <?= $order_details ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
