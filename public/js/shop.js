// shop.js

window.IS_AUTHENTICATED = false;
window.Laravel = { csrfToken: '' };

window.showErrorAlert = function (title, text) {
    Swal.fire({
        icon: 'error',
        title: title,
        text: text,
        confirmButtonColor: '#7b68ee'
    });
};

/**
 * @param {HTMLElement} element - The clicked element.
 */

window.toggleWishlist = function (element) {

    if (!window.IS_AUTHENTICATED) {
        window.showErrorAlert('Login Required!', 'Please login to use the wishlist.');
        return;
    }

    const isWishlistPage = window.location.pathname.includes('/wishlist');
    const isDrawerRemoveButton = element.classList.contains('remove-wishlist-drawer-btn');

    let url = element.dataset.toggleRoute;

    const productId = element.dataset.itemId || element.closest('[data-item-id]')?.dataset.itemId;

    let targetElement = null;

    if (isWishlistPage) {

        targetElement = element.closest("tr"); 
        url = element.dataset.toggleRoute;

        if (!url) {
            return window.showErrorAlert("Error!", "Toggle route missing!");
        }
    }

    else if (isDrawerRemoveButton) {

        targetElement = element.closest("li");

        if (!url) {
            return window.showErrorAlert("Error!", "Drawer route missing!");
        }
    }

    else {
        targetElement = element;
    }
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

            if (data.status === "added") {

                if (!isWishlistPage && !isDrawerRemoveButton) {
                    const icon = targetElement.querySelector("i");
                    icon.classList.remove("far");
                    icon.classList.add("fas", "is-wishlisted");
                }
            }

            else if (data.status === "removed") {

                if (isWishlistPage && targetElement) {
                    targetElement.classList.add("row-fade-out");
                    setTimeout(() => {
                        targetElement.remove();

                        if (document.querySelectorAll("tbody tr").length === 0) {
                            document.getElementById("wishlist-table-wrapper").style.display = "none";
                            document.getElementById("wishlist-empty-box").style.display = "block";
                        }
                    }, 300);
                }

                else if (isDrawerRemoveButton && targetElement) {
                    targetElement.remove();

                    const drawerList = document.getElementById("wishlist-drawer-content");
                    if (!drawerList.querySelector("li")) {
                        drawerList.innerHTML = '<p class="text-muted text-center p-5">Your wishlist is empty.</p>';
                    }

                    document.getElementById("wishlist-subtotal").textContent = "Rs. 0.00";
                }

                else {
                    const icon = targetElement.querySelector("i");
                    icon.classList.remove("fas", "is-wishlisted");
                    icon.classList.add("far");
                }

                if (productId) {
                    document
                        .querySelectorAll(`[data-toggle-route*="/${productId}"] i`)
                        .forEach(icon => {
                            icon.classList.remove("fas", "is-wishlisted");
                            icon.classList.add("far");
                        });
                }
            }

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
        window.showErrorAlert('Login Required!', 'Please login to view wishlist!');
        return;
    }

    const contentDiv = document.getElementById('wishlist-drawer-content');

    const routeDataElement = document.getElementById('route-data');
    const fetchUrl = routeDataElement ? routeDataElement.dataset.drawerUrl : '/wishlist/drawer-content';

    if (contentDiv.querySelector('.loading-state')) {
        return;
    }
    contentDiv.innerHTML = '<p class="text-muted text-center p-5 loading-state">Loading...</p>';
    document.getElementById('wishlist-subtotal').textContent = '...';

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

window.showCartToast = function (message) {
    const toastElement = document.getElementById('cartToast');
    const toastBody = document.getElementById('cartToastBody');

    if (toastElement && toastBody) {
        toastBody.textContent = message;
        const toast = new bootstrap.Toast(toastElement, {
            delay: 3000 
        });
        toast.show();
    }
}

/**
 * @param {HTMLElement} element 
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
 * @param {HTMLElement} buttonElement 
 */
window.confirmRemove = function (buttonElement) {

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
            removeCartItem(cartId, buttonElement);
        }
    });
}
/**
 * @param {number} cartId
 * @param {HTMLElement} buttonElement
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
                Swal.fire({
                    icon: 'success',
                    title: 'Removed!',
                    text: 'Your item has been removed from the cart.',
                    showConfirmButton: false,
                    timer: 1500
                });

                row.remove();

                const navbarCount = document.getElementById('cart-count');
                if (navbarCount) {
                    navbarCount.textContent = data.count;
                }


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
            if (!response.ok) {
                throw new Error('Server returned non-200 status');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {

                window.location.href = '/cart';

            } else {
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

function updateNavbarCartCount(count) {
    const navbarCountElement = document.getElementById('cart-count');
    if (navbarCountElement) {
        navbarCountElement.textContent = count;
        navbarCountElement.style.display = (parseInt(count) > 0) ? 'inline-block' : 'none';
    }
}


function updateNavbarCartCount(count) {
    const navbarCountElement = document.getElementById('cart-count');
    if (navbarCountElement) {
        navbarCountElement.textContent = count;

        navbarCountElement.style.display = (parseInt(count) > 0) ? 'inline-block' : 'none';
        navbarCountElement.style.visibility = 'visible';
    }
}

window.moveFromWishlistToCart = function (button) {
    const productId = button.getAttribute('data-product-id');
    const cartRoute = button.getAttribute('data-cart-route');
    const wishlistRoute = button.getAttribute('data-wishlist-route');
    const csrfTokenElem = document.getElementById('csrf-token-data');
    const csrfToken = csrfTokenElem ? csrfTokenElem.getAttribute('data-token') : (window.Laravel && window.Laravel.csrfToken ? window.Laravel.csrfToken : '');

    const row = button.closest('tr');

    button.disabled = true;
    const originalHtml = button.innerHTML;
    button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';

    let newCartCount = 0;

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

            newCartCount = cartData.count || 0;

            if (row) {
                row.classList.add('row-fade-out');
            }

            return new Promise((resolve) => setTimeout(resolve, 350));
        })
        .then(() => {
            return fetch(wishlistRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            });
        })
        .then(res => res.json())
        .then(wishlistData => {
            if (!wishlistData || (wishlistData.status !== 'removed' && wishlistData.status !== 'toggled' && wishlistData.status !== 'success')) {
                throw new Error((wishlistData && wishlistData.message) ? wishlistData.message : 'Could not remove from wishlist.');
            }

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

            const remaining = document.querySelectorAll("tbody tr").length;

            if (remaining === 0) {
                const tableBox = document.getElementById("wishlist-table-wrapper");
                const emptyBox = document.getElementById("wishlist-empty-box");

                tableBox.style.opacity = "1";
                tableBox.style.transition = "opacity 0.3s";

                setTimeout(() => {
                    tableBox.style.opacity = "0";
                }, 10);

                setTimeout(() => {
                    tableBox.style.display = "none";
                    emptyBox.style.display = "block";
                }, 300);
            }

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
