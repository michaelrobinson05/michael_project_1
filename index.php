<?php include 'login.php'; ?>

<title>Michael's store</title>

<h1 class='text-primary'>Welcome to our shopfront</h1>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <p>username: admin</p>
            <p>Password: admin</p>
            <p>username: user</p>
            <p>Password: user</p>
            <p>username: ryan</p>
            <p>Password: ryan</p>
            <p>username: testuser</p>
            <p>Password: testuser</p>
        </div>
        <div class="col-md-6">
            <!--            Login Form-->
            <?php if (!isset($_SESSION["username"])) : ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required="required"/>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required="required"/>
                    </div>

                    <center>
                        <button name="login" class="btn btn-primary"><span
                                    class="glyphicon glyphicon-log-in"></span> Login
                        </button>
                    </center>
                </form>
            <?php endif; ?>

        </div>
    </div>
</div>


</body>
</html>