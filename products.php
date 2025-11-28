<?php
include 'db.php'; // your database connection file

// Fetch categories with products
$sql = "
  SELECT c.id AS category_id, c.name AS category_name,
         p.id AS product_id, p.name AS product_name,
         p.description, p.price, p.image
  FROM categories c
  LEFT JOIN products p ON c.id = p.category_id
  ORDER BY c.id, p.id
";

$result = $conn->query($sql);

$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[$row['category_name']][] = $row;
}
?>

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    $isLoggedIn = false;
} else {
    $isLoggedIn = true;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BUT FIRST, COFFEE | Products</title>
  <link rel="stylesheet" href="products.css">
  <link rel="stylesheet" href="login-signup_Modal.css">
  
</head>

<body>

<!-- Navigation Bar -->
  <nav class="navbar">
    <div class="logo">
      <a href="index.php"><img src="image/OIP.jpg" alt="Logo"></a>
    </div>
    <div class="nav-links">
      <a href="index.php">Home</a>
      <a href="products.php" class="active">Products</a>
      <a href="index.php #about">About Us</a>
      <a href="index.php #contact">Contact</a>
      <a href="#" id="openLogin">Login</a>
    </div>
  </nav>

  <!-- Page Title -->
 <header class="page-header">
  <h1>Products</h1>
  </header>
                                                   <!-- REtrive data from database -->

  <!-- Main Content -->
  <div class="content-wrapper">
    <!-- Product Grid -->
    <section class="product-grid">
      <div class="product-section">
        <?php foreach ($categories as $categoryName => $products): ?>
            <div class="category-block">
                <h2 class="category-title"><?= $categoryName ?></h2>
                <div class="category-products">

                    <?php foreach ($products as $product): ?>
                        <a href="#<?= $product['product_id'] ?>" class="product-box">
                            <img src="<?= $product['image'] ?>" alt="<?= $product['product_name'] ?>">
                            <h3><?= $product['product_name'] ?></h3>
                            <h4><?= $product['price'] ?></h4>                            
                        </a>
                    <?php endforeach; ?>

                </div>
            </div>
        <?php endforeach; ?>
      </div>
    </section>


    <!-- Product Details (shown when clicked) -->
      <section class="product-details">
        <?php foreach ($categories as $categoryName => $products): ?>
          <?php foreach ($products as $product): ?>
              <div id="<?= $product['product_id'] ?>" class="details-box">
                  <button class="close-details" style="position: absolute; top:5px; right: 5px;">✖</button>
                  <img src="<?= $product['image'] ?>" alt="<?= $product['product_name'] ?>">
                  <h2><?= $product['product_name'] ?></h2>
                  <p><?= $product['description'] ?></p>
                  <button class="add-to-cart"
                          data-name="<?= $product['product_name'] ?>"
                          data-price="<?= $product['price'] ?>">
                      Add to Cart
                  </button>
              </div>
          <?php endforeach; ?>
        <?php endforeach; ?>
      </section>

    <div class="cart">
      <h2>🛒 Your Cart</h2>
      <ul class="cart-items"></ul>
      <p>Total: <span class="total-price">₱0</span></p>
      <button class="checkout-btn">Checkout</button>
      <button class="close-cart">Close</button>
    </div>

    <!-- Checkout Modal -->
  <div class="checkout-modal" id="checkoutModal">
    <div class="checkout-content">
      <span class="close-checkout">&times;</span>
      <h2>Checkout</h2>

      <form id="checkoutForm">
        <h3>Customer Information</h3>
        <label for="customerName">Name:</label>
        <input type="text" id="customerName" name="customerName" required>

        <label for="customerAddress">Address:</label>
        <input type="text" id="customerAddress" name="customerAddress" required>

        <label for="customerPhone">Phone:</label>
        <input type="text" id="customerPhone" name="customerPhone" required>

        <h3>Order Summary</h3>
        <ul class="checkout-items"></ul>
        <p>Total: <span class="checkout-total">₱0.00</span></p>

        <h3>Payment Method</h3>
        <select id="paymentMethod" name="paymentMethod" required>
          <option value="">Select Payment</option>
          <option value="cash">Cash</option>
          <option value="gcash">GCash</option>
          <option value="credit">Credit Card</option>
        </select>

        <button type="submit" class="checkout-submit">Confirm & Pay</button>
      </form>
    </div>
  </div>

  <!-- Order Complete Notification -->
  <div id="orderNotification" class="order-notification">
    ✅ Order Complete! Thank you for your purchase.
  </div>

  <button class="open-cart">🛒 View Cart</button>
    

  </div>
   <!-- LOGIN MODAL -->
      <div class="modal" id="loginModal">
        <div class="modal-content">
            <span class="close" data-close="loginModal">&times;</span>
            <h2>Login</h2>

            <form action="auth.php" method="POST" class="modal-form">
            <input type="hidden" name="action" value="login">
            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit" class="modal-btn">Login</button>

            <p class="switch-text">
                Don't have an account? 
                <a href="#" id="openSignupFromLogin">Sign Up</a>
            </p>
            </form>
        </div>
      </div>

      <!-- SIGNUP MODAL -->
      <div class="modal" id="signupModal">
        <div class="modal-content">
            <span class="close" data-close="signupModal">&times;</span>
            <h2>Create Account</h2>

            <form id="signupForm" action="auth.php" method="POST" class="modal-form">
              <input type="hidden" name="action" value="register">

                <label>Username</label>
                <input type="text" name="username" placeholder="Enter your username" required>

                <label>Contact Number</label>
                <input type="text" id="contactNumber" name="contact" maxlength="11" 
                      placeholder="11-digit mobile number (e.g., 09123456789)" required>
                <small id="contactError" style="color:red; display:none;">Contact number must be exactly 11 digits.</small>

                <label>Address</label>
                <input type="text" id="addressField" name="address" 
                      placeholder="House No., Street, Barangay, City" required>
                <small id="addressError" style="color:red; display:none;">
                    Address can only contain letters, numbers, spaces, commas, periods, and hyphens.
                </small>

                <label>Email</label>
                <input type="email" name="email" placeholder="example@email.com" required>

                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>

                <button type="submit" class="modal-btn">Sign Up</button>

                <p class="switch-text">
                    Already have an account? 
                    <a href="#" id="openLoginFromSignup">Login</a>
                </p>
            </form>
        </div>
      </div>

  <!-- Footer -->
  <footer>
    <p>© 2025 CHOCOCRAVE | Indulge in Sweet Cravings</p>
  </footer>

  
  <script>
    const IS_LOGGED_IN = <?= $isLoggedIn ? 'true' : 'false' ?>;
  </script>
  
  <script src="login-signup_Modal.js"></script>
  <script src="script.js"></script>




</body>
</html>
