<?php
include("connection/connect.php");
error_reporting(0);
session_start();

// Check if user is logged in
if (empty($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}

// Check if the order ID is passed in the URL
if (isset($_GET['order_id'])) {
    $order_id = mysqli_real_escape_string($db, $_GET['order_id']); // Sanitized input

    // Fetch order details
    $query = mysqli_query($db, "SELECT * FROM users_orders WHERE o_id = '$order_id' AND u_id = '" . $_SESSION['user_id'] . "'");
    $order = mysqli_fetch_array($query);

    if (!$order) {
        echo "<h3>Order not found!</h3>";
        exit();
    }

    // Fetch user details (if needed for the bill)
    $user_query = mysqli_query($db, "SELECT * FROM users WHERE u_id = '" . $_SESSION['user_id'] . "'");
    $user = mysqli_fetch_array($user_query);
} else {
    echo "<h3>Order ID is missing!</h3>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Generate Bill</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>My Orders</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .bill-container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 100px;
        }

        .bill-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .bill-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            align-items: center;
            text-align: center;
        }

        .bill-header p {
            font-size: 16px;
            font-weight: 500;
            color: #555;
            text-align: left;
        }

        .bill-header p strong {
            color: #000;
        }

        .bill-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .bill-table th,
        .bill-table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .bill-table th {
            background-color: #000000;
            color: #fff;
            font-size: 14px;
        }

        .bill-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .bill-table td {
            font-size: 14px;
        }

        .total-price-container {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            color: #333;
            text-align: right;
            
        }

        .btn-print {
            align-items: center;
            margin-top: 20px;
            background-color: #62A5FE;
            color: #fff;
            font-size: 16px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: inline-block;
            margin: 20px auto 0;
            width: fit-content;
            margin-left: 250px;
        }

        .btn-print:hover {
            background-color: #0056b3;
        }

        .btn-back {
            margin-top: 20px;
            background-color: #B0B0B0;
            color: #fff;
            font-size: 16px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: inline-block;
            margin: 20px auto 0;
            width: fit-content;
        }

        .btn-back:hover {
            background-color: #000000;
        }

        @media (max-width: 768px) {
            .bill-container {
                padding: 10px;
            }

            .bill-header h2 {
                font-size: 22px;
            }

            .bill-header p {
                font-size: 14px;
            }

            .bill-table th,
            .bill-table td {
                font-size: 12px;
            }

            .btn-print {
                width: 100%;
            }
        }

        @media print {
            .btn-print {
                display: none;
            }

            .bill-header {
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body>

    <div class="bill-container">
        <div class="bill-header">
            <h2>Bill ID : <?php echo $order['o_id']; ?></h2>
            <p><strong>Date:</strong> <?php echo $order['date']; ?></p>
            <p><strong>User:</strong> <?php echo $user['username']; ?></p>
            <p><strong>Address:</strong> <?php echo $user['address']; ?></p>
            <hr style="border: 2px solid black;">
        </div>

        <table class="bill-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price (Per Unit)</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $order_items_query = mysqli_query($db, "SELECT * FROM users_orders WHERE o_id = '$order_id'");
                $total_price = 0;

                if (mysqli_num_rows($order_items_query) > 0) {
                    while ($item = mysqli_fetch_array($order_items_query)) {
                        $titles = json_decode($item['title'], true);
                        $quantities = json_decode($item['quantity'], true);
                        $prices = json_decode($item['price'], true);

                        if (is_array($titles) && is_array($quantities) && is_array($prices) && count($titles) === count($quantities) && count($titles) === count($prices)) {
                            foreach ($titles as $index => $title) {
                                $quantity = $quantities[$index];
                                $price = $prices[$index];
                                $item_total = $quantity * $price;
                                $total_price += $item_total;

                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($title) . "</td>";
                                echo "<td>" . htmlspecialchars($quantity) . "</td>";
                                echo "<td>₹" . number_format($price, 2) . "</td>";
                                echo "<td>₹" . number_format($item_total, 2) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>Error: Invalid item data</td></tr>";
                        }
                    }
                } else {
                    echo "<tr><td colspan='4'>No items found for this order.</td></tr>";
                }

             
                $tax_percentage = 0;  

                
                if ($total_price > 1500) {
                    $tax_percentage = 10; 
                } elseif ($total_price >= 700 && $total_price <= 1500) {
                    $tax_percentage = 7; 
                } elseif ($total_price >= 300 && $total_price < 700) {
                    $tax_percentage = 5;  
                } elseif ($total_price < 300) {
                    $tax_percentage = 3;  
                }

                
                $tax_amount = ($total_price * $tax_percentage) / 100;
                $final_price = $total_price + $tax_amount;
                ?>

            </tbody>
        </table>

        
        <div class="total-price-container">
            <p><strong>Total Price:</strong> ₹<?php echo number_format($total_price, 2); ?></p>
            <p><strong>Tax (<?php echo $tax_percentage; ?>%):</strong> ₹<?php echo number_format($tax_amount, 2); ?></p>
            <p><strong>Final Total:</strong> ₹<?php echo number_format($final_price, 2); ?></p>
        </div>


        <button class="btn-print" onclick="window.print()">Print Bill</button>
        <a href="your_orders.php"><button class="btn-back">Back to Orders</button></a>
    </div>

</body>

</html>