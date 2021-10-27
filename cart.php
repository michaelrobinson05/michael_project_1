<?php ob_start(); //sometimes header redirects dont work this fixes the problem

//includes the header
include "template.php";

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
<!--title-->
<title>Cart</title>

<!--formatting for the page-->
<link rel="stylesheet" href="css/orderForm.css">


<?php
//sets the correct time zone
date_default_timezone_set("Australia/Sydney");

//removes error at top of page on first load
$status = "";

//function to remove item from shopping cart
//if the user presses remove item button
if (isset($_POST['action']) && $_POST['action'] == "remove") {
    //if shopping cart is not empty
    if (!empty($_SESSION["shopping_cart"])) {
        //goes through each product and grabs the element out as $key
        foreach ($_SESSION["shopping_cart"] as $key => $value) {
            //if the item is the same as the element $key
            if ($_POST["code"] == $key) {
                //delete that session variable out of the shopping cart array
                unset($_SESSION["shopping_cart"][$key]);
                //output the status saying that the product was removed successfully
                $status = "<div class='box' style='color:green;'>Product is removed from your cart!</div>";
            }
            //if the item is not the same as $key
            //if the shopping cart is not empty
            if (empty($_SESSION["shopping_cart"]))
                //unset / delete the shopping_cart variable from the session / memory
                unset($_SESSION["shopping_cart"]);
        }
    }
}


//This code runs when the user changes the quantity for a product
//if the user changes the quantity
if (isset($_POST['action']) && $_POST['action'] == "change") {
    //for each row in the shopping cart repeat the following code
    foreach ($_SESSION["shopping_cart"] as &$value) {
        //checks if product is same as product that has been updated
        if ($value['code'] === $_POST["code"]) {
            //grabs the quantity that has been set and stores it into the shopping cart variable
            $value['quantity'] = $_POST["quantity"];
            break; // Stop the loop after we've found the product
        }
    }
}
 ?>

<!--displays the status box on page after removing a product-->
<div class="message_box"style="margin:10px 0px;">
<?php echo $status;?>
</div>

<div class="cart">
    <?php
    //if there is something in the shopping_cart variable
    if (isset($_SESSION["shopping_cart"])) {
    //resets the total price if there is a product in the shopping cart
    $total_price = 0;
    ?>
<!--    displays products in the cart in a table-->
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
        //for every product in shopping_cart create a row and repeat following code
        foreach ($_SESSION["shopping_cart"] as $product) {
            ?>
            <tr>
                <td>
                    <!--displays product image from folder-->
                    <img src='images/product_pictures/<?php echo $product["image"]; ?>' width="40"/>
                </td>
                <td>
                    <!--displays product name-->
                    <?php echo $product["productName"]; ?>
                    <form method='post' action=''>
<!--                            hidden product data-->
                            <input type='hidden' name='code' value="<?php echo $product["code"]; ?>"/>
                            <input type='hidden' name='action' value="remove"/>
<!--                            remove item button-->
                            <button type='submit' class='remove'>Remove Item</button>
                        </form>
                </td>
                <td>
                    <!--form for selecting quantity-->
                    <form method='post' action=''>
                        <input type='hidden' name='code' value="<?php echo $product["code"]; ?>"/>
                        <input type='hidden' name='action' value="change"/>
                        <!--select box-->
                        <select name='quantity' class='quantity' onChange="this.form.submit()">
                            <!--different options in the dropdown-->
                            <!--option 1-->
                            <option <?php if ($product["quantity"] == 1) echo "selected"; ?>
                                    value="1">1
                                    <!--the default option value is "1"-->
                            </option>
                            <!--option 2-->
                            <option <?php if ($product["quantity"] == 2) echo "selected"; ?>
                                    value="2">2
                            </option>
                            <!--option 3-->
                            <option <?php if ($product["quantity"] == 3) echo "selected"; ?>
                                    value="3">3
                            </option>
                            <!--option 4-->
                            <option <?php if ($product["quantity"] == 4) echo "selected"; ?>
                                    value="4">4
                            </option>
                            <!--option 5-->
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
                    <!--Subtotal price for product-->
                    <?php echo "$" . $product["price"] * $product["quantity"]; ?>
                </td>
            </tr>
            <?php
            //multiplying quantity by product price into a variable
            $total_price += ($product["price"] * $product["quantity"]);
            } //ends the foreach loop if
            ?>
            <tr>
                <td colspan="5" align="right">
                    <!--displays the total price of individual product-->
                    <strong>TOTAL: <?php echo "$" . $total_price; ?></strong>
                </td>
            </tr>
        </tbody>
    </table>
    <form method="post">
<!--        order now button-->
        <input type="submit" name="orderProducts" value="Order Now"/>
    </form>
    <?php
} //ends the if in shopping cart statement


//      Writing the order to the database      //
//if the order submit button if pressed
if(isset($_POST['orderProducts'])) {
    //if the user id is set / if the user is logged in
    if (isset($_SESSION['user_id'])){
        //creates a randomly generated order code starting with "ORDER"
        $orderNumber ="ORDER".substr(md5(uniqid(mt_rand(),true)), 0, 8);
        //for each product being submitted do the following
        foreach($_SESSION["shopping_cart"]as$row) {
            //putting session variables about the user into variables
            $customerID = $_SESSION["user_id"];
            $productCode = $row['code'];
            $quantity = $row['quantity'];
            $orderDate = date("Y-m-d h:i:sa");

            //write to the orderDetails table in database.
            $conn->exec("INSERT INTO orderDetails (orderCode,userID, productCode, orderDate, quantity) VALUES('$orderNumber','$customerID','$productCode','$orderDate', '$quantity')");
        }
    //clears the shopping cart
    $_SESSION["shopping_cart"] = [];
    header("location:invoice.php");

    } else { //if user is not logged in
        //login error popup message
        echo "<p><script type='text/javascript'>alert('You are not logged in! Please go to home page to log in or register an account.')</script></p>";

        //login here button takes the user to home page
        echo '<button id="Home_page" class="btn btn-primary" style="margin-left: 45%">Login Here</button>';?>
<!--        redirects the user to the home page id the button is pressed-->
    <script type="text/javascript">
        document.getElementById("Home_page").onclick = function () {
            location.href = "index.php";
        };
    </script>
    <?php
    }
}

ob_end_flush(); //sometimes header redirects dont work this fixes the problem
?>