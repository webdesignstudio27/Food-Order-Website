<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
include_once 'product-action.php';
error_reporting(0);
session_start();

function function_alert()
{   
    echo "<script>alert('Thank you. Your Order has been placed!');</script>";
    echo "<script>window.location.replace('your_orders.php');</script>";
}

if (empty($_SESSION["user_id"])) {
    header('location:login.php');
} else {
    $item_total = 0; // Initialize item_total
    $tot_qty = 0; // Initialize total quantity
    $tot_price = 0; // Initialize total price

    // Initialize arrays to store all titles, quantities, and prices for a single order
    $titles = [];
    $quantities = [];
    $prices = [];

    foreach ($_SESSION["cart_item"] as $item) {
        // Calculate the total price
        $item_total += ($item["price"] * $item["quantity"]);

        // Update total quantity and total price
        $tot_qty += $item["quantity"];
        $tot_price += ($item["price"] * $item["quantity"]);

        // Append the item details to their respective arrays
        $titles[] = $item["title"];
        $quantities[] = $item["quantity"];
        $prices[] = $item["price"];
    }

    if ($_POST['submit']) {
        // Convert arrays to JSON format for storage
        $titles_json = json_encode($titles);
        $quantities_json = json_encode($quantities);
        $prices_json = json_encode($prices);

        // SQL query to insert combined data into users_orders
        $SQL = "INSERT INTO users_orders (u_id, title, quantity, price, tot_qty, tot_price, status) 
                VALUES ('" . $_SESSION["user_id"] . "', '$titles_json', '$quantities_json', '$prices_json', '$tot_qty', '$tot_price', '')";

        if (mysqli_query($db, $SQL)) {
            // Clear the cart and provide a success message
            unset($_SESSION["cart_item"]);
            $success = "Thank you. Your order has been placed!";
            function_alert($success);
        } else {
            // Handle errors during insertion
            echo "Error: " . mysqli_error($db);
        }
    }
}
?>


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>Checkout || Online Food Ordering System - Code Camp BD</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.7/js/tether.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
</head>

