<?php
    session_start();
    $f_name= $l_name= $name_err= "";
    $eid= $eid_err= "";
    $uname= $uname_err= "";
    $pass= $c_pass= $pass_err= "";
    $empty_err= "";
    $server_err = "";
    $sno=0;


    if($_SERVER["REQUEST_METHOD"]=="POST")
    {
        if( empty($_POST["f_name"]) || empty($_POST["l_name"])  || empty($_POST["eid"])  || empty($_POST["uname"])  || (empty($_POST["pass"])))
        {
            $empty_err="Please fill all the fields.";
        }
        else
        {
            $f_name=htmlspecialchars($_POST["f_name"]);
            $l_name=htmlspecialchars($_POST["l_name"]);
            $eid=htmlspecialchars($_POST["eid"]);
            $uname=htmlspecialchars($_POST["uname"]);
            $pass=htmlspecialchars($_POST["pass"]);
            $c_pass=htmlspecialchars($_POST["confirm_pass"]);
            

            if ((!preg_match("/^[a-zA-Z]{2,}$/", $f_name)) || (!preg_match("/^[a-zA-Z]{2,}$/", $l_name)))
            {
                $name_err = "Name must contain only letters.";
            }
            elseif(!(filter_var($eid,FILTER_VALIDATE_EMAIL)))
            {
                $eid_err="Enter valid email address.";
            }
            elseif(!(preg_match("/^[a-zA-Z0-9]{5,}$/", $uname)))
            {
                $uname_err = "Username must contain only letters and numbers.";
            }
            elseif(!(preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $pass)))
            {
                $pass_err="Password must be at least 8 characters: include uppercase, lowercase, a number & a special character.";
            }
            elseif($pass != $c_pass)
            {
                $pass_err="Passwords do not match.";
            }
            else
            {
                $db_name= "hrm_db";
                $conn = new mysqli("localhost",  "root", "", $db_name, 3306);
                if($conn->connect_error)
                {
                    $server_err= "Error connecting with the server";
                }
                else
                {
                    $my_query = "select * from signup_tb where Username = '$uname'";
                    $result = mysqli_query($conn, $my_query);
                    $total_user = mysqli_num_rows($result);

                    $my_query = "select * from signup_tb where Email_id = '$eid'";
                    $result = mysqli_query($conn, $my_query);
                    $total_eid = mysqli_num_rows($result);
                    
                    if($total_eid == 0 & $total_user == 0)
                    {
                        $row = mysqli_fetch_assoc($result);
                        $sno = $row["S_no"]+1;
                        $hash_pass= password_hash($pass, PASSWORD_DEFAULT);
                        $my_query = "INSERT INTO signup_tb(S_no, First_name, Last_name, Email_id, Username, Password, Role) VALUES ('$sno', '$f_name', '$l_name', '$eid', '$uname', '$hash_pass', 'HR')";
                        $result = mysqli_query($conn, $my_query);
                        header("Location: /Minor_project/login/login.php");
                    }
                    else
                    {
                        if($total_user>0)
                        {
                            $uname_err = "This Username already exists.";
                        }
                        if($total_eid>0)
                        {
                            $eid_err = "This Email id already exists.";
                        }
                    }
                }

                exit();
            }
        }
    }
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="signup.css">
    <title>HRMinder | Sign-up</title>
</head>

<body>
    <header>
        <h2 >Simplifying HR, One Step at a Time</h2>
    </header>
    <div class="signup_form">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h2>Sign-up and  Get Started</h2>

                <div class="lab">
                    <label>Name : </label>
                </div>
                <div class="inp">
                    <input type="text" name="f_name" placeholder="Enter your First name">
                    <input type="text" name="l_name" placeholder="Enter your Last name"><br>
                </div>
                <div class="php_err"><?php echo $name_err;?></div>
                    
                <div class="lab">
                    <label> Email id : </label>
                </div>
                <div class="single_inp">
                    <input type="text" name="eid" placeholder="Enter your Email address" class="single_inp"><br>
                </div>
                <div class="php_err"><?php echo $eid_err;?></div>
    
                <div class="lab">
                    <label>Username : </label>
                </div>
                <div class="single_inp">
                    <input type="text" name="uname" placeholder="Enter a unique Username"><br>
                </div>
                <div class="php_err"><?php echo $uname_err;?></div>

                <div class="lab">
                    <label>Password : </label>
                </div>
                <div class="inp">
                    <input type="password" name="pass" placeholder="Enter a strong Password">
                    <input type="password" name="confirm_pass" placeholder="Enter Password again">
                </div>
                <div class="php_err"><?php echo $pass_err;?></div>

                <div class="empty_err"><?php echo $empty_err;?></div>
                <button type="submit" id="signup">Sign-up</button>
            <p id="login">Already a User? <a href="/Minor_project/login/login.php">Login now!</a></p>
        </form>
    </div>
</body>

</html>