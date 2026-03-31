<?php
session_start();
require_once("connect.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" type="text/css" href="main.css"> <!-- link to the stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Student Union Shop - Home</title>
</head>
<body>
    <header>
        <!-- Desktop Header -->
        <div class="desktopHeader">
            <div class="brand">
                <img src="images/logo_reverse.png" alt="University of Central Lancashire logo">
                <span class="brand-text">Student Shop</span>
            </div>

            <!-- Semantic markup is used to clearly declare that this is for navigation -->
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
            <!-- "Hamburger menu" to toggle the navigation links -->
            <!-- https://www.w3schools.com/howto/howto_js_mobile_navbar.asp -->
            <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                <i class="fa fa-bars"></i>
            </a>
        </div>
    </header>

    <main>
        <div class="main">

            <!-- personalised welcome message - only shows when logged in -->
            <?php if (isset($_SESSION["logged-in"]) && $_SESSION["logged-in"] == true) { ?>
                <p>Welcome back, <?php echo $_SESSION["user_name"]; ?>!</p>
            <?php } ?>

            <section class="offersSection">
                <h2>Current Offers</h2>

                <div id="offerList">

                    <?php

                    // SQL query
                    $sql = "SELECT * FROM tbl_offers";

                    // run query
                    $result = $conn->query($sql);

                    // check query
                    if (!$result) {
                        die("Query failed: " . $conn->error);
                    }

                    // loop through offers
                    if ($result->num_rows > 0) {

                        while ($row = $result->fetch_assoc()) {

                            $title = $row["offer_title"];
                            $desc = $row["offer_desc"];

                            echo "<div class='offerCard'>";
                            echo "<h3>$title</h3>";
                            echo "<p>$desc</p>";
                            echo "</div>";
                        }

                    } else {
                        echo "<p>No offers available</p>";
                    }

                    ?>

                </div>
            </section>

            <h1>Where opportunity creates success</h1>
            <p>
                Every student at The University of Central Lancashire is automatically a member 
                of the Students' Union. We're here to make life better for students —
                inspiring you to succeed and achieve your goals.
            </p>

            <p>
                Everything you need to know about UCLan Students' Union. Your membership starts here.
            </p>

            <h2 class="html5">Together</h2>
            <!-- HTML5 video - from Week 6 notes -->
            <!-- https://www.w3schools.com/html/html5_video.asp -->
            <video width="700" height="400" controls>
                <source src="video/video.mp4" type="video/mp4">
                Your browser does not support the HTML5 video element.
            </video>

            <h2 class="youtubeEmbed">Join our global community</h2>
            <!-- Youtube iframe video - from week 6 notes -->
            <iframe
                width="700"
                height="400"
                src="https://www.youtube.com/embed/vzbO3x3OUJQ"
                title="University Open Day"
                allowfullscreen>
            </iframe>

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
        // mobile navigation from w3Schools
        // https://www.w3schools.com/jsref/met_document_getelementbyid.asp
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