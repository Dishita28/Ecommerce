<?php
session_start();

// Initialize wallet balance if not set
if (!isset($_SESSION['wallet_balance'])) {
    $_SESSION['wallet_balance'] = 1000; // Initial wallet balance
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['refill_amount']) && isset($_POST['payment_method'])) {
    $refill_amount = floatval($_POST['refill_amount']);
    $payment_method = $_POST['payment_method'];

    if ($refill_amount > 0 && in_array($payment_method, ['credit_card', 'paypal'])) {
        $_SESSION['wallet_balance'] += $refill_amount;
        $_SESSION['success_message'] = "Wallet refilled successfully using $payment_method!";
    } else {
        $_SESSION['error_message'] = "Please enter a valid amount and select a payment method.";
    }
    header("Location: wallet.php"); // <--- REDIRECT
    exit();
}

// Set messages if available
$wallet_balance = $_SESSION['wallet_balance'];
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;

// Clear messages after showing once
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>

<h2>My Wallet</h2>
<p>Wallet Balance: ₹<?= number_format($wallet_balance, 2) ?></p>

<?php if ($success_message): ?>
    <p style="color: green;"><?= $success_message ?></p>
<?php elseif ($error_message): ?>
    <p style="color: red;"><?= $error_message ?></p>
<?php endif; ?>

<h3>Refill Wallet</h3>
<form method="post" action="">
    Enter Amount to Refill: <input type="number" name="refill_amount" step="0.01" min="1" required><br><br>
    Select Payment Method:
    <select name="payment_method" required>
        <option value="">Select Payment Method</option>
        <option value="credit_card">Credit Card</option>
        <option value="paypal">PayPal</option>
    </select><br><br>
    <input type="submit" value="Refill">
</form>
