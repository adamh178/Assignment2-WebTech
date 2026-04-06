<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Shop - Page Not Found</title>
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

            <h1>404 - Page Not Found</h1>
            <p>Sorry, the page you are looking for does not exist.</p>
            <p><a href="index.php">Go back to the homepage</a></p>

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
