<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayPal Checkout</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Make sure this file exists -->
    <script src="https://www.paypal.com/sdk/js?client-id=your-client-id&components=buttons"></script>

    <style>
        /* Your existing CSS goes here */
    </style>
</head>
<body>
    <h2>Complete Your Payment</h2>
    <p>Select a payment method:</p>
    <ul>
        <li onclick="showCreditCardForm()">Credit Card</li>
        <li onclick="showQRCode()">QR Code</li>
    </ul>

    <!-- Credit Card Payment Form -->
    <div class="payment-form" id="credit-card-form">
        <h3>Enter Credit Card Details</h3>
        <form id="credit-card-details">
            <input type="text" placeholder="Card Number" required>
            <input type="text" placeholder="Cardholder Name" required>
            <input type="text" placeholder="Expiry Date" required>
            <input type="text" placeholder="CVV" required>
            <button type="submit">Submit Payment</button>
        </form>
    </div>

    <!-- QR Code Section -->
    <div class="qr-code-container" id="qr-code-container">
        <h3>Scan this QR Code to pay</h3>
        <div id="qr-code"></div>
    </div>

    <!-- PayPal Integration -->
    <div id="paypal-button-container"></div>

    <script>
        // Functions should be defined here before being used in onclick
        function showCreditCardForm() {
            document.getElementById('credit-card-form').style.display = 'block';
            document.getElementById('qr-code-container').style.display = 'none';
            document.getElementById('paypal-button-container').style.display = 'none';
        }

        function showQRCode() {
            document.getElementById('credit-card-form').style.display = 'none';
            document.getElementById('qr-code-container').style.display = 'block';
            document.getElementById('paypal-button-container').style.display = 'none';

            // Auto-generate QR code based on order price
            const qrCodeContainer = document.getElementById('qr-code');
            const amount = item_total; // The dynamic amount

            // Example QR code generation (use your QR code library here)
            qrCodeContainer.innerHTML = `<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent('Pay ' + amount)}" alt="QR Code">`;
        }

        // Make sure to echo the PHP variable properly inside the script
        var item_total = <?php echo json_encode($item_total); ?>; // Ensure PHP variable is properly passed into JS

        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: item_total // Use the dynamic amount
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert('Transaction completed by ' + details.payer.name.given_name);
                    alert("Your payment was successful! Order details have been recorded.");
                });
            }
        }).render('#paypal-button-container');
    </script>

</body>
</html>
