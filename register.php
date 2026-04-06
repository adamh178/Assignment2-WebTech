<?php
session_start();
require_once("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // grab the form fields and clean them up
    $name     = htmlspecialchars($_POST["user_name"]);
    $email    = htmlspecialchars($_POST["user_email"]);
    $password = htmlspecialchars($_POST["user_pass"]);
    $confirm  = htmlspecialchars($_POST["user_confirm"]);
    $address  = htmlspecialchars($_POST["user_address"]);

    // check both passwords are the same
    if ($password !== $confirm) {
        $error = "Passwords do not match.";

    // password needs to be at least 8 characters
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";

    // also needs a number in it
    } elseif (!preg_match('/[0-9]/', $password)) {
        $error = "Password must contain at least one number.";

    } else {

        // make sure the email isn't already in the database
        $checkSql = "SELECT * FROM tbl_users WHERE user_email = '$email'";
        $checkResult = $conn->query($checkSql);

        if ($checkResult->num_rows > 0) {
            $error = "That email is already registered. Please log in.";

        } else {

            // hash the password so it's not stored as plain text
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // insert the new user into the database using a prepared statement
            $stmt = $conn->prepare("INSERT INTO tbl_users (user_name, user_email, user_pass, user_address) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashedPassword, $address);
            $stmt->execute();

            // check it worked using insert_id
            $lastId = $conn->insert_id;
            if ($lastId) {
                $success = "Account created! You can now log in.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Shop - Register</title>
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
                    <li class="navList"><a href="login.php">Login</a></li>
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
                <a href="login.php">Login</a>
            </div>
            <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                <i class="fa fa-bars"></i>
            </a>
        </div>
    </header>

    <main>
        <div class="main">

            <h1>Register</h1>

            <p>Have an account? <a href="login.php">Log in here</a></p>

            <!-- show error if something went wrong -->
            <?php if (isset($error)) echo "<p class='formError'>$error</p>"; ?>

            <!-- show success message if account was created -->
            <?php if (isset($success)) echo "<p class='formSuccess'>$success <a href='login.php'>Log in here</a></p>"; ?>

            <!-- novalidate stops the browser popups so my custom messages show instead -->
            <form method="POST" novalidate onsubmit="return validateForm()">

                <label>Name:</label>
                <input type="text" name="user_name" id="nameInput">
                <p class="errorMsg" id="nameError">Please enter a valid name.</p>

                <label>Email:</label>
                <input type="email" name="user_email" id="emailInput">
                <p class="errorMsg" id="emailError">Please enter a valid email address.</p>

                <label>Password:</label>
                <input type="password" name="user_pass" id="passwordInput">
                <p class="errorMsg" id="passwordError">Password must be at least 8 characters and contain a number.</p>

                <label>Confirm Password:</label>
                <input type="password" name="user_confirm" id="confirmInput">
                <p class="errorMsg" id="confirmError">Passwords do not match.</p>

                <label>Address:</label>
                <input type="text" name="user_address" id="addressInput">
                <p class="errorMsg" id="addressError">Please enter your address.</p>

                <button type="submit">Register</button>

            </form>

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
    // check all fields before the form sends
    function validateForm() {

        var valid = true;

        var name     = document.getElementById("nameInput").value;
        var email    = document.getElementById("emailInput").value;
        var password = document.getElementById("passwordInput").value;
        var confirm  = document.getElementById("confirmInput").value;
        var address  = document.getElementById("addressInput").value;

        // reset all error messages first
        document.getElementById("nameError").style.display     = "none";
        document.getElementById("emailError").style.display    = "none";
        document.getElementById("passwordError").style.display = "none";
        document.getElementById("confirmError").style.display  = "none";
        document.getElementById("addressError").style.display  = "none";

        // name can't be blank
        if (name === "") {
            document.getElementById("nameError").style.display = "block";
            valid = false;
        }

        // basic check that it looks like an email
        if (email === "" || email.indexOf("@") === -1) {
            document.getElementById("emailError").style.display = "block";
            valid = false;
        }

        // needs 8 characters and at least one number
        if (password.length < 8 || !/[0-9]/.test(password)) {
            document.getElementById("passwordError").style.display = "block";
            valid = false;
        }

        // make sure both passwords match
        if (password !== confirm) {
            document.getElementById("confirmError").style.display = "block";
            valid = false;
        }

        // address can't be blank
        if (address === "") {
            document.getElementById("addressError").style.display = "block";
            valid = false;
        }

        // returns true to submit or false to stop
        return valid;
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
    </script>
</body>
</html>
