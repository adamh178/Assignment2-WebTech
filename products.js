// filter products by stock type
// adapted from W3Schools filter elements example
// https://www.w3schools.com/howto/howto_js_filter_elements.asp

function filterProducts(stockType) {

    var cards, i;
    cards = document.getElementsByClassName("productCard");

    // if 'all' is selected, show everything
    if (stockType === "all") {
        stockType = "";
    }

    for (i = 0; i < cards.length; i++) {

        // remove the show class from every card first
        removeClass(cards[i], "show");

        // skip the hidden template card
        if (cards[i].id === "itemCard") {
            continue;
        }

        // if card contains the selected stock class, show it
        if (cards[i].className.indexOf(stockType) > -1) {
            addClass(cards[i], "show");
        }
    }
}

function addClass(element, name) {
    var i, arr1, arr2;
    arr1 = element.className.split(" ");
    arr2 = name.split(" ");
    for (i = 0; i < arr2.length; i++)
    {
        if (arr1.indexOf(arr2[i]) == -1)
        {
            element.className += " " + arr2[i];
        }
    }
}

function removeClass(element, name) {
    var i, arr1, arr2;
    arr1 = element.className.split(" ");
    arr2 = name.split(" ");
    for (i = 0; i < arr2.length; i++)
    {
        while (arr1.indexOf(arr2[i]) > -1)
        {
            arr1.splice(arr1.indexOf(arr2[i]), 1);
        }
    }
    element.className = arr1.join(" ");
}
        

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

    // array indexed as follows: 
    // [0]name, [1]color, [2]price, [3]stock [4]image-src, [5]desc.

    const tshirts = [
        ['Legacy T-Shirt','Red','£7.99','good-stock','images/tshirts/tshirt1.jpg','Perfect for those graduating this year. Get a bargain whilst we have the stock.'],
        ['Legacy T-Shirt','Green','£7.99','last-few','images/tshirts/tshirt2.jpg','Limited stock. Grab these nostalgic items before they make their way onto eBay.'],
        ['Legacy T-Shirt','Blue','£7.99','out-of-stock','images/tshirts/tshirt3.jpg','Sadly we are sold out of this legendary item. Keep an eye out for future stock.'],
        ['Legacy T-Shirt','Cyan','£7.99','good-stock','images/tshirts/tshirt4.jpg','Perfect for those graduating this year. Get a bargain whilst we have the stock.'],
        ['Legacy T-Shirt','Magenta','£7.99','out-of-stock','images/tshirts/tshirt5.jpg','Sadly we are sold out of this legendary item. Keep an eye out for future stock.'],
        ['Legacy T-Shirt','Yellow','£7.99','last-few','images/tshirts/tshirt6.jpg','Limited stock. Grab these nostalgic items before they make their way onto eBay.'],
        ['Legacy T-Shirt','Black','£7.99','out-of-stock','images/tshirts/tshirt7.jpg','Sadly we are sold out of this legendary item. Keep an eye out for future stock.'],
        ['Legacy T-Shirt','Grey','£7.99','good-stock','images/tshirts/tshirt8.jpg','Perfect for those graduating this year. Get a bargain whilst we have the stock.'],
        ['Legacy T-Shirt','Burgundy','£7.99','last-few','images/tshirts/tshirt9.jpg','Limited stock. Grab these nostalgic items before they make their way onto eBay.'],
    ];

	// locate the container and the template card
    // https://www.w3schools.com/jsref/met_document_getelementbyid.asp
    const products = document.getElementById('productList'); // where all cards go
    const item = document.getElementById('itemCard');        // the template card

     // function from the slides – stores data for the clicked item in WebAPI
    function webapi(itemIndex) {
        return function () {
            sessionStorage.setItem('index', itemIndex);
            sessionStorage.setItem('name', tshirts[itemIndex][0]);
            sessionStorage.setItem('color', tshirts[itemIndex][1]);
            sessionStorage.setItem('price', tshirts[itemIndex][2]);
            sessionStorage.setItem('stock', tshirts[itemIndex][3]);
            sessionStorage.setItem('src', tshirts[itemIndex][4]);
            sessionStorage.setItem('description', tshirts[itemIndex][5]);

            // go to the detail page
            window.location.href = "item.html";
        };
    }

    // loop through the tshirts array
    for (var i = 0; i < tshirts.length; i++) {

        // create a copy of the template card
        var itemCopy = item.cloneNode(true);   // true = deep clone

        // add the copy into the product list
        products.appendChild(itemCopy);

        // give it a unique id, e.g. "item0", "item1", ...
        var value = "item" + i;
        itemCopy.id = value;

        // write array data into THIS card

        // name + colour
        itemCopy.querySelector('.itemName').innerHTML = tshirts[i][0] + ' (' + tshirts[i][1] + ')';

        // price
        itemCopy.querySelector('.itemPrice').innerHTML = tshirts[i][2];

        // description
        itemCopy.querySelector('.itemDescription').innerHTML = tshirts[i][5];

        // image
        itemCopy.querySelector('.itemImage').src = tshirts[i][4];  // find the <img> inside the card, sets it <src> file path

        // stock
        var stockValue = tshirts[i][3];
        
        // writing stock text into the card
        if (stockValue === "good-stock") {
            itemCopy.querySelector('.itemStock').innerHTML = "In stock";
            itemCopy.classList.add("inStock");
            document.getElementById(value).onclick = webapi(i);
            // "View more" button (same behaviour as clicking the card)
            itemCopy.querySelector(".viewMoreButton").onclick = webapi(i);
        }
        else if (stockValue === "last-few") {
            itemCopy.querySelector('.itemStock').innerHTML = "Last few left";
            itemCopy.classList.add("inStock");
            document.getElementById(value).onclick = webapi(i);
            // "View more" button (same behaviour as clicking the card)
            itemCopy.querySelector(".viewMoreButton").onclick = webapi(i);
        }
        else {
            itemCopy.querySelector('.itemStock').innerHTML = "Out of stock";
            itemCopy.classList.add("outStock");

            // differentiate between out of stock and in stock:
            // https://www.w3schools.com/Jsref/prop_style_opacity.asp
            itemCopy.style.opacity = "0.5";

            // unable to click out of stock items
            document.getElementById(value).onclick = null;
            itemCopy.querySelector(".viewMoreButton").onclick = null;
            itemCopy.querySelector(".viewMoreButton").disabled = true;
        }
	}

	// hide the original template card (like in the slide: item.style.display = 'none')
    item.style.display = 'none';
    
    filterProducts("all"); // show everything on page load

// scroll to top button logic
// https://www.w3schools.com/howto/howto_js_scroll_to_top.asp

var mybutton = document.getElementById("myBtn");

// show button when user scrolls down
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

// when user clicks the button, scroll to top
function topFunction() {
    document.body.scrollTop = 0;            // Safari
    document.documentElement.scrollTop = 0; // Chrome, Firefox, Edge
}