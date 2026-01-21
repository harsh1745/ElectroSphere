
fetch('/checkout/place-order', {
})
    .then(response => response.json())
    .then(data => {
        if (data.success) {

            const orderId = data.order_id;

            window.location.href = `/invoice/${orderId}`;

        } else {
            showErrorAlert(data.message);
        }
    })
    .catch(error => {
        console.error('Order placement failed:', error);
    });