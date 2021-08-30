<?php include "template.php";
/**
 *  This is the user's profile page.
 * It shows the Users details including picture, and a link to edit the details.
 *
 * @var SQLite3 $conn
 */

?>
<title>Edit your profile</title>

<h1 class='text-primary'>Edit your Profile</h1>


<?php
if (isset($_SESSION["username"])) {

    $username = $_SESSION["username"];
    $userId = $_SESSION["user_id"];

    $query = $conn->query("SELECT * FROM user WHERE username='$username'");
    $userData = $query->fetchArray();
    $username = $userData[1];
    $password = $userData[2];
    $name = $userData[3];
    $profilePic = $userData[4];
    $accessLevel = $userData[5];

    echo $username . "<p>";
    echo $password . "<p>";
    echo $name . "<p>";
    echo $profilePic . "<p>";
    echo $accessLevel . "<p>";
}else{
    header("location:index.php");


}
?>

<div class ="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <h3>Username :  <?php echo $username; ?></h3>
            <p> Name : <?php echo $name ?> </p>
            <p> Access Level : <?php echo $accessLevel ?> </p>
            <p>Profile Picture:</p>
            <?php echo "<img src='images/profilePic/".$profilePic."' width='100' height='100'>"   ?>
        </div>
        <div class="col-md-6">
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="muiltipart/form-data">
<p>Name:<input type="text" name="name" value="<?php echo $name ?>"></p>
<p>Access Level:<input type="text" name="accessLevel" value="<?php echo $accessLevel ?>"></p>
<p>profile Picture</p>
</form>
        </div>
    </div>
</div>
