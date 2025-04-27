<?php
session_start();

// Connect database
$conn = new mysqli("localhost", "root", "", "orders");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Initialize wallet if not set
if (!isset($_SESSION['wallet_balance'])) {
    $_SESSION['wallet_balance'] = 1000; // default wallet balance
}
$wallet_balance = $_SESSION['wallet_balance'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $product = $_POST['product'];
    $quantity = (int)$_POST['quantity'];
    $payment_method = $_POST['payment_method'];
    
    $price = 200; // Assume fixed price for simplicity
    $total = $quantity * $price;

    if ($payment_method === 'wallet') {
        if ($wallet_balance >= $total) {
            $_SESSION['wallet_balance'] -= $total; // Deduct from wallet
            $wallet_balance = $_SESSION['wallet_balance'];
            
            // Insert order
            $stmt = $conn->prepare("INSERT INTO orders (name, email, address, product, quantity, total) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssid", $name, $email, $address, $product, $quantity, $total);

            if ($stmt->execute()) {
                echo "<p>Order placed successfully and paid using Wallet! Remaining Balance: ₹" . number_format($wallet_balance, 2) . "</p>";
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "<p style='color:red;'>Error: Not enough balance in wallet!</p>";
        }
    } else {
        // For now, just allow other payment methods without wallet deduction
        $stmt = $conn->prepare("INSERT INTO orders (name, email, address, product, quantity, total) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssid", $name, $email, $address, $product, $quantity, $total);

        if ($stmt->execute()) {
            echo "<p>Order placed successfully using $payment_method!</p>";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>

<h2>Order Form</h2>
<form method="post" action="">
    Name: <input type="text" name="name" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Address: <textarea name="address" required></textarea><br><br>
    Product: 
    <select name="product" required>
        <option value="Product A">Product A</option>
        <option value="Product B">Product B</option>
        <option value="Product C">Product C</option>
    </select><br><br>
    Quantity: <input type="number" name="quantity" min="1" required><br><br>

    Payment Method: 
    <select name="payment_method" required>
        <option value="">Select Payment Method</option>
        <option value="wallet">Wallet (Balance: ₹<?= number_format($wallet_balance, 2) ?>)</option>
        <option value="credit_card">Credit Card</option>
        <option value="paypal">PayPal</option>
    </select><br><br>

    <input type="submit" value="Place Order">
</form>
