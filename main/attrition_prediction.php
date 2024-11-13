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
            $db_name = "hrm_db";
            $conn = new mysqli("localhost", "root", "", $db_name, 3306);
            $username = $_SESSION['username'];
        }
            
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="basic_structure.css">
    <link rel="stylesheet" href="attrition_prediction.css">
    <title>HRMinder | Attrition Prediction</title>
</head>

<body>
<!-- navigation bar -->
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
            <li class="active">
                <a href="">
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
            <li>
                <a href="help_support.php">
                    <img src="Help_Support.png" alt="Help and Supprt">
                    <span>Help & Support</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="main_container">
        <h1>Attrition Prediction</h1>
        <p>Attrition prediction helps organizations identify employees who may be at risk of leaving, enabling HR to take proactive steps to improve retention. <br>
            Below is a table showing employee data, who might leave the Organisation.</p>
        <table>
            <tr>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Department</th>
            </tr>
            <?php
                $sql = "SELECT employee_id, emp_name, department FROM survey_feedback_tb WHERE attrition_pred = 1"; 
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['employee_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['emp_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['department']) . "</td>";
                        echo "</tr>";
                    }
                }                
            ?>
        </table>

        <div class="power_bi">
            <a href="https://app.powerbi.com/groups/me/reports/78ce005c-54d8-49a8-8d0a-e96630adce9c/56f732880a08a18aa0a4?experience=power-bi">Power BI Report</a>
        </div>
    </div>
    </body>

</html>