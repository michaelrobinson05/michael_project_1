<?php include "template.php"; ?>
    <title>Invoicing</title>

<li class="nav-item">
    <a class="nav-link"href="invoice.php">Invoices</a>
</li>
<?php
/**
 * Invoicing page
 * This page is used for different cases:
 * 1) Users to view their "open" orders as a list.
 * 2) Users to view invoices from individual orders (using the order variable in url)
 * 3) Inform users if they have not previously made any orders.
 * 4) Administrators to view all orders.
 * 5) If user is not logged in, then redirect them to index.php
 *
 * "Defines" the conn variable, removing the undefined variable errors.
 * @var SQLite3 $conn
 */
?>

<?php
if (empty($_GET["order"])) { // Showing the list of open order (case 1)
    echo "<h1 class='text-primary'>Invoices</h1>";
    echo "<h2 class='text-primary'>Choose your invoice number below.</h2>";
    if (isset($_SESSION["user_id"])) { // Case 1 or 3?
        $userID = $_SESSION["user_id"];
        $query = $conn->query("SELECT orderCode FROM orderDetails WHERE orderDetails_id='$userID' AND status='OPEN'");
        $count = $conn->querySingle("SELECT orderCode FROM `orderDetails` WHERE orderDetails_id='$userID' AND status='OPEN'");

        $orderCodesForUser = [];
        if ($count > 0){  // Has the User made orders previously? Case 1
            while ($data = $query->fetchArray()) {
                $orderCode = $data[0];
                array_push($orderCodesForUser, $orderCode);
            }
            echo "<p>";

            //Gets the unique order numbers from the extracted table above.
            $unique_orders = array_unique($orderCodesForUser);
            // Produce a list of links of the Orders for the user.
            foreach ($unique_orders as $order_ID) {
                echo "<p><a href='invoice.php?order=" . $order_ID . "'>Order : " . $order_ID . "</a></p>";
            }
        }else{ // Case 3
            echo "<p class='alert-danger'>You don't have any open orders. Please make an order to view them</p>";
        }
    }else {// Case 5
        header("Location:index.php");
    }
}

?>


