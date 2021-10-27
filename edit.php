<?php
//includes navbar
include "template.php";
/**
 * This is the user's profile page.
 * It shows the Users details including picture, and a link to edit the details.
 *
 * @var SQLite3 $conn
 */
?>
<!--title-->
    <title>Edit your Profile</title>

<!--heading-->
    <h1 class='text-primary'>Edit Your Profile</h1>

<?php

//if the user is logged in
if (isset($_SESSION["username"])) {
    //gets the users username and id
    $userName = $_SESSION["username"];
    $userId = $_SESSION["user_id"];

    //selects all data for user logged in
    $query = $conn->query("SELECT * FROM user WHERE username='$userName'");
    //stores data into array for use later
    $userData = $query->fetchArray();
    //separates the array data into variables for use later
    $userName = $userData[1];
    $password = $userData[2];
    $name = $userData[3];
    $profilePic = $userData[4];
    $accessLevel = $userData[5];
} else {
    //if not logged in go to home page
    header("Location:index.php");
}
?>

    <!-- A javascript function to preview the new profile picture the user has chosen -->
    <script type="text/javascript">
        function preview_image(event){
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('output_image');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>


    <!--Displays user information-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
<!--                display the users information-->
                <h3>Username : <?php echo $userName; ?></h3>
                <p> Name : <?php echo $name ?> </p>
                <p> Access Level : <?php echo $accessLevel ?> </p>
                <p>Profile Picture:</p>
                <?php
                //displays the users profile picture
                echo "<img src='images/profile_pictures/" . $profilePic . "' width='100' height='100'>" ?>
            </div>
            <div class="col-md-6">
                <!--form for inputting new user information-->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
<!--                    displays it nicely with an input field for the user to edit-->
                    <p>Name: <input type="text" name="name" value="<?php echo $name ?>"></p>
                    <p>Access Level: <input type="text" name="accessLevel" value="<?php echo $accessLevel ?>"></p>
<!--                    new profile picture preview-->
                    <p>Profile Picture: <input type="file" name="file" onchange="preview_image(event)" accept="image/*"></p>
                    <p><div style="color: darkgrey">Profile picture preview:</div><img id="output_image" width="130"></p>
<!--                    submit changes button-->
                    <input type="submit" name="formSubmit" value="Submit">
                </form>
            </div>
        </div>
    </div>

<?php

//if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //sanitise the data for the users new name and access level
    $newName = sanitise_data($_POST['name']);
    $newAccessLevel = sanitise_data($_POST['accessLevel']);

    //sets sql to override current user info with new user info in database
    $sql = "UPDATE user SET name = :newName, accessLevel=:newAccessLevel WHERE username='$userName'";
    //prepares the SQL above
    $sqlStmt = $conn->prepare($sql);
    //binds template values with new user data
    $sqlStmt->bindValue(":newName", $newName);
    //only the admin can change that access level of a profile
    if ($accessLevel == "Administrator") {
        //binds the new access level to user
        $sqlStmt->bindValue(":newAccessLevel", $newAccessLevel);
    } else {
        //binds the default (user) access level to user
        $sqlStmt->bindValue(":newAccessLevel", $accessLevel);
    }
    //executes the SQL
    $sqlStmt->execute();


// Updating Profile picture
    //puts picture info into variable
    $file = $_FILES['file'];

//separates picture info for ease of access to use later
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

//defining what type of file is allowed
//we separate the file, and obtain the end.
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    //what picture formats are allowed
    $allowed = array('jpg', 'jpeg', 'png', 'pdf');

    //if the picture is in one of the correct formats
    if (in_array($fileActualExt, $allowed)) {
        //if there ar eno errors
        if ($fileError === 0) {
            //File is smaller than 10gb.
            if ($fileSize < 10000000000) {
                //file name is now a unique ID based on time with IMG- preceding it, followed by the file type.
                $fileNameNew = uniqid('IMG-', True) . "." . $fileActualExt;
                //where the picture will upload to
                $fileDestination = 'images/profile_pictures/' . $fileNameNew;
                //upload the picture to the file destination
                move_uploaded_file($fileTmpName, $fileDestination);

                //sql to insert new profile picture info into database
                $sql = "UPDATE user SET profilePic=:newFileName WHERE username='$userName'";
                //prepare SQL above
                $stmt = $conn->prepare($sql);
                //binds new file name to the SQL; safer method of uploading
                $stmt->bindValue(':newFileName', $fileNameNew);
                //executes the SQL
                $stmt->execute();
                //resets the session profile picture variable to the new pic
                $_SESSION['profilePicture'] = $fileNameNew;
                //sends the user to the home page
                header("location:index.php");
            } else {
                //error message
                echo "Your image is too big!";
            }
        } else {
            //error message
            echo "there was an error uploading your image!";
        }
    } else {
        //error message
        echo "You cannot upload files of this type!";
    }
}
?>
