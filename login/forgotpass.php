<?php
    session_start();
    $uname= "";
    $php_err= "";

    if($_SERVER["REQUEST_METHOD"]=="POST")
    {
        if(empty($_POST["uname"]))
        {
            $php_err="This field can't be empty.";
        }
        else
        {
            $uname=htmlspecialchars($_POST["uname"]);

            $db_name= "hrm_db";
            $conn = new mysqli("localhost",  "root", "", $db_name, 3306);
            if($conn->connect_error)
            {
                $php_err= "Error connecting with the server";
            }
            else
            {
                $my_query = "select * from signup_tb where Username = '$uname' OR Email_id = '$uname'";
                $result = mysqli_query($conn, $my_query);
                $total_user = mysqli_num_rows($result);

                if($total_user == 0)
                {
                    $php_err = "User Not Found! Enter correct credentials.";
                }
                else
                {
                    $row = mysqli_fetch_assoc($result);
                    $email_to = $row['Email_id'];
                    $username = $row['Password'];
                    $recovery_msg = "Hi $username, \nWe noticed that you're having trouble logging into your account. \nDon't worry, we've got you covered!\n
                        You have two options:\n
                        1. Log in directly: Click the link below to log in securely without resetting your password:\n 
                        [Direct Login Link]\n
                        2. Reset your password: If you'd prefer to reset your password, click the link below:\n
                        [Reset Password Link]\n\n
                        Note: Both of these link will expire in 15 minutes.\n
                        If you didn't request this email, you can safely ignore it. Your account is secure, and no changes will be made.
                        Thanks,\n
                        The HRMinder Team";
                    if(mail($email_to, "Account Recovery: Login or Reset Your Password", $recovery_msg, "From: @gmail.com"))
                    {
                        $php_err= "Email sent successfully!";
                        header("Location: Login.php");
                    }    
                    else
                        $php_err= "Error sending email.";
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
    <link rel="stylesheet" href="forgotpass.css">
    <title>HRMinder | Forgot Password</title>
</head>

<body>
    <header>
        <h2>Trouble logging in?We’ve Got You Covered!</h2>
    </header>
    <div class="forgotpass_form">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h2>Recover Your Account!</h2>

            <p id="instruction">Enter the email address associated with your account, and we’ll send you a link to reset
                your password. If you don't receive the email, please check your spam folder or try again.</p>
            
            <label>Username or Email: </label>
            <input type="text" name="uname" placeholder="Enter your username or email address"><br>
            <div class="php_err">
                <?php echo $php_err?>
            </div>

            <button type="submit" id="submit">Get Recovery Email</button>
        </form>

    </div>
</body>

</html>