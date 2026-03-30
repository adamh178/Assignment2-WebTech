<?php
session_start();
require_once("connect.php");

// check that an id was passed in the URL e.g. item.php?id=3
if (!isset($_GET['id'])) {
    die("No product selected");
}

// get the id from the URL
$id = $_GET['id'];

// SQL query to find the product that matches the id
$sql = "SELECT * FROM tbl_products WHERE product_id = $id";

// run the query
$result = $conn->query($sql);

// if nothing came back, stop the page
if (!$result || $result->num_rows == 0) {
    die("Product not found");
}

// fetch the product as an associative array
$row = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" type="text/css" href="main.css"> <!-- link to the stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Shop - Item</title>
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
                </ul>
            </nav>
        </div>

        <!-- Mobile Header -->
        <div class="topnav">
            <div class="brand">
                <img src="images/logo_reverse.png" alt="University of Central Lancashire logo">
                <span class="brand-text">Student Shop</span>
            </div>
            <!-- links that show/hide on mobile -->
            <div id="myLinks">
                <a href="index.php">Home</a>
                <a href="products.php">Products</a>
                <a href="cart.php">Cart</a>
            </div>
            <!-- "Hamburger menu" to toggle the navigation links -->
            <!-- https://www.w3schools.com/howto/howto_js_mobile_navbar.asp -->
            <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                <i class="fa fa-bars"></i>
            </a>
        </div>
    </header>


    <main class="mainItemPage">
        <!-- display product details from the database row -->

        <!-- product title -->
        <h1 id="itemName"><?php echo $row['product_title']; ?></h1>

        <!-- product image using the path stored in the database -->
        <img id="itemImage"
            src="<?php echo $row['product_src']; ?>"
            alt="<?php echo $row['product_title']; ?>">

        <!-- price, stock, and description -->
        <p id="itemPrice">£<?php echo $row['product_price']; ?></p>
        <p id="itemStock"><?php echo $row['product_stock']; ?></p>
        <p id="itemDescription"><?php echo $row['product_desc']; ?></p>
        
        <p><button id="addToCartBtn">Add to Cart</button></p>

        <a href="products.php" class="itemBackLink">Back to products</a>

    </main>

    <!-- used this as a hidden header -->
    <h2 hidden>This header should be hidden.</h2>
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

</body>
</html>
