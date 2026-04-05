<?php
session_start();
require_once("connect.php");

// redirect guests to login - cart is for logged in users only
if (!isset($_SESSION["logged-in"]) || $_SESSION["logged-in"] != true) {
    header("Location: login.php");
    exit();
}

// if "Add to Basket" was submitted, save the product id in a cookie
// https://www.w3schools.com/php/php_cookies.asp
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["product_id"])) {
    $product_id = $_POST["product_id"];

    // setcookie stores the value in the browser for 1 hour
    setcookie("cart_item", $product_id, time() + 3600, "/");

    // redirect back to cart so the cookie is available straight away
    header("Location: cart.php");
    exit();
}

// if checkout button was clicked, insert order into the database
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["checkout"])) {
    if (isset($_COOKIE["cart_item"])) {

        $user_id     = $_SESSION["user_id"];   // get logged in user id from session
        $product_ids = $_COOKIE["cart_item"];  // get product id from cookie

        // prepared statement to safely insert the order - same technique as register.php
        $stmt = $conn->prepare("INSERT INTO tbl_orders (user_id, product_ids) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $product_ids);
        $stmt->execute();

        // check if order was created successfully using insert_id
        $lastId = $conn->insert_id;
        if ($lastId) {
            $success = "Thank you for your order! Your order has been placed successfully.";
            // clear the cookie after checkout so cart is empty
            setcookie("cart_item", "", time() - 3600, "/");
            unset($_COOKIE["cart_item"]);
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}

// read the cookie and get the product details from the database
// https://www.w3schools.com/php/php_cookies.asp
$cartProduct = null;
if (isset($_COOKIE["cart_item"])) {
    $product_id = $_COOKIE["cart_item"];
    $sql = "SELECT * FROM tbl_products WHERE product_id = $product_id";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $cartProduct = $result->fetch_assoc();
    }
}

// get current offers from the database to display in the cart
$offersSql = "SELECT * FROM tbl_offers";
$offersResult = $conn->query($offersSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Shop - Cart</title>
</head>

<body>
    <header>
        <!-- Desktop Header -->
        <div class="desktopHeader">
            <div class="brand">
                <img src="images/logo_reverse.png" alt="University of Central Lancashire logo">
                <span class="brand-text">Student Shop</span>
            </div>

            <nav>
                <ul class="myNav">
                    <li class="navList"><a href="index.php">Home</a></li>
                    <li class="navList"><a href="products.php">Products</a></li>
                    <li class="navList"><a href="cart.php">Cart</a></li>
                    <?php if (isset($_SESSION["logged-in"]) && $_SESSION["logged-in"] == true) { ?>
                        <li class="navList"><a href="logout.php">Logout</a></li>
                    <?php } else { ?>
                        <li class="navList"><a href="login.php">Login</a></li>
                    <?php } ?>
                </ul>
            </nav>
        </div>

        <!-- Mobile Header -->
        <div class="topnav">
            <div class="brand">
                <img src="images/logo_reverse.png" alt="University of Central Lancashire logo">
                <span class="brand-text">Student Shop</span>
            </div>
            <div id="myLinks">
                <a href="index.php">Home</a>
                <a href="products.php">Products</a>
                <a href="cart.php">Cart</a>
                <?php if (isset($_SESSION["logged-in"]) && $_SESSION["logged-in"] == true) { ?>
                    <a href="logout.php">Logout</a>
                <?php } else { ?>
                    <a href="login.php">Login</a>
                <?php } ?>
            </div>
            <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                <i class="fa fa-bars"></i>
            </a>
        </div>
    </header>

    <main>
        <div class="main">

            <h1>Your Cart</h1>

            <!-- show success message after checkout -->
            <?php if (isset($success)) echo "<p class='formSuccess'>$success</p>"; ?>

            <!-- show error if checkout failed -->
            <?php if (isset($error)) echo "<p class='formError'>$error</p>"; ?>

            <!-- show current offers from tbl_offers -->
            <?php if ($offersResult && $offersResult->num_rows > 0) { ?>
                <h2>Current Offers</h2>
                <div id="offerList">
                    <?php while ($offer = $offersResult->fetch_assoc()) { ?>
                        <div class="offerCard">
                            <h3><?php echo $offer["offer_title"]; ?></h3>
                            <p><?php echo $offer["offer_desc"]; ?></p>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

            <!-- show the item stored in the cookie -->
            <?php if ($cartProduct) { ?>

                <h2>Item in Your Cart</h2>

                <div class="reviewCard">
                    <img src="<?php echo $cartProduct["product_src"]; ?>"
                         alt="<?php echo $cartProduct["product_title"]; ?>"
                         style="width:150px;">
                    <h3><?php echo $cartProduct["product_title"]; ?></h3>
                    <p>Price: <strong>£<?php echo $cartProduct["product_price"]; ?></strong></p>
                    <p><?php echo $cartProduct["product_desc"]; ?></p>
                </div>

                <!-- checkout form - submits order to tbl_orders -->
                <form method="POST">
                    <input type="hidden" name="checkout" value="1">
                    <button type="submit">Checkout</button>
                </form>

            <?php } elseif (!isset($success)) { ?>
                <!-- only show empty message if there is no success message showing -->
                <p>Your cart is empty. <a href="products.php">Browse products</a></p>
            <?php } ?>

        </div>
    </main>

    <footer>
        <div class="footer-container">

            <div class="footer-section">
                <h3>Links</h3>
                <p><a href="https://www.uclansu.co.uk">Students' Union</a></p>
            </div>

            <div class="footer-section">
                <h3>Contact</h3>
                <p>Email: suinformation@uclan.ac.uk</p>
                <p>Phone: 01772 201201</p>
            </div>

            <div class="footer-section">
                <h3>Location</h3>
                <p>University of Lancashire Students' Union</p>
                <p>Fylde Road, Preston, PR1 7BY</p>
            </div>

        </div>
    </footer>

    <script>
        // mobile nav toggle
        function myFunction() {
            var x = document.getElementById("myLinks");
            if (x.style.display === "block") {
                x.style.display = "none";
            } else {
                x.style.display = "block";
            }
        }
    </script>
</body>
</html>
