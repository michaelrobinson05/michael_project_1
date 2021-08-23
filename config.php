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

adduser("admin", "admin", "Administrator","admin.jpg","Administrator");
adduser("user", "user", "User","user.jpg","User");
adduser("michael", "michael", "Michael","michael.jpg","User");


?>