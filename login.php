<?php
$conn = new mysqli("localhost", "root", "", "users");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT password FROM Login WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($stored_password);
    if ($stmt->fetch()) {
        if ($password === $stored_password) {
            echo "Login successful. Welcome, $username!";
        } else {
            echo "Invalid credentials.";
        }
    } else {
        echo "No such user.";
    }
}
?>
<form method="post" action="">
    <h2>Login</h2>
    Email: <input type="email" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
</form>
