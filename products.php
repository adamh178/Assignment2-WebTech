<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once("connect.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" type="text/css" href="style.css"> <!-- link to the stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Student Union Shop - Products</title>
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

    <main>
        <!-- https://www.w3schools.com/howto/howto_css_product_card.asp -->
        <div class="main">
            <!-- breadcrumb navigation -->
            <!-- https://www.w3schools.com/howto/howto_css_breadcrumbs.asp -->
            <!-- <ul class="breadcrumbs">
                <li><a href="index.html">Home</a></li>
                <li><a href="products.html">Products</a></li>
                <li>Item</li>
            </ul> -->

            <div class="pageTitle">
                <h1>Legacy T-Shirts</h1>
                <p>Old UCLan merchandise at discounted prices.</p>
            </div>

            <!-- filter stock -->
            <!-- <div class="filterButtons">
                <button onclick="filterSelection('all')">Show all</button>
                <button onclick="filterSelection('in')">In stock only</button>
            </div> -->

            <div id="filterButtons">
                <button class="btn" onclick="filterProducts('all')">Show All</button>
                <button class="btn" onclick="filterProducts('inStock')">In Stock</button>
            </div>

            <!-- This is where we will output the list of products -->
            <!-- Product list container -->
            <ul id="productList">

            <?php

            $sql = "SELECT * FROM tbl_products";
            $result = $conn->query($sql);

            if (!$result) {
                die("Query failed: " . $conn->error);
            }

            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {

                    $id = $row["product_id"];
                    $title = $row["product_title"];
                    $price = $row["product_price"];
                    $stock = $row["product_stock"];
                    $image = $row["product_src"];
                    $desc = $row["product_desc"];
            ?>

                <?php
                // convert database stock into filter class (my logic)
                if ($stock == "good-stock" || $stock == "low-stock") {
                    $stockClass = "inStock";   // anything available
                } else {
                    $stockClass = "outStock";  // not available
                }
                ?>

                <li class="productCard show <?php echo $stockClass; ?>">

                    <!-- using image path stored in database -->
                    <img class="itemImage" src="<?php echo $image; ?>" alt="<?php echo $title; ?>">

                    <div class="itemInfo">

                        <!-- product title -->
                        <h2 class="itemName"><?php echo $title; ?></h2>

                        <!-- price -->
                        <p class="itemPrice">£<?php echo $price; ?></p>

                        <!-- stock status -->
                        <p class="itemStock"><?php echo $stock; ?></p>

                        <!-- description -->
                        <p class="itemDescription"><?php echo $desc; ?></p>

                        <!-- view more button -->
                        <a href="item.php?id=<?php echo $id; ?>">
                            <button class="viewMoreButton" type="button">View More</button>
                        </a>

                    </div>

                </li>

            <?php
                }
            }
            else {
                echo "No products found in database";
            }
            ?>

            </ul>

        </div>
    </main>
    <!-- Scroll to top button (W3Schools style) -->
    <button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>

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
    
    <!-- external JavaScript file -->
    <!-- https://www.w3schools.com/tags/att_script_src.asp -->
    <!-- <script src="products.js"></script> -->

    <script>
    // filter products by stock type
    // products are built by PHP now, JS is only used for filtering on the page
    function filterProducts(stockType) {
        var cards = document.getElementsByClassName("productCard");

        // show everything if user clicks show all
        if (stockType === "all") {
            stockType = "";
        }

        for (var i = 0; i < cards.length; i++) {
            // hide every card first
            cards[i].classList.remove("show");

            // if the selected class matches, show the card again
            if (cards[i].className.indexOf(stockType) > -1) {
                cards[i].classList.add("show");
            }
        }
    }

    // mobile nav toggle
    // same idea as before, just kept directly on this page
    function myFunction() {
        var x = document.getElementById("myLinks");
        if (x.style.display === "block") {
            x.style.display = "none";
        } else {
            x.style.display = "block";
        }
    }

    // scroll to top button logic
    var mybutton = document.getElementById("myBtn");

    window.onscroll = function () {
        scrollFunction();
    };

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    }

    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }

    // show all products when page first loads
    filterProducts("all");
    </script>
</body>
</html>
