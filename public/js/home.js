/* this months sale section */

function toggleWishlist(event, el) {
    event.stopPropagation();
    el.classList.toggle("fa-solid");
    el.classList.toggle("fa-regular");
    el.classList.toggle("active");
}

// Add to cart alert (you can integrate later)
function addToCart(event) {
    event.stopPropagation();
    alert("✅ Product added to cart!");
}

// Product details redirect
function openProduct(id) {
    window.location.href = `product-details.html?id=${id}`;
}
/* end of this months sale section */

/* Category section */

// Redirect to products page with category parameter
function openCategory(categoryName) {
    window.location.href = `products.html?category=${encodeURIComponent(
        categoryName
    )}`;
}

// Scroll Control for Category Row
const scrollContainer = document.getElementById("categoryScroll");
const leftBtn = document.querySelector(".arrow-btn.left");
const rightBtn = document.querySelector(".arrow-btn.right");

leftBtn.addEventListener("click", () => {
    scrollContainer.scrollBy({ left: -400, behavior: "smooth" });
});

rightBtn.addEventListener("click", () => {
    scrollContainer.scrollBy({ left: 400, behavior: "smooth" });
});

/* Our Products Section */

function addToCart(event) {
    event.preventDefault();
    event.stopPropagation();
    alert("✅ Product added to cart!");
}

function openProduct(productId) {
    window.location.href = `product-details.html?id=${productId}`;
}

function toggleWishlist(event, element) {
    event.stopPropagation();
    element.classList.toggle("active");
    element.classList.toggle("fa-solid");
    element.classList.toggle("fa-regular");
}
