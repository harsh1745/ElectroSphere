// shop.js

// Global state variables...
window.IS_AUTHENTICATED = false;
window.Laravel = { csrfToken: '' };

// ✅ NEW HELPER: SweetAlert Error Display Function
window.showErrorAlert = function (title, text) {
    Swal.fire({
        icon: 'error',
        title: title,
        text: text,
        confirmButtonColor: '#7b68ee'
    });
};

/**
 * Toggles the product's presence in the user's wishlist via AJAX.
 * @param {HTMLElement} element - The clicked element.
 */
// window.toggleWishlist = function (element) {

//     if (!window.IS_AUTHENTICATED) {
//         // alert('Please login to add products to wishlist!');
//         // ➡️ ALERT REPLACED
//         window.showErrorAlert('Login Required!', 'Please login to add products to wishlist.');
//         return;
//     }

//     const isWishlistPage = window.location.pathname.includes('/wishlist');
//     const isDrawerRemoveButton = element.classList.contains('remove-wishlist-drawer-btn');

//     let url;
//     let targetElement;

//     // ✅ NEW: Product ID nikal lo, jo response handling mein kaam aayega
//     const productId = element.dataset.itemId || (element.closest('.wishlist-icon') ? element.closest('.wishlist-icon').dataset.itemId : null);


//     // --- Determine URL and the DOM element to manipulate ---



//     if (isDrawerRemoveButton && targetElement) {
//         targetElement.remove();
//         const contentDiv = document.getElementById('wishlist-drawer-content');
//         if (!contentDiv.querySelector('li')) {
//             contentDiv.innerHTML = '<p class="text-muted text-center p-5">Aapki wishlist khaali hai!</p>';
//         }
//         document.getElementById('wishlist-subtotal').textContent = 'Rs. 0.00';
//     }
//     else if (isWishlistPage) {
//         // Logic for main wishlist page (element is the remove button or the <tr>)
//         const removeBtn = element.querySelector('.remove-wishlist-btn') || element.closest('td').querySelector('button');
//         url = removeBtn ? removeBtn.dataset.toggleRoute : element.dataset.toggleRoute;
//         targetElement = element.closest('tr') || element;
//         // ✅ FIX: ADD THIS LINE TO REMOVE THE ROW
//         if (targetElement) {
//             targetElement.remove();
//         }
//     }
//     else if (isDrawerRemoveButton) {
//         // ✅ DRAWER LOGIC: Element is the <button>. 
//         url = element.dataset.toggleRoute;
//         targetElement = element.closest('li'); // The element to remove from the DOM
//     }
//     else {
//         // Logic for Shop/Product Page Icon
//         url = element.dataset.toggleRoute;
//         targetElement = element;
//     }

//     if (!url) {
//         // console.error("Toggle route not found.");
//         // ➡️ ALERT REPLACED
//         window.showErrorAlert('Configuration Error!', 'Toggle route not configured correctly.');
//         return;
//     }

//     // 🔁 Send AJAX Request
//     fetch(url, {
//         method: 'POST',
//         headers: {
//             'X-CSRF-TOKEN': window.Laravel.csrfToken,
//             'Accept': 'application/json',
//             'Content-Type': 'application/json'
//         },
//     })
//         .then(response => {
//             if (response.status === 401) {
//                 // alert('Session expired or unauthorized. Please login.');
//                 // ➡️ ALERT REPLACED
//                 window.showErrorAlert('Session Expired!', 'Session expired or unauthorized. Please login.');
//                 return { status: 'error' };
//             }
//             return response.json();
//         })
//         .then(data => {

//             if (data.status === 'added') {
//                 if (!isWishlistPage && !isDrawerRemoveButton) {
//                     // Shop/Product Page Icon Update
//                     targetElement.querySelector('i').classList.add('is-wishlisted', 'fas');
//                     targetElement.querySelector('i').classList.remove('far');
//                 }
//             }
//             else if (data.status === 'removed') {

//                 // --- 1. DOM Removal Logic (Drawer/Page) ---
//                 if (isDrawerRemoveButton && targetElement) {
//                     // ✅ DRAWER REMOVAL LOGIC
//                     targetElement.remove();
//                     // ... (Empty state logic and subtotal update is fine here) ...
//                     const contentDiv = document.getElementById('wishlist-drawer-content');
//                     if (!contentDiv.querySelector('li')) {
//                         contentDiv.innerHTML = '<p class="text-muted text-center p-5">Aapki wishlist khaali hai!</p>';
//                     }
//                     document.getElementById('wishlist-subtotal').textContent = 'Rs. 0.00';

