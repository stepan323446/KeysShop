/**
 * Send ajax action to server
 * @param {string} action 
 * @param {Object} args 
 * @param {Function} onLoad 
 * @param {Function} onSuccess 
 * @param {Function} onError 
 */
function sendAjax(action, 
    args = [ ],
    onLoad = () => { }, 
    onSuccess = (data) => { }, 
    onError = (error) => { }) {
    
    onLoad();
    
    fetch('/ajax', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            'action': action,
            'args': args
        })
    })
    .then(response => response.json().then(data => ({ data, response })))
    .then(({ data, response }) => {
        if (!response.ok) {
            throw new Error(data.error || `HTTP error! Status: ${response.status}`);
        }
    
        onSuccess(data);
    })
    .catch(error => {
        onError(error);
    });
}
function showToastify(message, type = "info") {
    Toastify({
        text: message,
        gravity: "bottom",
        position: "left",
        className: type,
        duration: 3000 })
    .showToast();
}
function blockScroll(turnOn) {
    if(turnOn)
        document.body.classList.add('lock');
    else
        document.body.classList.remove('lock');
}

// Profile avatar
let headerAvatar = document.getElementById('header-avatar');
headerAvatar?.addEventListener('click', (e) => {
    headerAvatar.classList.toggle('active');
});

/**
 * Init events for every button (.btn-wishlist) with attr product-id as wishlist
 * @param {HTMLElement} container element with wishlist add buttons
 */
function addToWishlist(container) {
    let btnsWishlist = container.querySelectorAll('.btn-wishlist');

    btnsWishlist.forEach(btn => {
        let productId = btn.getAttribute('product-id');
        let icon = btn.querySelector('i');
        let textSpan = btn.querySelector('span');

        let isWishlist = icon.classList.contains('fa-solid');

        btn.addEventListener('click', (e) => {
            sendAjax(
                "wishlist", {
                product_id: productId
            },
            () => {
                btn.disabled = true;
            },
            (result) => {
                isWishlist = !isWishlist;
                icon.classList.toggle('fa-regular');
                icon.classList.toggle('fa-solid');
                
                if(isWishlist && textSpan)
                    textSpan.textContent = "Remove from List";
                else if(textSpan)
                    textSpan.textContent = "Add to Wishlist";

                showToastify(result.message);
                btn.disabled = false;
            },
            (error) => {
                showToastify(error.message, "error");
                btn.disabled = false;
            });
        });
    });
}

// Init event for every button on page
addToWishlist(document.body);

// Background to close some fullscreen container
let backgroundScreens = document.querySelectorAll('.background-screen');
backgroundScreens.forEach(screen => {
    screen.addEventListener('click', (e) => {
        if(e.target == screen) {
            screen.classList.remove('active');
            blockScroll(false);
        }
    });
});

// Ajax search
let search = document.getElementById("search-global");
let searchInput = search.querySelector("input");
let searchIcon = search.querySelector("i");

let searchResult = document.getElementById("ajax-search-result");
let searchResultContent = searchResult.querySelector(".content");

/**
 * @param {Array} objects 
 */
function displaySearchResult(objects) {
    searchResultContent.innerHTML = "";
    if(objects.length == 0) {
        searchResultContent.innerHTML = '<div class="nothing">Nothing found</div>'
        return;
    }
    objects.forEach(obj => {
        let discount = "Out of Stock";
        if(obj.is_available)
            discount = "-" + obj.discount + "%";

        searchResultContent.innerHTML += `
        <a href="${obj.permalink}" class="item">
            <div class="left">
                <div class="image image-cover">
                    <img src="${obj.image}" alt="">
                </div>
                <div class="item-info">
                    <div class="title clamp clamp-2">${obj.title}</div>
                    <div class="platform">${obj.platform_icon} ${obj.platform_name}</div>
                </div>
            </div>
            <div class="right">
                <div class="price">${obj.price}$</div>
                <div class="discount">(${discount})</div>
            </div>
        </a>
        `;
    });
}

