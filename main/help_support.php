<?php
    session_start();
    $err="";
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
        else
        {
            $username = $_SESSION['username'];
            $db_name = "hrm_db";
            $conn = new mysqli("localhost", "root", "", $db_name, 3306);

            if ($conn->connect_error) 
            {
                die("Connection failed: " . $conn->connect_error);
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
    <link rel="stylesheet" href="help_support.css">
    <title>HRMinder | Help & Support</title>
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
                <a href="dashboard.html">
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
            <li>
                <a href="feedback_survey.php">
                    <img src="Survey.png" alt="Survey & feedback">
                    <span>Survey & Feedback</span>
                </a>
            </li>
            <li class="active">
                <a href="#">
                    <img src="Help_Support.png" alt="Help and Supprt">
                    <span>Help & Support</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="main_container">
        <h1>Employee Help And Support</h1>

        <div class="section">
            <h3>Employee Queries</h3>
            <table>
                <tr>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Department</th>
                    <th>Query</th>
                </tr>
                <?php
                $sql = "SELECT employee_id, employee_name, department, query FROM help_support_tb  WHERE query IS NOT NULL";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) 
                {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['employee_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['employee_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['department']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['query']) . "</td>";
                        echo "</tr>";
                    }
                } 
            ?>
            </table>
        </div>

        <div class="section">
            <h3>Employee Grievances</h3>
            <table>
                <tr>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Department</th>
                    <th>Grievances</th>
                </tr>
            <?php
                $sql = "SELECT employee_id, employee_name, department, grievance FROM help_support_tb  WHERE grievance IS NOT NULL";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) 
                {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['employee_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['employee_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['department']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['grievance']) . "</td>";
                        echo "</tr>";
                    }
                } 
            ?>
            
            </table>
        </div>
    </div>
    
</body>

</html>