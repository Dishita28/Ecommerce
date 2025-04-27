<?php
session_start();
$conn = new mysqli("localhost", "root", "", "orders");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if (empty($_SESSION['cart'])) {
    echo "Cart is empty. <a href='cart.php'>Back to Cart</a>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $products = json_encode($_SESSION['cart']);
    $total = array_sum(array_column($_SESSION['cart'], 'p'));

    $stmt = $conn->prepare("INSERT INTO orders (name, email, address, product, total) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssd", $name, $email, $address, $products, $total);

    if ($stmt->execute()) {
        $_SESSION['wallet_balance'] -= $total;
        unset($_SESSION['cart']);
        echo "<p>Order placed successfully! Wallet deducted ₹".number_format($total,2)."</p>";
        echo "<a href='cart.php'>Go back to Catalogue</a>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<h2>Order Form</h2>
<form method="post">
    Name: <input type="text" name="name" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Address: <textarea name="address" required></textarea><br><br>
    <input type="submit" value="Confirm Order">
</form>
