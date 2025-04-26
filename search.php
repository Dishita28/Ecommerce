<?php
session_start();
$conn = new mysqli("localhost", "root", "", "catalog");

// Search handling
if (isset($_GET['q']) && $_GET['q'] !== '') {
    $q = $conn->real_escape_string($_GET['q']);
    $res = $conn->query("SELECT name, description, price, image FROM catalog WHERE name LIKE '%$q%' OR description LIKE '%$q%'");
} else {
    $res = $conn->query("SELECT name, description, price, image FROM catalog");
}
?>
<html>
<body>
<h2>Search Page</h2>

<form method="get">
    <input type="text" name="q" placeholder="Search products..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
    <input type="submit" value="Search">
</form>

<?php
if ($res && $res->num_rows > 0) {
    while($r = $res->fetch_assoc()) { ?>
    <div style="border:1px solid #aaa; padding:10px; margin:10px; width:200px; display:inline-block;">
        <img src="<?= htmlspecialchars($r['image']) ?>" style="width:100%;height:200px;"><br>
        <b><?= htmlspecialchars($r['name']) ?></b><br>
        <?= htmlspecialchars($r['description']) ?><br>
        ₹<?= htmlspecialchars($r['price']) ?><br>
    </div>
<?php
    }
} else {
    echo "No products found.";
}
?>

</body>
</html>
