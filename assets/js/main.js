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