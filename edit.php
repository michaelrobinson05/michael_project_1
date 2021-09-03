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
<p>profile Picture:<input type="file" name="file"> </p>
<input type="submit" name="formSubmit" value="submit">
</form>

        </div>
    </div>
</div>
<?php
if ($_SERVER["REQUEST_METHOD"]== "post") {


    $newName = sanitise_data($_POST['name']);
    $newAccessLevel = sanitise_data($_POST['accessLevel']);

    $sql = "UPDATE user SET name =:newName, accessLevel=:newAccessLevel WHERE username'$username'";
    $sqlstmt = $conn->prepare($sql);
    $sqlstmt->bindValue(":newName", $newName);
    if ($accessLevel == "administrator") {
        $sqlstmt->bindValue(":newAccessLevel", $newAccessLevel);
    }else{
        $sqlstmt->bindValue(":newAccessLevel", $accessLevel);


    }
$sqlstmt->execute();


    $file = $_FILES['file'];

//Variable Names
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

//defining what type of file is allowed
// We seperate the file, and obtain the end.
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));
//We ensure the end is allowable in our thing.
    $allowed = array('jpg', 'jpeg', 'png', 'pdf');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            //File is smaller than yadda.
            if ($fileSize < 10000000000) {
                //file name is now a unique ID based on time with IMG- precedding it, followed by the file type.
                $fileNameNew = uniqid('IMG-', True) . "." . $fileActualExt;
                //upload location
                $fileDestination = 'images/profilePic/' . $fileNameNew;
                //command to upload.
                move_uploaded_file($fileTmpName, $fileDestination);
                $sql = "UPDATE user SET profilePic=:newFileName WHERE username'$username'";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(':newFileName', $fileNameNew);
                $stmt->execute();
                header("location:index.php");
            } else {
                echo "Your image is too big!";
            }
        } else {
            echo "there was an error uploading your image!";
        }
    } else {
        echo "You cannot upload files of this type!";
    }
}

?>