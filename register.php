<?php
$conn = new mysqli("localhost", "root", "", "users");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password']; // Securely hash password
    $stmt = $conn->prepare("INSERT INTO Login (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);
    if ($stmt->execute()) {
        echo "Registration successful. <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<form method="post" action="">
    <h2>Register</h2>
    Email: <input type="email" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Register">
</form>
