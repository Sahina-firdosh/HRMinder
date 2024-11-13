<?php

    session_start();
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
        $error="";
        $db_name= "hrm_db";
        $conn = new mysqli("localhost",  "root", "", $db_name, 3306);

        if($conn->connect_error)
        {
            $server_err= "Error connecting with the server";
        }
        else
        {
            $sql = "Select * from  survey_feedback_tb";
            $result= $conn->query($sql);
            $sno = 1;
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="basic_structure.css">
    <link rel="stylesheet" href="feedback_survey.css">
    <title>HRMinder | Feedback & Survey</title>
</head>

<body>
    <nav>
        <div class="nav_left">
            <a href="/Minor_project/Home/index.html"><img src="HRM_logo.png" alt="HRMinder"></a>
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
            <li>
                <a href="employee_data.php">
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
                <a href="resume_screening.php">
                    <img src="resume_icon.png" alt="AI Resume Screening">
                    <span>AI Resume Screening</span>
                </a>
            </li>        
            <li class="active">
                <a href="#">
                    <img src="Survey.png" alt="Survey & feedback">
                    <span>Survey & Feedback</span>
                </a>
            </li>
            <li>
                <a href="help_support.php">
                    <img src="Help_Support.png" alt="Help and Supprt">
                    <span>Help & Support</span>
                </a>
            </li>
        </ul>
    </div>  
    
    <div class="main_container">
        <h1>Feedback & Survey Data</h1>
        <table>
            <tr>
                <th>S.no</th>
                <th>Name</th>
                <th>Employee Id</th>
                <th>Department</th>
                <th>Average Monthly Hours</th>
                <th>Promotion in 5 years</th>
                <th>Salary</th>
                <th>Salary Satisfaction</th>
                <th>Salary Competitiveness</th>
                <th>Role Satisfaction</th>
                <th>Work Environment</th>
                <th>Leaving Status</th>
                <th>Leaving Reason</th>
            </tr>
            <?php
                if ($result->num_rows > 0) 
                {
                    while ($row = $result->fetch_assoc()) 
                    {
                        echo "<tr>";
                        echo "<td>" . $sno++ . "</td>";  // S.no
                        echo "<td>" . $row["emp_name"] . "</td>";
                        echo "<td>" . $row["employee_id"] . "</td>";
                        echo "<td>" . $row["department"] . "</td>";
                        echo "<td>" . $row["avg_monthly_hours"] . "</td>";
                        echo "<td>" . $row["promotion_in_5_years"] . "</td>";
                        echo "<td>" . $row["employee_salary"] . "</td>";
                        echo "<td>" . $row["salary_satisfaction"] . "</td>";
                        echo "<td>" . $row["salary_competitiveness"] . "</td>";
                        echo "<td>" . $row["role_satisfaction"] . "</td>";
                        echo "<td>" . $row["work_environment_rating"] . "</td>";
                        echo "<td>" . $row["leaving_status"] . "</td>";
                        echo "<td>" . $row["leave_reason"] . "</td>";
                        echo "</tr>";
                    }
                } 
                else 
                {
                    echo "<tr><td colspan='12'>No results found.</td></tr>";
                }
        
                $conn->close();
            ?>
        </table>
    </div>
</body>

</html>