//                 } else if (isWishlistPage) {
//                     // Main Wishlist Page Logic
//                     // ... (existing logic) ...
//                 } else {
//                     // Shop Page Logic: Icon ko empty karo
//                     targetElement.querySelector('i').classList.remove('is-wishlisted', 'fas');
//                     targetElement.querySelector('i').classList.add('far');
//                 }

//                 // --- 2. ✅ FIX: Shop Card Sync Logic (MUST RUN AFTER REMOVAL) ---
//                 if (productId) {
//                     // Poore DOM mein us product ID ke icon ko dhoondo aur unfilled karo
//                     // Hum data-toggle-route attribute ka use karenge jismein product ID embedded hota hai.
//                     const syncIcons = document.querySelectorAll(`[data-toggle-route*="/${productId}"] i`);

//                     syncIcons.forEach(icon => {
//                         // Agar icon milta hai (chahe woh shop page par ho, ya kisi aur page par)
//                         icon.classList.remove('is-wishlisted', 'fas');
//                         icon.classList.add('far');
//                     });
//                 }
//             }

//             // ✅ Counter Update (Navbar badge)
//             const counter = document.getElementById('wishlist-count');
//             if (counter && data.count !== undefined) {
//                 counter.textContent = data.count;
//             }
//         })
//         .catch(error => {
//             console.error('Error:', error);
//             // alert('Something went wrong! Please try again.');
//             // ➡️ ALERT REPLACED
//             window.showErrorAlert('Critical Error!', 'Something went wrong! Please try again.');
//         });
// };
window.toggleWishlist = function (element) {

    // 🔐 Authentication check
    if (!window.IS_AUTHENTICATED) {
        window.showErrorAlert('Login Required!', 'Please login to use the wishlist.');
        return;
    }

    // Identify environment
    const isWishlistPage = window.location.pathname.includes('/wishlist');
    const isDrawerRemoveButton = element.classList.contains('remove-wishlist-drawer-btn');

    // URL to send request
    let url = element.dataset.toggleRoute;

    // Detect product ID for icon-sync
    const productId = element.dataset.itemId || element.closest('[data-item-id]')?.dataset.itemId;

    // Which element to remove from DOM
    let targetElement = null;

    // -------------------------------------------------
    // 1️⃣ Wishlist Page (Table Page)
    // -------------------------------------------------
    if (isWishlistPage) {

        targetElement = element.closest("tr");   // always a row
        url = element.dataset.toggleRoute;       // url on the button

        if (!url) {
            return window.showErrorAlert("Error!", "Toggle route missing!");
        }
    }

    // -------------------------------------------------
    // 2️⃣ Wishlist Drawer (Sidebar)
    // -------------------------------------------------
    else if (isDrawerRemoveButton) {

        targetElement = element.closest("li");  // each item is a list item

        if (!url) {
            return window.showErrorAlert("Error!", "Drawer route missing!");
        }
    }

    // -------------------------------------------------
    // 3️⃣ Shop / Product Page Icon
    // -------------------------------------------------
    else {
        targetElement = element; // icon element itself
    }

    // -------------------------------------------------
    // 🔁  AJAX REQUEST (POST)
    // -------------------------------------------------
    fetch(url, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": window.Laravel.csrfToken,
            "Accept": "application/json",
            "Content-Type": "application/json"
        }
    })
        .then(res => res.json())
        .then(data => {

            // -------------------------------------------------
            // ❤️ ADDED TO WISHLIST
            // -------------------------------------------------
            if (data.status === "added") {

                // Shop page icon update
                if (!isWishlistPage && !isDrawerRemoveButton) {
                    const icon = targetElement.querySelector("i");
                    icon.classList.remove("far");
                    icon.classList.add("fas", "is-wishlisted");
                }
            }

            // -------------------------------------------------
            // ❌ REMOVED FROM WISHLIST
            // -------------------------------------------------
            else if (data.status === "removed") {

                // 1️⃣ Remove from UI (Wishlist Page)
                if (isWishlistPage && targetElement) {
                    targetElement.classList.add("row-fade-out");
                    setTimeout(() => {
                        targetElement.remove();

                        // Check empty wishlist
                        if (document.querySelectorAll("tbody tr").length === 0) {
                            document.getElementById("wishlist-table-wrapper").style.display = "none";
                            document.getElementById("wishlist-empty-box").style.display = "block";
                        }
                    }, 300);
                }

                // 2️⃣ Remove from Wishlist Drawer
                else if (isDrawerRemoveButton && targetElement) {
                    targetElement.remove();

                    const drawerList = document.getElementById("wishlist-drawer-content");
                    if (!drawerList.querySelector("li")) {
                        drawerList.innerHTML = '<p class="text-muted text-center p-5">Your wishlist is empty.</p>';
                    }

                    document.getElementById("wishlist-subtotal").textContent = "Rs. 0.00";
                }

                // 3️⃣ Remove icon style from Shop/Product cards
                else {
                    const icon = targetElement.querySelector("i");
                    icon.classList.remove("fas", "is-wishlisted");
                    icon.classList.add("far");
                }

                // -------------------------------------------------
                // 🔄 SYNC ICONS EVERYWHERE (Search, Home, Shop etc.)
                // -------------------------------------------------
                if (productId) {
                    document
                        .querySelectorAll(`[data-toggle-route*="/${productId}"] i`)
                        .forEach(icon => {
                            icon.classList.remove("fas", "is-wishlisted");
                            icon.classList.add("far");
                        });
                }
            }

            // -------------------------------------------------
            // 🧮 Update Wishlist Counter in Navbar
            // -------------------------------------------------
            const counter = document.getElementById("wishlist-count");
            if (counter && data.count !== undefined) {
                counter.textContent = data.count;
            }
        })
        .catch(err => {
            console.error(err);
            window.showErrorAlert("Error!", "Something went wrong.");
        });
};


