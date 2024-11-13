<?php
    session_start();
    $error="";
    if (!isset($_SESSION['username'])) 
    {
        header('Location: /Minor_project/login/login.php');
        exit();
    }
    else
    {
        if ($_SESSION['Role'] !== 'HR') {
            exit();
        }
        $db_name= "hrm_db";
        $conn = new mysqli("localhost",  "root", "", $db_name, 3306);

        if($conn->connect_error)
        {
            $server_err= "Error connecting with the server";
        }
        else
        {
            $sql = "Select 1 from information_schema.tables where table_schema='hrm_db' and table_name='jims_emp_data_tb'";
            $result= $conn->query($sql);

            if($result && $result->num_rows>0)
            {
                //work on this page only
                $sql = "SELECT * FROM jims_emp_data_tb";
                $result= $conn->query($sql);
                $sno= 1;
            }
            else
            {
                header("Location: employee_input_data.php");
            }
                    
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="basic_structure.css">
    <link rel="stylesheet" href="employee_data.css">
    <title>HRMinder | Employee Data</title>
</head>

<body>
    <nav>
        <div class="nav_left">
            <a href="/Minor_project/Home/index.html">
                <img src="HRM_logo.png" alt="HRMinder">
            </a>
            <!-- <img src="menu_logo2.png" alt="Menu" id="menu_logo"> -->
        </div>
        <!-- Organisation Name -->
        <a href="https://jimsd.org/" class="org_name">JIMS VK</a>
        <div class="nav_right">

            <img src="user.png" alt="User-Profile" class="Profile_pic">
            <div class="username"><?php echo  $_SESSION['username']; ?></div>

            <!-- dropdown list -->
            <ul class="profile_card">
                    <li>
                        <a href="#">
                            <img src="user_profile.png" alt="My Profile">
                            <span>My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="change_pass.png" alt="Change Password">
                            <span>Change Password</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="logout.png" alt="Log Out">
                            <span>Log Out</span>
                        </a>
                    </li>
                </ul>
        </div>
    </nav>

<!-- sidebar menu -->
    <div class="sidebar_menu">
        <ul class="main_menu">
            <li>
                <a href="dashboard.php">
                    <img src="Dashborad.png" alt="Dashboard">
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="active">
                <a href="employee_data.html">
                    <img src="contact-data.png" alt="Employee Data">
                    <span>Employee Data</span>
                </a>
            </li>
            <li>
                <a href="report.php">
                    <img src="Report.png" alt="Analysis and Report">
                    <span>Analysis & Report</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="attrition.png" alt="Attrition Prediction">
                    <span>Attrition Prediction</span>
                </a>
            </li>
            <li>
                <a href="feedback_survey.php">
                    <img src="Survey.png" alt="Survey & feedback">
                    <span>Survey & Feedback</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="Help_Support.png" alt="Help and Supprt">
                    <span>Help & Support</span>
                </a>
            </li>
        </ul>
    </div>  
    
    <div class="main_container">
        <div class="display_table">
            <h2>Employee Data</h2>
            <table>
                <thead>
                    <tr>
                        <th>S.No</th>
                        <?php
                        // Assuming you've already checked the existence of the table
                        $sql = "SELECT * FROM jims_emp_data_tb";
                        $result = $conn->query($sql);

                        // Display the headers dynamically
                        if ($result->num_rows > 0) {
                            // Fetch the first row to get the column names
                            $firstRow = $result->fetch_assoc();
                            // Display column headers
                            foreach ($firstRow as $key => $value) {
                                echo "<th>" . htmlspecialchars($key) . "</th>";
                            }
                            echo "</tr></thead><tbody>";

                            // Display the first row of data
                            echo "<tr><td>" . $sno++ . "</td>";
                            foreach ($firstRow as $value) {
                                echo "<td>" . htmlspecialchars($value) . "</td>";
                            }
                            echo "</tr>";

                            // Now display the remaining rows
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr><td>" . $sno++ . "</td>";
                                foreach ($row as $value) {
                                    echo "<td>" . htmlspecialchars($value) . "</td>";
                                }
                                echo "</tr>";
                            }
                            echo "</tbody>";
                        } else {
                            echo "<tr><td colspan='100'>No records found.</td></tr>";
                        }
                        ?>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- <h1>supposedly is page mein data h</h1>
        <div class="err"><?php echo $error ?></div> -->
    </div>
    <footer>
        <p>Insert Row</p>
        <p>Insert Column</p>
        <p>Delete Row</p>
        <p>Delete Column</p>
    </footer>
</body>

</html>