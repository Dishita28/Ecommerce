<?php
session_start();
$conn = new mysqli("localhost", "root", "", "catalog");

if (isset($_POST['add'])) $_SESSION['cart'][] = ['n' => $_POST['n'], 'p' => $_POST['p']];
if (isset($_POST['clear'])) unset($_SESSION['cart']);
if (isset($_POST['remove'])) unset($_SESSION['cart'][$_POST['id']]);

if (isset($_POST['placeorder'])) {
    header("Location: wallet.php");
    exit();
}

// Search
if (isset($_GET['q']) && $_GET['q'] !== '') {
    $q = $conn->real_escape_string($_GET['q']);
    $res = $conn->query("SELECT name, description, price, image FROM catalog WHERE name LIKE '%$q%' OR description LIKE '%$q%'");
} else {
    $res = $conn->query("SELECT name, description, price, image FROM catalog");
}
?>
<html>
<body>
<h2>Catalogue</h2>

<form method="get">
    <input type="text" name="q" placeholder="Search products..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
    <input type="submit" value="Search">
</form>

<?php if (!empty($_SESSION['cart'])) {
    $t = 0;
    foreach ($_SESSION['cart'] as $i => $c) {
        echo htmlspecialchars($c['n'])." - ₹".htmlspecialchars($c['p']);
        echo " <form method='post' style='display:inline;'>
                <input type='hidden' name='id' value='$i'>
                <button name='remove'>Remove</button></form><br>";
        $t += $c['p'];
    }
    echo "<b>Total: ₹$t</b><br>
    <form method='post'>
        <button name='clear'>Clear Cart</button>
        <button name='placeorder'>Place Order</button>
    </form><hr>";
} ?>

<?php
if ($res && $res->num_rows > 0) {
    while($r = $res->fetch_assoc()) { ?>
    <div style="border:1px solid #aaa; padding:10px; margin:10px; width:200px; display:inline-block;">
        <img src="<?= htmlspecialchars($r['image']) ?>" style="width:100%;height:200px;"><br>
        <b><?= htmlspecialchars($r['name']) ?></b><br>
        <?= htmlspecialchars($r['description']) ?><br>
        ₹<?= htmlspecialchars($r['price']) ?><br>
        <form method="post">
            <input type="hidden" name="n" value="<?= htmlspecialchars($r['name']) ?>">
            <input type="hidden" name="p" value="<?= $r['price'] ?>">
            <button name="add">Add to Cart</button>
        </form>
    </div>
<?php
    }
} else {
    echo "No products found.";
}
?>

</body>
</html>
