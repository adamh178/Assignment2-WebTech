<?php
session_start();
require_once("connect.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="style1.css">
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
            <!-- links that show/hide on mobile -->
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
            <!-- hamburger icon to open the mobile nav -->
            <!-- https://www.w3schools.com/howto/howto_js_mobile_navbar.asp -->
            <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                <i class="fa fa-bars"></i>
            </a>
        </div>
    </header>

    <main>
        <div class="main">

            <div class="pageTitle">
                <h1>Legacy T-Shirts</h1>
                <p>Old UCLan merchandise at discounted prices.</p>
            </div>

            <!-- filter buttons to show all or in stock only -->
            <div id="filterButtons">
                <button class="btn" onclick="filterProducts('all')">Show All</button>
                <button class="btn" onclick="filterProducts('inStock')">In Stock</button>
            </div>

            <!-- product list - built by PHP from the database -->
            <ul id="productList">

            <?php

            $sql = "SELECT * FROM tbl_products";
            $result = $conn->query($sql);

            if (!$result) {
                die("Query failed: " . $conn->error);
            }

            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {

                    $id    = $row["product_id"];
                    $title = $row["product_title"];
                    $price = $row["product_price"];
                    $stock = $row["product_stock"];
                    $image = $row["product_src"];
                    $desc  = $row["product_desc"];
            ?>

                <?php
                // work out which filter class to give each card
                if ($stock == "good-stock" || $stock == "low-stock") {
                    $stockClass = "inStock";
                } else {
                    $stockClass = "outStock";
                }
                ?>

                <li class="productCard show <?php echo $stockClass; ?>">

                    <img class="itemImage" src="<?php echo $image; ?>" alt="<?php echo $title; ?>">

                    <div class="itemInfo">

                        <h2 class="itemName"><?php echo $title; ?></h2>

                        <p class="itemPrice">£<?php echo $price; ?></p>

                        <!-- convert the database value into something readable -->
                        <p class="itemStock">
                            <?php
                            if ($stock == "good-stock") {
                                echo "In stock";
                            } elseif ($stock == "low-stock") {
                                echo "Low stock";
                            } else {
                                echo "Out of stock";
                            }
                            ?>
                        </p>

                        <p class="itemDescription"><?php echo $desc; ?></p>

                        <a href="item.php?id=<?php echo $id; ?>">
                            <button class="viewMoreButton" type="button">View More</button>
                        </a>

                        <!-- logged in users go to item page, guests get sent to login -->
                        <?php if (isset($_SESSION["logged-in"]) && $_SESSION["logged-in"] == true) { ?>
                            <a href="item.php?id=<?php echo $id; ?>">
                                <button class="addToBasketButton" type="button">Add to Basket</button>
                            </a>
                        <?php } else { ?>
                            <a href="login.php">
                                <button class="addToBasketButton" type="button">Add to Basket</button>
                            </a>
                        <?php } ?>

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

    <!-- scroll to top button -->
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

    <script>
    // filter products by stock - JS just shows/hides the cards PHP already built
    function filterProducts(stockType) {
        var cards = document.getElementsByClassName("productCard");

        // show everything if user clicks show all
        if (stockType === "all") {
            stockType = "";
        }

        for (var i = 0; i < cards.length; i++) {
            // hide every card first then show the ones that match
            cards[i].classList.remove("show");
            if (cards[i].className.indexOf(stockType) > -1) {
                cards[i].classList.add("show");
            }
        }
    }

    // mobile nav toggle
    function myFunction() {
        var x = document.getElementById("myLinks");
        if (x.style.display === "block") {
            x.style.display = "none";
        } else {
            x.style.display = "block";
        }
    }

    // scroll to top button - only appears after scrolling down a bit
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

    // show all products when the page first loads
    filterProducts("all");
    </script>
</body>
</html>
