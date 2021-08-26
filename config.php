<?php
session_start();

$conn = new SQLite3("db/db_michael") or die ("unable to open database");

function createTable($sqlStmt, $tableName)
{
    global $conn;
    $stmt = $conn->prepare($sqlStmt);
    if ($stmt->execute()) {
        echo "<p style='color: green'>".$tableName. ": Table Created Successfully</p>";
    } else {
        echo "<p style='color: red'>".$tableName. ": Table Created Failure</p>";
    }
}

function adduser($userName, $unhashedPassword, $name, $profilePic, $accessLevel)
{   global $conn;

    $hashedPassword = password_hash($unhashedPassword, PASSWORD_DEFAULT);
    $sqlstmt = $conn->prepare("INSERT INTO user(userName, password, name, profilePic, accessLevel) VALUES (:userName, :hashedPassword, :name, :profilePic, :accessLevel)");
    $sqlstmt->bindValue(':userName', $userName);
    $sqlstmt->bindValue(':hashedPassword', $hashedPassword);
    $sqlstmt->bindValue(':name', $name);
    $sqlstmt->bindValue(':profilePic', $profilePic);
    $sqlstmt->bindValue(':accessLevel', $accessLevel);
    if ($sqlstmt->execute()) {
        echo "<p style='color: green'>user: ".$userName. ": Created Successfully</p>";
    } else {
        echo "<p style='color: red'>user: ".$userName. ": Created Failure</p>";
    }

}
$query = file_get_contents("sql/create_user.sql");
createTable($query, "User");

$query = file_get_contents("sql/create_order.sql");
createTable($query, "Order");

$query = file_get_contents("sql/create_product_table.sql");
createTable($query, "product");

$query = file_get_contents("sql/create_messages.sql");
createTable($query, "messages");

$query = file_get_contents("sql/create_invoice.sql");
createTable($query, "invoice");

$query=$conn->query("SELECT COUNT(*) as count FROM user");
$rowCount = $query->fetchArray();
$usercount = $rowCount["count"];

if ($usercount == 0) {
    adduser("admin", "admin", "Administrator", "admin.jpg", "Administrator");
    adduser("user", "user", "User", "user.jpg", "User");
    adduser("michael", "michael", "Michael", "michael.jpg", "User");
}
function add_product($productName, $Category, $Quantity, $Price, $Image, $Code) {
    global$conn;
    $sqlstmt = $conn->prepare("INSERT INTO product (productName, category, quantity, price, image, code) VALUES (:name, :category, :quantity, :price, :image, :code)");
    $sqlstmt->bindValue(':name', $productName);
    $sqlstmt->bindValue(':category', $Category);
    $sqlstmt->bindValue(':quantity', $Quantity);
    $sqlstmt->bindValue(':price', $Price);
    $sqlstmt->bindValue(':image', $Image);
    $sqlstmt->bindValue(':code', $Code);

    if($sqlstmt->execute()) {
        echo"<p style='color: green'>Product:".$productName.": Created Successfully</p>";
    }else{
        echo"<p style='color: red'>Product:".$productName.": Created Failure</p>";
    }
}

$query= $conn->query("SELECT COUNT(*) as count FROM product");
$rowCount = $query->fetchArray();
$productCount = $rowCount["count"];

if($productCount == 0) {
    add_product('Bottled Water','drink - food', 29, 1.10,'bottled_water.jpg','a4d84470');
    add_product('ToothPaste','cosmetics - hygiene ', 10, 4.55,'toothpaste.jpg','a5l84360');
    add_product('ToothBrush','cosmetics - hygiene ', 6, 10.00,'toothbrush.jpg','p4z95281');
    add_product('Nike Air Maxs','clothing - fashion ', 11, 210.00,'toothbrush.jpg','w7j90528');

}



?>