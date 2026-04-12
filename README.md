# Student Name: Adam Hassan
Student ID: G20932771

# Homepage URL:
https://vesta.uclan.ac.uk/~ahassan17/assignment2web/index.php

# GitHub Repository:
https://github.com/adamh178/Assignment2-WebTech

# Description:
A Student Union Shop built with PHP and MySQL. Products, offers and user data all come from the database.

# Dummy Account:
Email: adam@yahoo.com
Password: liverpool1!

# Pages:
- index.php - Homepage showing live offers from the database and a welcome message when logged in
- products.php - Lists all products from the database with a filter for in stock items
- item.php - Individual product page showing details, reviews, average rating, and a review form for logged in users
- cart.php - Shopping cart using cookies, shows current offers, and allows logged in users to checkout
- login.php - Login page that checks credentials against the database using a prepared statement
- register.php - Registration page with client-side and server-side validation
- logout.php - Destroys the session and redirects to the homepage
- 404.php - Custom error page shown when a page is not found

# Features:
- Homepage showing live offers from the database
- Products page with filtering
- Item page with reviews and average rating
- Register and login with password hashing
- Shopping cart using cookies, saves order to database on checkout
- Personalised messages when logged in
- Custom 404 page
- Responsive on mobile, tablet and desktop

# Usability and Accessibility:
I tried to follow Don Norman's design principles throughout the site. For example, feedback is given to the user at every step - error messages show when login fails or a form is filled in wrong, and a success message shows when an account is created or an order is placed. This addresses the 'gulf of evaluation' mentioned in the lecture, making sure users always know what has happened.

The navigation changes depending on whether the user is logged in or not showing Logout instead of Login, which follows the consistency and visibility principles. Out of stock items are clearly labelled and the Add to Cart button is hidden for those products so users cannot add them by mistake.

The site is responsive and works on mobile and desktop using CSS media queries. A custom 404 page was created so users are not left on a blank error page.

# Resources used:
- W3Schools (https://www.w3schools.com)
- Lecture materials (CO1707)
- Don Norman's Design Principles - https://xd.adobe.com/ideas/process/ui-design/4-golden-rules-ui-design/
