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

// handle review form submission - only runs if user is logged in and form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["logged-in"])) {

    $review_title  = $_POST["review_title"];
    $review_desc   = $_POST["review_desc"];
    $review_rating = $_POST["review_rating"];
    $user_id       = $_SESSION["user_id"];

    // insert the new review into the database
    $insertSql = "INSERT INTO tbl_reviews (user_id, product_id, review_title, review_desc, review_rating) VALUES ($user_id, $id, '$review_title', '$review_desc', '$review_rating')";
    $conn->query($insertSql);

    // redirect back to the same page so the new review shows up straight away
    header("Location: item.php?id=$id");
    exit();
}

// get all reviews for this product
$reviewSql = "SELECT * FROM tbl_reviews WHERE product_id = $id";
$reviewResult = $conn->query($reviewSql);

// calculate the average rating for this product
$avgSql = "SELECT AVG(review_rating) AS avg_rating FROM tbl_reviews WHERE product_id = $id";
$avgResult = $conn->query($avgSql);
$avgRow = $avgResult->fetch_assoc();

// round to 1 decimal place, or show 0 if no reviews yet
$avgRating = $avgRow["avg_rating"] ? round($avgRow["avg_rating"], 1) : 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" type="text/css" href="style1.css"> <!-- link to the stylesheet -->
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

        <!-- convert the database stock value into something readable -->
        <p id="itemStock">
            <?php
            if ($row['product_stock'] == "good-stock") {
                echo "In stock";
            } elseif ($row['product_stock'] == "low-stock") {
                echo "Low stock";
            } else {
                echo "Out of stock";
            }
            ?>
        </p>

        <p id="itemDescription"><?php echo $row['product_desc']; ?></p>
        
        <!-- add to cart - only shown when logged in -->
        <?php if (isset($_SESSION["logged-in"]) && $_SESSION["logged-in"] == true) { ?>
            <form method="POST" action="cart.php">
                <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                <button type="submit" class="addToBasketButton">Add to Cart</button>
            </form>
        <?php } else { ?>
            <a href="login.php"><button class="addToBasketButton">Add to Cart</button></a>
        <?php } ?>

        <a href="products.php" class="itemBackLink">Back to products</a>

        <!-- average rating for this product -->
        <h2>Average Rating: <?php echo $avgRating; ?> / 5</h2>

        <!-- loop through and display all reviews for this product -->
        <?php if ($reviewResult->num_rows > 0) { ?>

            <?php while ($review = $reviewResult->fetch_assoc()) { ?>
                <div class="reviewCard">
                    <h3><?php echo $review["review_title"]; ?></h3>
                    <p><?php echo $review["review_desc"]; ?></p>
                    <p><strong>Rating: <?php echo $review["review_rating"]; ?> / 5</strong></p>
                </div>
            <?php } ?>

        <?php } else { ?>
            <p>No reviews yet for this product.</p>
        <?php } ?>

        <!-- review form - only shown when logged in -->
        <?php if (isset($_SESSION["logged-in"]) && $_SESSION["logged-in"] == true) { ?>

            <h2>Leave a Review</h2>

            <form method="POST" class="reviewForm">

                <label>Title:</label>
                <input type="text" name="review_title" required>

                <label>Comment:</label>
                <textarea name="review_desc" required></textarea>

                <label>Rating:</label>
                <select name="review_rating" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>

                <button type="submit">Submit</button>

            </form>

        <?php } else { ?>
            <!-- tell guests they need to log in to leave a review -->
            <p>Please <a href="login.php">log in</a> to leave a review.</p>
        <?php } ?>

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
