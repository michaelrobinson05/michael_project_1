<?php include "template.php"; ?>
<?php
/**
 * Shopping Cart.
 * Displays (and allows edits) of the items that the user has entered into their cart.
 * On submit, writes it to the orderDetails table.
 * Additionally updates messaging table to send message to admin to indicates order has been made.
 *
 * "Defines" the conn variable, removing the undefined variable errors.
 * @var SQLite3 $conn
 */
?>
<title>Cart</title>

<link rel="stylesheet" href="css/orderForm.css">

<?php
date_default_timezone_set("Australia/Sydney");
$status = "";
if (isset($_POST['action']) && $_POST['action'] == "remove") {
    if (!empty($_SESSION["shopping_cart"])) {
        foreach ($_SESSION["shopping_cart"] as $key => $value) {
            if ($_POST["code"] == $key) {
                unset($_SESSION["shopping_cart"][$key]);
                $status = "<div class='box' style='color:red;'>
      Product is removed from your cart!</div>";
            }
            if (empty($_SESSION["shopping_cart"]))
                unset($_SESSION["shopping_cart"]);
        }
    }
}
//This code runs when the quantity changes for a row
if (isset($_POST['action']) && $_POST['action'] == "change") {
    foreach ($_SESSION["shopping_cart"] as &$value) {
        if ($value['code'] === $_POST["code"]) {
            $value['quantity'] = $_POST["quantity"];
            break; // Stop the loop after we've found the product
        }
    }
}
?>

<div class="message_box"style="margin:10px 0px;">
    <?php echo $status;?>
</div>

<div class="cart">
    <?php
    if (isset($_SESSION["shopping_cart"])) {
        $total_price = 0;
        ?>
        <table class="table">
            <tbody>
            <tr>
                <td></td>
                <td>ITEM NAME</td>
                <td>QUANTITY</td>
                <td>UNIT PRICE</td>
                <td>ITEMS TOTAL</td>
            </tr>
            <?php
            foreach ($_SESSION["shopping_cart"] as $product) {
                ?>
                <tr>
                    <td>
                        <img src='images/productImages/<?php echo $product["image"]; ?>' width="50" height="40"/>
                    </td>
                    <td>
                        <?php echo $product["productName"]; ?> <br>
                        <form method='post' action=''>
                            <input type='hidden' name='code' value="<?php echo $product["code"]; ?>"/>
                            <input type='hidden' name='action' value="remove"/>
                            <button type='submit' class='remove'>Remove Item</button>
                        </form>
                    </td>
                    <td>
                        <form method='post' action=''>
                            <input type='hidden' name='code' value="<?php echo $product["code"]; ?>"/>
                            <input type='hidden' name='action' value="change"/>
                            <select name='quantity' class='quantity' onChange="this.form.submit()">
                                <option <?php if ($product["quantity"] == 1) echo "selected"; ?>
                                    value="1">1
                                </option>
                                <option <?php if ($product["quantity"] == 2) echo "selected"; ?>
                                    value="2">2
                                </option>
                                <option <?php if ($product["quantity"] == 3) echo "selected"; ?>
                                    value="3">3
                                </option>
                                <option <?php if ($product["quantity"] == 4) echo "selected"; ?>
                                    value="4">4
                                </option>
                                <option <?php if ($product["quantity"] == 5) echo "selected"; ?>
                                    value="5">5
                                </option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <!--Individual product price-->
                        <?php echo "$" . $product["price"]; ?>
                    </td>
                    <td>
                        <!-- Subtotal for product-->
                        <?php echo "$" . $product["price"] * $product["quantity"]; ?>
                    </td>
                </tr>
                <?php


                $total_price += ($product["price"] * $product["quantity"]);
            }
            ?>
            <tr>
                <td colspan="5" align="right">
                    <strong>TOTAL: <?php echo "$" . $total_price; ?></strong>
                </td>
            </tr>



            </tbody>
        </table>

        <form method="post">
            <input type="submit" name="orderProducts" value="Order Now"/>
        </form>
        <?php
    }

    if (isset($_POST['orderProducts'])){

        //writing order to database
    $orderNumber ="ORDER".substr(md5(uniqid(mt_rand(),true)), 0, 8);
foreach($_SESSION["shopping_cart"]as$row) {
            $customerID = $_SESSION["user_id"];
            $productCode = $row['code'];
            $quantity = $row['quantity'];
            $orderDate =date("Y-m-d h:i:sa");

// Write to the Db.
$conn->exec("INSERT INTO orderDetails (orderCode, user_id, product_id, date, quantity) VALUES('$orderNumber','$customerID','$productCode','$orderDate', '$quantity')");

        }
    $_SESSION["shopping_cart"] = [];

    }
    ?>