window.fetchWishlistContent = function (event) {
    event.preventDefault();

    if (!window.IS_AUTHENTICATED) {
        // alert('Login required to view wishlist!');
        window.showErrorAlert('Login Required!', 'Please login to view wishlist!');
        return;
    }

    const contentDiv = document.getElementById('wishlist-drawer-content');

    const routeDataElement = document.getElementById('route-data');
    const fetchUrl = routeDataElement ? routeDataElement.dataset.drawerUrl : '/wishlist/drawer-content';

    if (contentDiv.querySelector('.loading-state')) {
        return; // Already loading
    }
    contentDiv.innerHTML = '<p class="text-muted text-center p-5 loading-state">Loading...</p>';
    document.getElementById('wishlist-subtotal').textContent = '...';

    // AJAX call to fetch and inject the content
    fetch(fetchUrl, {
        method: 'GET',
        headers: { 'Accept': 'text/html' }
    })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(html => {
            contentDiv.innerHTML = html;

            // Read subtotal from the hidden data attribute and update footer
            const subtotalData = document.getElementById('drawer-subtotal-data');
            if (subtotalData) {
                const subtotal = subtotalData.dataset.subtotal;
                document.getElementById('wishlist-subtotal').textContent = 'Rs. ' + subtotal;
            } else {
                document.getElementById('wishlist-subtotal').textContent = 'Rs. 0.00';
            }
        })
        .catch(error => {
            contentDiv.innerHTML = '<p class="text-danger text-center p-5">Could not load items. Please try again.</p>';
            console.error("Could not load wishlist drawer:", error);
        });
}

// ✅ NEW: Toast Message Helper Function
window.showCartToast = function (message) {
    const toastElement = document.getElementById('cartToast');
    const toastBody = document.getElementById('cartToastBody');

    if (toastElement && toastBody) {
        toastBody.textContent = message;
        // Bootstrap Toast ko JS se initialize aur show karo
        const toast = new bootstrap.Toast(toastElement, {
            delay: 3000 // 3 seconds tak dikhega
        });
        toast.show();
    }
}

/**
 * 🛒 Handles AJAX request to add a product to the cart and updates the navbar count.
 * @param {HTMLElement} element - The clicked 'Add to Cart' button.
 */
