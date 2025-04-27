<?php
session_start();

// Initialize wallet balance
if (!isset($_SESSION['wallet_balance'])) {
    $_SESSION['wallet_balance'] = 1000;
}

if (isset($_POST['refill_amount']) && isset($_POST['payment_method'])) {
    $amount = floatval($_POST['refill_amount']);
    $method = $_POST['payment_method'];

    if ($amount > 0 && in_array($method, ['credit_card', 'paypal'])) {
        $_SESSION['wallet_balance'] += $amount;
        $_SESSION['success_message'] = "Wallet refilled successfully!";
    } else {
        $_SESSION['error_message'] = "Invalid amount or payment method.";
    }
    header("Location: wallet.php");
    exit();
}

if (isset($_POST['placeorder'])) {
    $total = 0;
    foreach ($_SESSION['cart'] as $c) {
        $total += $c['p'];
    }
    if ($_SESSION['wallet_balance'] >= $total) {
        header("Location: order_form.php");
        exit();
    } else {
        $error_message = "Not enough balance. Please refill.";
    }
}

if (isset($_POST['back'])) {
    header("Location: cart.php");
    exit();
}

$wallet_balance = $_SESSION['wallet_balance'];
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>

<h2>My Wallet</h2>
<p>Wallet Balance: ₹<?= number_format($wallet_balance,2) ?></p>

<?php if ($success_message): ?>
    <p style="color:green;"><?= $success_message ?></p>
<?php endif; ?>
<?php if ($error_message): ?>
    <p style="color:red;"><?= $error_message ?></p>
<?php endif; ?>

<h3>Refill Wallet</h3>
<form method="post">
    Enter Amount: <input type="number" name="refill_amount" step="0.01" min="1" required><br><br>
    Payment Method:
    <select name="payment_method" required>
        <option value="">Select</option>
        <option value="credit_card">Credit Card</option>
        <option value="paypal">PayPal</option>
    </select><br><br>
    <input type="submit" value="Refill">
</form>

<hr>

<form method="post">
    <button name="placeorder">Place Order</button>
    <button name="back">Back to Cart</button>
</form>
