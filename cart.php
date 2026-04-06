<?php
session_start();
require_once("connect.php");

// redirect guests to login - cart is for logged in users only
if (!isset($_SESSION["logged-in"]) || $_SESSION["logged-in"] != true) {
    header("Location: login.php");
    exit();
}

// add to basket - store the product id in a cookie when the form is submitted
// https://www.w3schools.com/php/php_cookies.asp
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["product_id"])) {
    $product_id = $_POST["product_id"];

    // save the product id in a cookie for 1 hour
    setcookie("cart_item", $product_id, time() + 3600, "/");

    // reload the page so the cookie shows up straight away
    header("Location: cart.php");
    exit();
}

// checkout - runs when the checkout button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["checkout"])) {
    if (isset($_COOKIE["cart_item"])) {

        $user_id     = $_SESSION["user_id"];   // logged in user id from session
        $product_ids = $_COOKIE["cart_item"];  // product id from the cookie

        // used a prepared statement here - same as register.php
        $stmt = $conn->prepare("INSERT INTO tbl_orders (user_id, product_ids) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $product_ids);
        $stmt->execute();

        // check the order was saved using insert_id
        $lastId = $conn->insert_id;
        if ($lastId) {
            // clear the cookie so the cart is empty after checkout
            setcookie("cart_item", "", time() - 3600, "/");
            unset($_COOKIE["cart_item"]);
            $success = true;
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}

// read the cookie and get the matching product from the database
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

// get the current offers from tbl_offers to show in the cart
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

            <h1>Shopping Cart</h1>

            <!-- show success alert after checkout -->
            <?php if (isset($success) && $success) { ?>
                <script>alert('Your order has been successfully placed.');</script>
            <?php } ?>

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

            <!-- show cart contents -->
            <?php if ($cartProduct) { ?>

                <!-- personalised message with the users name -->
                <p>Welcome <?php echo $_SESSION["user_name"]; ?>, the item you have added to your shopping cart is:</p>

                <!-- table layout matching the example -->
                <table>
                    <tr>
                        <th>Item</th>
                        <th>Product</th>
                        <th>Price</th>
                    </tr>
                    <tr>
                        <td><img src="<?php echo $cartProduct["product_src"]; ?>"
                                 alt="<?php echo $cartProduct["product_title"]; ?>"
                                 style="width:80px;"></td>
                        <td><?php echo $cartProduct["product_title"]; ?></td>
                        <td>£<?php echo $cartProduct["product_price"]; ?></td>
                    </tr>
                </table>

                <!-- checkout form - submits order to tbl_orders -->
                <form method="POST">
                    <input type="hidden" name="checkout" value="1">
                    <button type="submit">Checkout</button>
                </form>

            <?php } else { ?>
                <!-- empty cart message with link to products -->
                <p><?php echo $_SESSION["user_name"]; ?> there are no items in your shopping cart.
                   Please add items from our current <a href="products.php">product list</a>.</p>
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