window.addToCart = function (element) {
    if (!window.IS_AUTHENTICATED) {
        window.showErrorAlert('Login Required!', 'Please login to add products to your cart!');
        return;
    }

    const url = element.dataset.route;
    const quantityInput = element.closest('.product-details-actions')?.querySelector('input[name="quantity"]') || 1;
    const quantity = parseInt(quantityInput.value) || 1;

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': window.Laravel.csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ quantity: quantity })
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Server error');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                const message = `Product successfully ${data.action} to cart. Total items: ${data.count}.`;
                window.showCartToast(message);

                const counter = document.getElementById('cart-count');
                if (counter && data.count !== undefined) {
                    counter.textContent = data.count;
                }
            }
        })
        .catch(error => {
            console.error('Cart Error:', error);
            window.showErrorAlert('Update Failed!', 'Could not add to cart. Please try again.');
        });
};

/**
 * ❓ SweetAlert Confirmation Dialog for Cart Removal
 * @param {HTMLElement} buttonElement - The clicked 'X' button.
 */
window.confirmRemove = function (buttonElement) {

    // ✅ FIX: Read ID from data attribute
    const cartId = buttonElement.dataset.cartId;

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#7b68ee',
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'Yes, remove it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // User confirmed, now proceed with AJAX removal
            removeCartItem(cartId, buttonElement);
        }
    });
}
/**
 * ➖ NEW: Handles the actual AJAX DELETE request
 * @param {number} cartId - The ID of the cart item to remove.
 * @param {HTMLElement} buttonElement - The clicked 'X' button.
 */
