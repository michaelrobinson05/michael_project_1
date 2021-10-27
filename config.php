<?php
//starts the user's session when they open the page
session_start();

//connects to database
$conn = new SQLite3("db/Db_User") or die ("unable to open database");

//function to create default tables into database
function createTable($sqlStmt, $tableName)
{
    global $conn;
    $stmt = $conn->prepare($sqlStmt);
    if ($stmt->execute()) {
        //success message (commented out)
        //echo "<p style='color: green'>" . $tableName . ": Table Created Successfully</p>";
    } else {
        //error message
        echo "<p style='color: red'>" . $tableName . ": Table Created Failure</p>";
    }
}

//    executes the SQL in the saved SQL files and executes the createTable function to make a new table
//creates user table
$createUserTableQuery = file_get_contents("sql/create-user.sql");
createTable($createUserTableQuery, "User");

//creates products table
$createProductsTableQuery = file_get_contents("sql/create-products.sql");
createTable($createProductsTableQuery, "Products");

//creates order details table
$createOrderDetailsTableQuery = file_get_contents("sql/create-orderDetails.sql");
createTable($createOrderDetailsTableQuery, "Order Details");

/* commented out tables that are not needed yet
//creates messages table
$createMessagingTableQuery = file_get_contents("sql/create-messaging.sql");
createTable($createMessagingTableQuery, "Messages");
*/


//function to add default users to database
function addUser($username, $unhashedPassword, $name, $profilePic, $accessLevel) {
    global $conn;
    //hashes the password to make it secure
    $hashedPassword = password_hash($unhashedPassword, PASSWORD_DEFAULT);
    //prepares query to insert data into table
    $sqlstmt = $conn->prepare("INSERT INTO user (username, password, name, profilePic, accessLevel) VALUES (:userName, :hashedPassword, :name, :profilePic, :accessLevel)");
    //binds values to variables; safer method to insert users
    $sqlstmt->bindValue(':userName', $username);
    $sqlstmt->bindValue(':hashedPassword', $hashedPassword);
    $sqlstmt->bindValue(':name', $name);
    $sqlstmt->bindValue(':profilePic', $profilePic);
    $sqlstmt->bindValue(':accessLevel', $accessLevel);
    //displays success message or error message
    if ($sqlstmt->execute()) {
        //echo "<p style='color: green'>User: ".$username. ": Created Successfully</p>";
    } else {
        //user created error message
        echo "<p style='color: red'>User: ".$username. ": Created Failure</p>";
    }
}

//gets the count of rows in user table to count how many accounts
$query = $conn->query("SELECT COUNT(*) as count FROM user");
//stores data into variable array for use later
$rowCount = $query->fetchArray();
//gets the count of rows
$userCount = $rowCount["count"];

//if there are no rows (users) in the users table this inserts users using the addUser function
if ($userCount == 0) {
    addUser("admin", "admin", "Administrator", "admin.png", "Administrator");
    addUser("user", "user", "User", "user.png", "User");
}

//function to add products into database
function addProduct($productName, $category, $quantity, $price, $image, $code) {
    global $conn;
    //prepares SQL code to add products to products table in database
    $sqlstmt = $conn->prepare("INSERT INTO products (productName, category, quantity, price, image, code) VALUES (:productName, :category, :quantity, :price, :image, :code)");
    //binds values to variables; safer method to insert users
    $sqlstmt->bindValue(':productName', $productName);
    $sqlstmt->bindValue(':category', $category);
    $sqlstmt->bindValue(':quantity', $quantity);
    $sqlstmt->bindValue(':price', $price);
    $sqlstmt->bindValue(':image', $image);
    $sqlstmt->bindValue(':code', $code);
    //executes the SQL code above
    if ($sqlstmt->execute()) {
        //success message (commented out)
        //echo "<p style='color: blue'>Product: ".$productName. ": Created Successfully</p>";
    } else {
        //error message
        echo "<p style='color: red'>Product: ".$productName. ": Created Failure</p>";
    }
}

//gets the count of rows in products table
$query = $conn->query("SELECT COUNT(*) as count FROM products");
//stores data into variable array for use later
$rowCount = $query->fetchArray();
//stores the count of rows
$productCount = $rowCount["count"];

//if there are no rows (products) inserts products using the addProduct function
if ($productCount == 0){
    //Cat category
    addProduct("Cat 1", "Cat", 10, 20, "Cat_01.jpg", "37c7ie3");
    addProduct("Cat 2", "Cat", 10, 30, "Cat_02.jpg", "sj6ksa9");

    //Dog category
    addProduct("Dog 1", "Dog", 10, 90, "Dog_01.png", "CarAb3n");
    addProduct("Dog 2", "Dog", 10, 80, "Dog_02.jpg", "H01dM3J");

    //Pig category
    addProduct("Pig 1", "Pig", 10, 20, "Pig_01.jpg", "akisk4s");
    addProduct("Pig 2", "Pig", 10, 20, "Pig_02.jpg", "km538vc");

    //Dat category
    addProduct("Rat 1", "Rat", 10, 10, "Rat_01.jpg", "Gg3raed");
    addProduct("Rat 2", "Rat", 10, 10, "Rat_02.jpg", "l0rdfqd");


    //addProduct("", "", "", "", "", "");
}
?>