<body>
    <div class="site-wrapper">
        <!-- Navbar here -->

        <div class="page-wrapper">
            <div class="top-links">
                <div class="container">
                    <ul class="row links">
                        <li class="col-xs-12 col-sm-4 link-item"><span>1</span><a href="restaurants.php">Choose Restaurant</a></li>
                        <li class="col-xs-12 col-sm-4 link-item"><span>2</span><a href="#">Pick Your favorite food</a></li>
                        <li class="col-xs-12 col-sm-4 link-item active"><span>3</span><a href="checkout.php">Order and Pay</a></li>
                    </ul>
                </div>
            </div>

            <div class="container">
                <span style="color:green;">
                    <?php if (isset($success)) echo $success; ?>
                </span>
            </div>

            <div class="container m-t-30">
                <form action="" method="post" onsubmit="return validateCreditCard()">
                    <div class="widget clearfix">
                        <div class="widget-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="cart-totals margin-b-20">
                                        <div class="cart-totals-title">
                                            <h4>Cart Summary</h4>
                                        </div>
                                        <div class="cart-totals-fields">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td>Cart Subtotal</td>
                                                        <td> <?php echo "₹" . number_format($item_total, 2); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Delivery Charges</td>
                                                        <td>Free</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-color"><strong>Total</strong></td>
                                                        <td class="text-color"><strong> <?php echo "₹" . number_format($item_total, 2); ?></strong></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="payment-option">
                                        <ul class="list-unstyled">
                                            <li>
                                                <label class="custom-control custom-radio  m-b-20">
                                                    <input name="mod" id="radioStacked1" checked value="COD" type="radio" class="custom-control-input" onclick="togglePaymentFields()"> <span class="custom-control-indicator"></span> <span class="custom-control-description">Cash on Delivery</span>
                                                </label>
                                            </li>
                                            <li>
                                                <label class="custom-control custom-radio  m-b-10">
                                                    <input name="mod" type="radio" value="paypal" class="custom-control-input" onclick="togglePaymentFields()"> <span class="custom-control-indicator"></span> <span class="custom-control-description">Paypal <img src="images/paypal.jpg" alt="" width="90"></span>
                                                </label>
                                            </li>
                                            <li>
                                                <label class="custom-control custom-radio  m-b-10">
                                                    <input name="mod" type="radio" value="qrcode" class="custom-control-input" onclick="togglePaymentFields()"> <span class="custom-control-indicator"></span> <span class="custom-control-description">QR Code <img src="images/qr.png" alt="" height="25" width="25"></span>
                                                </label>
                                            </li>
                                        </ul>

                                        <!-- Credit Card Details (hidden by default) -->
                                        <div id="creditCardDetails" style="display: none;">
                                            <div class="form-group">
                                                <label for="creditCardNumber">Credit Card Number</label>
                                                <input type="text" id="creditCardNumber" class="form-control" placeholder="Enter your credit card number">
                                            </div>
                                            <div class="form-group">
                                                <label for="expiryDate">Expiry Date (MM/YY)</label>
                                                <input type="text" id="expiryDate" class="form-control" placeholder="MM/YY">
                                            </div>
                                            <div class="form-group">
                                                <label for="cvv">CVV</label>
                                                <input type="text" id="cvv" class="form-control" placeholder="Enter CVV">
                                            </div>
                                        </div>

                                        <!-- QR Code Section (hidden by default) -->
                                        <div id="qrCodeDetails" style="display: none; text-align: center; margin-top: 30px;">
                                            <h5>Scan the QR Code to make payment</h5>
                                            <canvas id="qrcode"></canvas> <!-- This canvas is where the QR code will be drawn -->
                                            <p>Total: <?php echo "₹" . number_format($item_total, 2); ?></p>
                                        </div>

                                        <p class="text-xs-center"> <input type="submit" value="Order" name="submit" class="btn theme-btn m-t-15"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        function togglePaymentFields() {
            const paymentMethod = document.querySelector('input[name="mod"]:checked').value;

            document.getElementById('creditCardDetails').style.display = 'none';
            document.getElementById('qrCodeDetails').style.display = 'none';

            if (paymentMethod === 'paypal') {
                // Implement PayPal payment integration here
                document.getElementById('creditCardDetails').style.display = 'block';
                document.getElementById('qrCodeDetails').style.display = 'none';
            } else if (paymentMethod === 'qrcode') {
                // Show QR Code
                document.getElementById('qrCodeDetails').style.display = 'block';
                var totalAmount = <?php echo $item_total; ?>;
                // Generate QR code dynamically based on the total amount
                QRCode.toCanvas(document.getElementById('qrcode'), `Payment of $${totalAmount} to Restaurant`, function(error) {
                    if (error) {
                        console.error('Error generating QR Code:', error);
                    } else {
                        console.log('QR Code generated successfully!');
                    }
                });

            } else {
                // Cash on Delivery is selected
                document.getElementById('creditCardDetails').style.display = 'none';
                document.getElementById('qrCodeDetails').style.display = 'none';
            }
        }

        function validateCreditCard() {
            const cardNumber = document.getElementById("creditCardNumber").value;
            const expiryDate = document.getElementById("expiryDate").value;
            const cvv = document.getElementById("cvv").value;

            const cardNumberPattern = /^[0-9]{16}$/;
            const expiryPattern = /^(0[1-9]|1[0-2])\/([0-9]{2})$/;
            const cvvPattern = /^[0-9]{3}$/;

            if (cardNumber && !cardNumber.match(cardNumberPattern)) {
                alert("Please enter a valid 16-digit credit card number.");
                return false;
            }

            if (expiryDate && !expiryDate.match(expiryPattern)) {
                alert("Please enter a valid expiry date in MM/YY format.");
                return false;
            }

            if (cvv && !cvv.match(cvvPattern)) {
                alert("Please enter a valid 3-digit CVV.");
                return false;
            }

            return true; // Allow form submission if all validations pass
        }
    </script>
</body>
</html>
