<?php
//includes the Navbar
include "template.php"; ?>
<!--title-->
<title>User Registration</title>

<!--heading-->
<h1 class='text-primary'>User Registration</h1>

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

<!--form to register an account-->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <div class="container-fluid">
        <div class="row">
            <!--Customer Details-->

            <div class="col-md-6">
                <!--Account details-->
                <h2>Account Details</h2>
<!--                username input-->
                <p>Please enter username and password:</p>
                <p>User Name<input type="text" name="username" class="form-control" required="required"></p>
<!--                passoword input-->
                <p>Password<input type="password" name="password" class="form-control" required="required"></p>

            </div>
            <div class="col-md-6">
                <!--More details-->
                <h2>More Details</h2>
                <p>Please enter More Personal Details:</p>
<!--                name input-->
                <p>Name<input type="text" name="name" class="form-control" required="required"></p>
<!--                profile picture input-->
                <p>Profile Picture <input type="file" name="file" class="form-control" required="required" onchange="preview_image(event)" accept="image/*"></p>
                <!--display profile picture image preview-->
                <p>Image preview: <img id="output_image" width="100"></p>
            </div>
        </div>
    </div>
<!--    submit button-->
    <input type="submit" name="formSubmit" value="Submit">
</form>

<?php
//if the user submits the register user form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
//puts the user info into variables to be used later and sanitises the data for safety
    $username = sanitise_data($_POST['username']);
    $password = sanitise_data($_POST['password']);
    $name = sanitise_data($_POST['name']);

//check if user exists.
    //selects all rows where username is same as username user just submitted
    $query = $conn->query("SELECT COUNT(*) FROM user WHERE username='$username'");
    //puts data into varaible array to use later
    $data = $query->fetchArray();
    //gets number of users with username
    $numberOfUsers = (int)$data[0];

//if there a user already exists with same username display error message
    if ($numberOfUsers > 0) {
        //error message
        echo "Sorry, username already taken";
    } else {
// User Registration continues

//hashes the password the user registered for safety in the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

//puts profile picture info into variable for use later
        $file = $_FILES['file'];

//separates picture info for ease of access to use later
        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];

//defining what type of file is allowed
//separate the file to obtain the file type.
        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        //what picture formats are allowed
        $allowed = array('jpg', 'jpeg', 'png', 'pdf');

        //if the file type is allowed
        if (in_array($fileActualExt, $allowed)) {
            //if there are no errors
            if ($fileError === 0) {
                //file is smaller than 10gb.
                if ($fileSize < 10000000000) {
                    //file name is now a unique ID (name) based on time with IMG- precedding it, followed by the file type.
                    $fileNameNew = uniqid('IMG-', True) . "." . $fileActualExt;
                    //upload location
                    $fileDestination = 'images/profile_pictures/' . $fileNameNew;
                    //command to upload data to database.
                    move_uploaded_file($fileTmpName, $fileDestination);

                    //SQL to upload new user info into database
                    $sql = "INSERT INTO user (username, password, name, profilePic, accessLevel) VALUES (:newUsername, :newPassword, :newName, :newImage, 'User')";
                    //prepares the SQL
                    $stmt = $conn->prepare($sql);
                    //binds values of user to the SQL; safer method of uploading
                    $stmt->bindValue(':newUsername', $username);
                    $stmt->bindValue(':newPassword', $hashed_password);
                    $stmt->bindValue(':newName', $name);
                    $stmt->bindValue(':newImage', $fileNameNew);
                    //executes the SQL to add new user into database
                    $stmt->execute();
                    //redirects to home page
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
}
?>
</body>
</html>
