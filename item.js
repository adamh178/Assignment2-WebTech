// test commands using console (for debugging only)
console.log("Index:", sessionStorage.getItem("index"));
console.log("Name:", sessionStorage.getItem("name"));
console.log("Color:", sessionStorage.getItem("color"));
console.log("Price:", sessionStorage.getItem("price"));
console.log("Stock:", sessionStorage.getItem("stock"));
console.log("Description:", sessionStorage.getItem("description"));
console.log("Image source:", sessionStorage.getItem("src"));

// When page loads, query sessionStorage keys and inject values into named elements
window.onload = function () {

    document.getElementById("itemImage").src = sessionStorage.getItem("src");
    document.getElementById("itemName").innerHTML = sessionStorage.getItem("name");
    document.getElementById("itemColor").innerHTML = sessionStorage.getItem("color");
    document.getElementById("itemPrice").innerHTML = sessionStorage.getItem("price");
    document.getElementById("itemDescription").innerHTML = sessionStorage.getItem("description");

    var stock = sessionStorage.getItem("stock");

    if (stock === "good-stock") {
        document.getElementById("itemStock").innerHTML = "In stock";
    }
    else if (stock === "last-few") {
        document.getElementById("itemStock").innerHTML = "Last few left";
    }
    else {
        document.getElementById("itemStock").innerHTML = "Out of stock";
    }
    // add to cart button (lecture style onclick handler)
    document.getElementById("addToCartBtn").onclick = function () {

        // https://www.smashingmagazine.com/2014/02/create-client-side-shopping-cart/
        // create a simple item object from sessionStorage
        var cartItem = {
            name: sessionStorage.getItem("name"),
            price: sessionStorage.getItem("price"),
            color: sessionStorage.getItem("color"),
            stock: sessionStorage.getItem("stock"),
            description: sessionStorage.getItem("description"),
            src: sessionStorage.getItem("src")
        };

        // get exisiting cart or empty array if nothing
        var cart = JSON.parse(localStorage.getItem("cart")) || [];

        // adding new item into the array
        cart.push(cartItem);

        // https://www.freecodecamp.org/news/web-storage-localstorage-vs-sessionstorage-in-javascript/
        // save updated array into localStorage, usng JSON.stringify
        // as it is an object before stored in web storage
        localStorage.setItem("cart", JSON.stringify(cart));

        // when item added to cart, automatically taken to cart page
        alert("Added to cart");
        window.location.href = "cart.html";
    };
};

// mobile navigation from w3Schools
// https://www.w3schools.com/jsref/met_document_getelementbyid.asp
function myFunction() {
    var x = document.getElementById("myLinks");
    if (x.style.display === "block") {
        x.style.display = "none";
    }
    else {
        x.style.display = "block";
    }
}