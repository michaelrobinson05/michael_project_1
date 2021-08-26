
<?php include "template.php";
/**
 * @var SQLite3 $conn
 */
?>

<title>Order Form</title>
<link rel="stylesheet" href="css/orderForm.css">

<h1 class="text-primary">Order Form</h1>

<?php
$status = "";
if (isset($_POST['code']) && $_POST['code'] != "") {
    $code = $_POST['code'];
    $row = $conn->querySingle("SELECT * FROM product WHERE code='$code'", true);
    $name = $row['productName'];
    $price = $row['price'];
    $image = $row['image'];

    $cartArray = array(
        $code => array(
            'productName' => $name,
            'code' => $code,
            'price' => $price,
            'quantity' => 1,
            'image' => $image)
    );

    if (empty($_SESSION["shopping_cart"])) {
        $_SESSION["shopping_cart"] = $cartArray;
        $status = "
<div class='box'>Product is added to your cart!</div>";
    } else {
        $array_keys = array_keys($_SESSION["shopping_cart"]);
        if (in_array($code, $array_keys)) {
            $status = "
<div class='box' style='color:red;'>
    Product is already added to your cart!
</div>";
        } else {
            $_SESSION["shopping_cart"] = array_merge(
                $_SESSION["shopping_cart"],
                $cartArray
            );
            $status = "
<div class='box'>Product is added to your cart!</div>";
        }
    }
}
?>

<div class="message_box" style="margin:10px 0px;">
    <?php echo $status; ?>
</div>

<?php
if (!empty($_SESSION["shopping_cart"])) {
    $cart_count = count(array_keys($_SESSION["shopping_cart"]));
    ?>
    <div class="cart_div">
        <a href="cart.php"><img src="images/cart-icon.png"/> Cart<span>
<?php echo $cart_count; ?></span></a>
    </div>
    <?php
}
?>

<?php
$result = $conn->query("SELECT * FROM product");
while ($row = $result->fetchArray()) {
    echo "<div class='product_wrapper'>
    <form method ='post' action =''>
    <input type='hidden' name='code' value=" . $row['code'] . " />
    <div class='image'><img src='images/productImages/" . $row['image'] . "' width='100' height='100'/></div>
    <div class='name'>" . $row['productName'] . "</div>
    <div class='price'>$" . $row['price'] . "</div>
    <button type='submit' class='buy'>Add to Cart</button>
    </form>
    </div>";
}

?>