let searchTimeout = null;
searchInput.addEventListener("input", (e) => {
    let searchVal = searchInput.value.trim();

    if(searchInput != null)
        clearTimeout(searchTimeout);

    // If we have search
    if(searchVal != "") {
        search.classList.add("loading");

        searchTimeout = setTimeout(() => {
            sendAjax(
                "search",
                {
                    search: searchVal
                },
                () => { },
                (data) => {
                    searchResult.hidden = false;
                    search.classList.remove("loading");
                    displaySearchResult(data.objects);
                },
                (error) => {
                    search.classList.remove("loading");
                    showToastify("An error occurred while searching", "error");      
                }
            );
        }, 1200);
    }
    // if nothing in the search input
    else {
        searchResult.hidden = true;
        search.classList.remove("loading");
    }
});

// Add to cart
let headerCartBtn = document.querySelector('.header-cart');
let headerCartPrice = document.querySelector('.header-cart__price');
let headerCartCounter = document.getElementById('header-cart-counter');

let cartContainer = document.getElementById("cart-container");
let cartContainerClose = document.querySelectorAll('.cart-close');
let cartItems = cartContainer.querySelector('.cart-items');
let nothingMessage = cartContainer.querySelector('.nothing');

let collectedInfo = false;

headerCartBtn.addEventListener('click', (e) => {
    e.preventDefault();
    cartContainer.classList.add('active');
    blockScroll(true);

    if(!collectedInfo) {
        sendAjax(
            'cart',
            {
                type: 'info'
            },
            () => {
                nothingMessage.textContent = "Loading...";
                cartContainer.classList.remove("has-items");
            },
            (data) => {
                collectedInfo = true;
                updateCart(data.cart_information);
            },
            (error) => {
                showToastify(error.message, "error");
                btn.disabled = false;
            }
        )
    }
});
cartContainerClose.forEach(btn => {
    btn.addEventListener('click', (e) => {
        cartContainer.classList.remove('active');
        blockScroll(false);
    }); 
});

function updateCart(cartInfo) {
    // Display to header
    headerCartPrice.textContent = cartInfo.total_price + "$";
    headerCartCounter.textContent = cartInfo.count;

    if(cartInfo.count > 0)
        headerCartCounter.hidden = false;
    else
        headerCartCounter.hidden = true;

    // Display to cart
    document.querySelector('#cart-container .total-price .price').textContent = cartInfo.total_price + "$";
    cartItems.innerHTML = "";

    cartInfo.products.forEach(prod => {
        let productElem = document.createElement("cart-item");
        productElem.innerHTML = `
        <div class="cart-item">
            <a href="${prod.permalink}" class="image image-cover">
                <img src="${prod.image}" alt="">
            </a>
            <div class="item-info">
                <div class="item-title">
                    <a href="${prod.permalink}" class="item-title__text clamp clamp-2">${prod.title}</a>
                    <button type="button" class="item-title__remove" product-id="${prod.id}"><i class="fa-solid fa-x"></i></button>
                </div>
                <div class="prices">
                    <div class="old-price">${prod.old_price}$</div>
                    <div class="price">${prod.price}$</div>
                </div>
            </div>
        </div>
        `;
        let btnRemoveItem = productElem.querySelector(".item-title__remove");
        btnRemoveItem.addEventListener("click", (e) => {
            sendAjax(
                'cart',
                {
                    product_id: prod.id,
                    type: "remove"
                },
                () => {
                    btnRemoveItem.disabled = true;
                },
                (data) => {
                    updateCart(data.cart_information);
                    showToastify(data.message);
                    collectedInfo = true;
                },
                (error) => {
                    showToastify(error.message, "error");
                }
            );
        });

        cartItems.append(productElem);
    });

    if(cartInfo.count == 0) {
        nothingMessage.textContent = "You have no items in your shopping cart.";
        cartContainer.classList.remove("has-items");
    } else {
        cartContainer.classList.add("has-items");
    }
}

let btnAddCart = document.querySelectorAll('.btn-add-to-cart');
btnAddCart?.forEach(btn => {
    let productId = btn.getAttribute('product-id');
    btn.addEventListener('click', (e) => {
        sendAjax(
            'cart',
            {
                product_id: productId,
                type: "add"
            },
            () => {
                btn.disabled = true;
            },
            (data) => {
                updateCart(data.cart_information);
                showToastify(data.message);
                btn.disabled = false;
                collectedInfo = true;
            },
            (error) => {
                showToastify(error.message, "error");
                btn.disabled = false;
            }
        );
    });
});