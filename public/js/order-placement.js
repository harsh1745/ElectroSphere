// order-placement.js or similar file

fetch('/checkout/place-order', {
    // ... POST Request details ...
})
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // ✅ FIX: Server se success milne ke baad redirect karein
            // Server ne order_id bheja hoga response mein
            const orderId = data.order_id;

            window.location.href = `/invoice/${orderId}`;
            // Ya phir: window.location.href = data.redirect_url; (Agar server redirect URL bhejta hai)

        } else {
            // Error handling
            showErrorAlert(data.message);
        }
    })
    .catch(error => {
        // Network ya Fatal error
        console.error('Order placement failed:', error);
    });