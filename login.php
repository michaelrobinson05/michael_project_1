<?php
//includes the config page to connect to the database
require_once 'config.php';

//if the user presses the login button on home page
if (isset($_POST['login'])) {
    //gets the username and password and sanitises them as well
    $username = sanitise_data($_POST['username']);
    $password = sanitise_data($_POST['password']);

    //selects all user information from user with same username that was just submitted
    $query = $conn->query("SELECT COUNT(*) as count, * FROM `user` WHERE `username`='$username'");
    //puts the data into array to use later
    $row = $query->fetchArray();
    //stores if there is a row in the database that matches the username
    $count = $row['count'];
    //if there is a row in the database that matches the username
    if ($count > 0) {
        //if the passwords match
        if (password_verify($password, $row['password'])) {
            //sets session variables of hte users information for later use
            $_SESSION["user_id"] = $row[1];
            $_SESSION["name"] = $row[4];
            $_SESSION["username"] = $row[2];
            $_SESSION['level'] = $row[6];
            $_SESSION['profilePicture'] = $row[5];
            //redirects the user to their profile page
            header("location:profile.php");
        } else {
            //error message
            echo "<div class='alert alert-danger'>Invalid username or password</div>";
        }
    }
}
?>