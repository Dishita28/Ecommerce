<?php
$conn = new mysqli("localhost", "root", "", "orders");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $product = $_POST['product'];
    $quantity = (int)$_POST['quantity'];
    $price = 200; // Assume fixed price for simplicity
    $total = $quantity * $price;

    $stmt = $conn->prepare("INSERT INTO orders (name, email, address, product, quantity, total) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssid", $name, $email, $address, $product, $quantity, $total);

    if ($stmt->execute()) {
        echo "<p>Order placed successfully!</p>";
    } else {
        echo "Error: " . $stmt->error;
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
    <input type="submit" value="Place Order">
</form>
