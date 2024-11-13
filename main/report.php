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
        $username = $_SESSION['username'];
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="basic_structure.css">
    <link rel="stylesheet" href="report.css">
    <title>HRMinder | Analysis & Report</title>
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
            <li class="active">
                <a href="#">
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
                <a href="#">
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
                <a href="#">
                    <img src="Help_Support.png" alt="Help and Supprt">
                    <span>Help & Support</span>
                </a>
            </li>
        </ul>
    </div>   
    
    <div class="main_container">
        <h1>Report Formats</h1>
        <div class="button_div">
            <button class="rep_button" onclick="report_format(1)">Format-1: [EP 3.1]</button>
            <button class="rep_button" onclick="report_format(2)">Format-2: [2.4.1 & 2.4.3]</button>
            <button class="rep_button" onclick="report_format(3)">Format-3: [2.4.2]</button>
            <button class="rep_button" onclick="report_format(4)">Format-4</button>
            <button class="rep_button" onclick="report_format(5)">Format-5: [AISHE]</button>
            <button class="rep_button" onclick="report_format(6)">Format-6: [NIRF]</button>
        </div>
        <div class="display_report"></div>
        <div class="powerbi_dashboard"></div>
    </div>

    <script>
        // Using JavaScript to load the PHP-generated table into the div
        function report_format(format) {
            fetch(`report_format.php?format=${format}`)
                .then(response => response.text())
                .then(data => {
                    document.querySelector('.display_report').innerHTML = data;
                })
                .catch(error => console.error('Error loading data:', error));
        }
    </script>
</body>

</html>