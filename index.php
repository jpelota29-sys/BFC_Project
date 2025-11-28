<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Butfirst, Coffee | Your Greater Start</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="login-signup_Modal.css">

    <style>
        /* ==========================================================
        PRODUCTS (HOME PAGE)
        ========================================================== */
        .products {
        text-align: center;
        padding: 60px 40px;
        }

        .products h2 {
        font-size: 36px;
        color: #4b2e05;
        margin-bottom: 30px;
        }

        .product-cards {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 25px;
        }

        .card {
        width: 280px;
        border-radius: 12px;
        overflow: hidden;
        background-color: #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.25);
        }

        .card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        }

        .card h3 {
        color: #5c3317;
        margin: 15px 0 5px;
        }

        .card p {
        color: #555;
        padding: 0 15px 15px;
        font-size: 15px;
        }

    </style>
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

    <!-- Welcome Section -->
    <section class="welcome-section" id="home">
        <h1>BUT FIRST, COFFEE</h1>
        <p>“Your Greater Start”</p>
        <a href="products.php" class="shop-btn">Order Now</a>
    </section>

    <!-- Products Section -->
    <section class="products" id="products">
        <h2>Our Bestsellers</h2>
        <div class="product-cards">

            <div class="card">
                <img src="finish_product/Spanish Latte.jpg" alt="Spanish-Latte">
                <h3>Spanish Latte</h3>
                <p>Where bold espresso meets creamy condensed milk—every sip feels like a hug.</p>
            </div>

            <div class="card">
                <img src="finish_product/Black forest.jpg" alt="Black Forest Cake">
                <h3>Black Forest Cake</h3>
                <p>Layers of chocolatey goodness with a cherry on top.</p>
            </div>

            <div class="card">
                <img src="finish_product/Auro.jpg" alt="Auro choco Butter-Scotch">
                <h3>Auro Choco Butter-Scotch</h3>
                <p>Sweet, creamy, and oh-so-decadent! Chocolate and butterscotch in perfect harmony..</p>
            </div>

        </div>
    </section>
    

    <!-- About Section -->
    <section class="about" id="about">
        <h2>About But First, Coffee</h2>
        <p>But First, Coffee – Canlubang, we believe every great day starts with a cup of coffee. As part of the well-loved Filipino brand But First, Coffee, our mission is simple: serve high-quality, café-style coffee at prices everyone can enjoy.</p>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <h2>Contact Us</h2>
        <p>📍 Located in Canlubang beside unioil, Calamba City, Laguna</p>
        <p>📞 09090338676 | ButfirstCanlubang@gmail.com</p>
        <p>Follow us on 
            <a href="https://www.facebook.com/butfirstcoffeecanlubang">Facebook</a> | 
            <a href="https://www.instagram.com/butfirstcoffeecanlubang/">Instagram</a>
        </p>
    </section>

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
        <p>© 2025 BUTFIRST, COFFEE | YOUR GREATER START</p>
    </footer>

    <script src="Toggle.js"></script>
    <script src="login-signup_Modal.js"></script>
</body>
</html>
