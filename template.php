<?php
//includes the database
require_once 'config.php';
//inlcludes the login info
include 'login.php'; ?>

<!--  Formatting  Navbar  -->
    <html>
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light" role="navigation">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="images/logo.png" alt="" width="80" height="80">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse w-100 order-3 dual-collapse2" id="navbarNav">

            <!--  Default navbar items  -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="orderForm.php">Order Form</a>
                </li>

                <!--          Left side of Navbar          -->
                <?php if (isset($_SESSION["username"])) : ?> <!--Authenticated user-->
                    <li class="nav-item">
                        <a class="nav-link" href="invoice.php">Invoice</a>
                    </li>
                    <?php if ($_SESSION["level"] == "Administrator") : ?> <!--Administrator user-->

                    <?php endif; ?>
                <?php endif; ?>
            </ul>

            <!--        Right side of Navbar        -->
            <div class="mx-auto order-0"></div>
            <?php if (!isset($_SESSION["username"])) : ?>  <!--Unauthenticated user-->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Log in</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="registration.php">Register</a>
                    </li>
                </ul>
            <?php else: ?>     <!--Authenticated user-->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <img src="images/profile_pictures/<?php echo $_SESSION['profilePicture'] ?>"
                             style="width: 35px; margin-top: 40%">
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Hello <?php echo $_SESSION["username"] ?><br> Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>


<script src="js/bootstrap.bundle.js"></script>

<?php
//function to sanitise data to make it safe to go into database
function sanitise_data($data)
{
    //trims the data to get rid of blank spaces
    $data = trim($data);
    $data = stripslashes($data);
    //makes the data raw so it doesn't run SQL into the database
    $data = htmlspecialchars($data);
    //gives the data back
    return $data;
}


//footer (not used currently)
function outputFooter()
{
    //sets the correct time zone
    date_default_timezone_set('Australia/Canberra');
    //displays the time and date of the time zone
    echo "<footer>This page was last modified: " . date("F d Y H:i:s.", filemtime("index.php")) . "</footer>";
}

?>