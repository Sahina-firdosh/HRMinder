<?php
    session_start();
    $uname= $uname_err= "";
    $pass= $pass_err= "";
    $empty_err= "";
    $server_err = "";

    if($_SERVER["REQUEST_METHOD"]=="POST")
    {
        if(empty($_POST["uname"])  || (empty($_POST["pass"])))
        {
            $empty_err="Please fill all the fields.";
        }
        else
        {
            $uname=htmlspecialchars($_POST["uname"]);
            $pass=htmlspecialchars($_POST["pass"]);

            $db_name= "hrm_db";
            $conn = new mysqli("localhost",  "root", "", $db_name, 3306);
            if($conn->connect_error)
            {
                $server_err= "Error connecting with the server";
            }
            else
            {
                $my_query = "select * from signup_tb where Username = '$uname' OR Email_id = '$uname'";
                $result = mysqli_query($conn, $my_query);
                $total_user = mysqli_num_rows($result);

                if($total_user == 0)
                {
                    $uname_err = "User Not Found! Enter correct credentials.";
                }
                else
                {
                    $row = mysqli_fetch_assoc($result);
                    $hash_pass = $row['Password'];
                    if(password_verify($pass, $hash_pass))
                    {
                        $_SESSION["username"] = $uname;
                        $_SESSION["Role"]=$row['Role'];
                        header("Location: /Minor_project/main/dashboard.php");
                    }
                    else
                    {
                        $pass_err="Incorrect password";
                    }
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>HRMinder | Login</title>
</head>

<body>
    <header>
        <h2>Access Your HRMinder Dashboard</h2>
    </header>
    <div class="signup_form">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h2>Login to Your Account</h2>

                <div class="lab">
                    <label>Username or Email: </label>
                </div>
                <div class="single_inp">
                    <input type="text" name="uname" placeholder="Enter your username or email address"><br>
                </div>
                <div class="php_err"><?php echo $uname_err;?></div>

                <div class="lab">
                    <label>Password : </label>
                </div>
                <div class="single_inp">
                    <input type="password" name="pass" placeholder="Enter your password">
                </div>
                <div class="php_err"><?php echo $pass_err;?></div>

                <a href="forgotpass.php" id="forgot_pass">Forgot Password?</a>

                <div class="empty_err"><?php echo $empty_err;?></div>
                <button type="submit" id="login">login</button>
                <p id="new_acc">New to HRMinder?<br> <a href="/Minor_project/signup/signup.php">Create an account</a> and join us!</p>
        </form>

    </div>
</body>

</html>