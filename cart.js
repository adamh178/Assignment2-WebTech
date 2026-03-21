// https://www.smashingmagazine.com/2014/02/create-client-side-shopping-cart/
// get item from localStorage
// JSON.parse allows strings to go back to JS objects

// Cart page - load the cart array from localStorage and display it
// https://www.w3schools.com/html/html5_webstorage.asp

var cart = JSON.parse(localStorage.getItem("cart")) || []; // cart is an array
console.log("Cart from localStorage:", cart); // debug

// printing items
var cartItemsDiv = document.getElementById("cartItems");
// eg - Your cart is empty
var cartMessage = document.getElementById("cartMessage");

// if cart is empty
if (cart.length === 0) {
    cartMessage.innerHTML = "Your cart is empty.";
}
else {
    // clear empty cart message
    cartMessage.innerHTML = "";

    // used W3schools to dynamically create and display a cart item
    // loop through each item in the cart array
    for (var i = 0; i < cart.length; i++) {

        // created a container div for one cart item
        var cartItemDiv = document.createElement("div");
        cartItemDiv.className = "cartItem";


        var img = document.createElement("img");
        img.src = cart[i].src;
        img.alt = "Product image";

        var namePara = document.createElement("p");
        namePara.innerHTML = cart[i].name + " (" + cart[i].color + ")";

        var pricePara = document.createElement("p");
        pricePara.innerHTML = cart[i].price;

        // adding image and text into the cart item div
        cartItemDiv.appendChild(img);
        cartItemDiv.appendChild(namePara);
        cartItemDiv.appendChild(pricePara);

        // add the completed cart item into main cart container as shown
        cartItemsDiv.appendChild(cartItemDiv);
    }
}

// Clearing the cart function
document.getElementById("clearCartBtn").onclick = function () {
    localStorage.removeItem("cart");
    window.location.reload();
};


// if (item !== null) {
//     document.getElementById("cartMessage").innerHTML = "";

//     document.getElementById("cartItems").style.display = "flex";
//     document.getElementById("cartImage").src = item.src;
//     document.getElementById("cartName").innerHTML = item.name + " (" + item.color + ")";
//     document.getElementById("cartPrice").innerHTML = item.price;
// }
// else {
//     document.getElementById("cartMessage").innerHTML = "Your cart is empty.";
//     document.getElementById("cartItems").style.display = "none";
// }