window.removeCartItem = function (cartId, buttonElement) {

    const row = buttonElement.closest('tr');

    fetch(`/cart/remove/${cartId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'removed') {
                // Success Toast instead of Alert
                Swal.fire({
                    icon: 'success',
                    title: 'Removed!',
                    text: 'Your item has been removed from the cart.',
                    showConfirmButton: false,
                    timer: 1500
                });

                // 1. Remove row from DOM
                row.remove();

                const navbarCount = document.getElementById('cart-count');
                if (navbarCount) {
                    navbarCount.textContent = data.count;
                }

                // For now, prompt user to update cart manually or reload for totals fix.

            } else {
                Swal.fire('Error!', 'Could not remove item. Please try again.', 'error');
            }
        })
        .catch(error => {
            console.error('Remove Error:', error);
            Swal.fire('Error!', 'Something went wrong during removal.', 'error');
        });
}

// --- Initialization Logic ---

document.addEventListener('DOMContentLoaded', () => {
    // 1. Initialize global variables from hidden elements in Blade
    const authStatusElement = document.getElementById('auth-status');
    const csrfTokenElement = document.getElementById('csrf-token-data');

    if (authStatusElement) {
        window.IS_AUTHENTICATED = authStatusElement.getAttribute('data-is-auth') === 'true';
    }
    if (csrfTokenElement) {
        window.Laravel.csrfToken = csrfTokenElement.getAttribute('data-token');
    }
});

window.buyNow = function (button) {
    const route = button.getAttribute('data-route');
    const quantityInput = document.querySelector('.product-details-actions input[name="quantity"]');
    const quantity = quantityInput ? parseInt(quantityInput.value) : 1;
    const csrfToken = document.getElementById('csrf-token-data').getAttribute('data-token');

    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';

    fetch(route, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ quantity: quantity })
    })
        .then(response => {
            // Agar response 200 OK nahi hai, toh JSON parse karne se pehle handle karein.
            if (!response.ok) {
                throw new Error('Server returned non-200 status');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // ✅ YAHAN HAI WOH ZAROORI LINE (Step 1)
                // Item cart mein successfully add ho gaya hai, ab cart page par redirect karo.
                window.location.href = '/cart';
                // NOTE: Agar aapke cart page ka URL /cart/checkout hai, toh uske hisaab se change karein.

            } else {
                // Server ne success: false bheja
                Swal.fire({
                    icon: 'error',
                    title: 'Cart Error!',
                    text: data.message || 'Product could not be added to cart.',
                    confirmButtonColor: '#DB4444'
                });

                button.disabled = false;
                button.innerHTML = 'Buy Now';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Error hone par button ko wapas enable karein
            button.disabled = false;
            button.innerHTML = 'Buy Now';
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'An error occurred while adding to cart.',
                confirmButtonColor: '#DB4444'
            });
        });
}

// public/js/shop.js

// ... (existing functions like addToCart, toggleWishlist, etc.) ...

// ✅ NEW HELPER FUNCTION (Ensure this is defined in shop.js)
function updateNavbarCartCount(count) {
    const navbarCountElement = document.getElementById('cart-count');
    if (navbarCountElement) {
        navbarCountElement.textContent = count;
        // Optional: Hide/Show badge if count is 0
        navbarCountElement.style.display = (parseInt(count) > 0) ? 'inline-block' : 'none';
    }
}

// public/js/shop.js

// ✅ 1. HELPER FUNCTION: Navbar Count Update karne ke liye
function updateNavbarCartCount(count) {
    const navbarCountElement = document.getElementById('cart-count');
    if (navbarCountElement) {
        navbarCountElement.textContent = count;

        // Count 0 se zyada hone par hi show karein (agar shuru mein hidden ho toh)
        navbarCountElement.style.display = (parseInt(count) > 0) ? 'inline-block' : 'none';
        navbarCountElement.style.visibility = 'visible'; // Extra check for visibility
    }
}


/**
 * Wishlist se item ko Cart mein add karta hai aur phir wishlist se remove karta hai.
 */
window.moveFromWishlistToCart = function (button) {
    const productId = button.getAttribute('data-product-id');
    const cartRoute = button.getAttribute('data-cart-route');
    const wishlistRoute = button.getAttribute('data-wishlist-route');
    const csrfTokenElem = document.getElementById('csrf-token-data');
    const csrfToken = csrfTokenElem ? csrfTokenElem.getAttribute('data-token') : (window.Laravel && window.Laravel.csrfToken ? window.Laravel.csrfToken : '');

    const row = button.closest('tr');

    // Disable button & show spinner
    button.disabled = true;
    const originalHtml = button.innerHTML;
    button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';

    let newCartCount = 0;

    // Step 1 - Add to cart
    fetch(cartRoute, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ quantity: 1 })
    })
        .then(res => res.json())
        .then(cartData => {
            if (!cartData || cartData.status !== 'success') {
                throw new Error((cartData && cartData.message) ? cartData.message : 'Could not add to cart.');
            }

            // update cart count (value from server)
            newCartCount = cartData.count || 0;

            // visually fade the row first
            if (row) {
                row.classList.add('row-fade-out');
            }

            // wait for row fade animation to complete (optional)
            return new Promise((resolve) => setTimeout(resolve, 350));
        })
        .then(() => {
            // Step 2 - Remove from wishlist (toggle)
            return fetch(wishlistRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({}) // some toggle endpoints require a body; safe to send empty
            });
        })
        .then(res => res.json())
        .then(wishlistData => {
            if (!wishlistData || (wishlistData.status !== 'removed' && wishlistData.status !== 'toggled' && wishlistData.status !== 'success')) {
                throw new Error((wishlistData && wishlistData.message) ? wishlistData.message : 'Could not remove from wishlist.');
            }

            // If row still in DOM, remove it
            if (row && row.parentNode) {
                row.parentNode.removeChild(row);
            }

            // Update navbar counts
            const cartCounter = document.getElementById('cart-count');
            if (cartCounter) cartCounter.textContent = newCartCount;

            const wishlistCounter = document.getElementById('wishlist-count');
            if (wishlistCounter && wishlistData.count !== undefined) {
                wishlistCounter.textContent = wishlistData.count;
            }

            // Check remaining rows in wishlist table body
            const remaining = document.querySelectorAll("tbody tr").length;

            if (remaining === 0) {
                const tableBox = document.getElementById("wishlist-table-wrapper");
                const emptyBox = document.getElementById("wishlist-empty-box");

                // fade-out animation
                tableBox.style.opacity = "1";
                tableBox.style.transition = "opacity 0.3s";

                setTimeout(() => {
                    tableBox.style.opacity = "0";
                }, 10);

                // After fade, hide table and show empty UI
                setTimeout(() => {
                    tableBox.style.display = "none";
                    emptyBox.style.display = "block";
                }, 300);
            }

            // show success
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Moved to Cart!',
                    text: 'Item added to cart and removed from wishlist.',
                    confirmButtonColor: '#7b68ee'
                });
            }

        })
        .catch(err => {
            console.error('moveFromWishlistToCart error:', err);

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Operation failed',
                    text: (err && err.message) ? err.message : 'Something went wrong.',
                    confirmButtonColor: '#DB4444'
                });
            } else {
                alert((err && err.message) ? err.message : 'Something went wrong.');
            }
        })
        .finally(() => {
            // restore button
            button.disabled = false;
            button.innerHTML = originalHtml;
        });
};
