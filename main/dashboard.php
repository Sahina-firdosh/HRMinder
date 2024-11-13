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
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task'])) 
            {
                $username = $_SESSION['username'];
                $task = htmlspecialchars($_POST['task']);
                $priority = htmlspecialchars($_POST['priority']);
                
                if (!empty($task) && !empty($priority)) 
                {

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    } else 
                    {
                        $sql = "CREATE TABLE IF NOT EXISTS task_manager_tb (id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(50) NOT NULL, task TEXT NOT NULL, priority INT NOT NULL)";
                        if ($conn->query($sql) === FALSE) 
                        {
                            die("Error creating table: " . $conn->error);
                        }
                        $query = "INSERT INTO task_manager_tb (username, task, priority) VALUES ('$username', '$task', '$priority')";
                        mysqli_query($conn, $query);
                    }
                }
            }

            if (isset($_GET['delete'])) 
            {
                $id = intval($_GET['delete']);
                $query = "DELETE FROM task_manager_tb WHERE id = $id";
                mysqli_query($conn, $query);
            }

            $result = mysqli_query($conn, "SELECT * FROM task_manager_tb ORDER BY priority ASC");
        }
            
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="basic_structure.css">
    <link rel="stylesheet" href="dashboardd.css">
    <title>HRMinder | Dashboard</title>
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
            <li class="active">
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
                <a href="attrition_prediction.php">
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

<!-- main content -->
    <div class="content">
<!-- to do list -->
        <div class="box">
            <h2>Task Manager</h2>
            <ol id="tasks">
                <!-- To-do tasks will be listed here -->
                <li>
                    <table>
                        <tr>
                            <th>Task</th>
                            <th>Priority</th>
                            <th>Action</th>
                        </tr>
                    <?php while ($task = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($task['task']) ?></td>
                            <td><?= htmlspecialchars($task['priority']) ?></td>
                            <td><a href="?delete=<?= $task['id'] ?>"><button class="delete">Delete</button></a></td>
                        </tr>
                    <?php endwhile; ?>
                    </table>
                </li>
            </ol>

            <div class="new_task">
                <form class="new_task" method="post" action="">
                    <input type="text" name="task" placeholder="Add a new task" required>
                    <input type="number" name="priority" placeholder="Set priority" id="input-priority" required>
                    <button type="submit" name="add_task" class="add">Add Task</button>
                </form>
            </div>
        </div>

<!-- notice periods -->
<div class="box">
            <h2>Notice Period <span id="alert">Alerts </span></h2>
            <div class="notice_period">
                <table>
                    <tr>
                        <th>S.No.</th>
                        <th>Emp. ID</th>
                        <th>Emp. Name</th>
                        <th>Dept.</th>
                        <th>Remaining Days</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>E003</td>
                        <td>Mr. Pratik Kr. Singh</td>
                        <td>IT</td>
                        <td>8</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>E004</td>
                        <td>Dr. Sonal Kanungo Sharma</td>
                        <td>IT</td>
                        <td>8</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>E008</td>
                        <td>Ms. Anupama Munshi</td>
                        <td>IT</td>
                        <td>4</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>E009</td>
                        <td>Mr. Ashish Kumar</td>
                        <td>IT</td>
                        <td>4</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>E012</td>
                        <td>Ms. Minal Maheshwari</td>
                        <td>IT</td>
                        <td>8</td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>E014</td>
                        <td>Ms. Nisha Jha</td>
                        <td>IT</td>
                        <td>7</td>
                    </tr>
                </table>
            </div>
        </div>
<!-- calender -->
        <div class="box">
            <h2>Calendar</h2>
            <div class="calender">
                <div id="month_name"></div> 
                <div id="day_names"></div>       
                <div id="calendar_dates"></div>
            </div>
        </div>
<!-- IT dept -->
<div class="box">
            <h2>IT Team</h2>
            <!-- to be integrated after setting up database for the org -->
            <div class="dept">
                <table>
                    <tr>
                        <th>S.No.</th>
                        <th>Emp. ID</th>
                        <th>Emp. Name</th>
                        <th>Designation</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>E001</td>
                        <td>Dr. Meenakshi Narula</td>
                        <td>Professor & Head of IT Department</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>E011</td>
                        <td>Mr. Deepak Sharma</td>
                        <td>Assistant Professor & IQAC Coordinator</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>E010</td>
                        <td>Dr. Harsha Ratnani</td>
                        <td>Associate Professor</td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- BBA dept -->
        <div class="box">
            <h2>Management Team</h2>
            <!-- to be integrated after setting up database for the org -->
            <div class="dept">
                <table>
                    <tr>
                        <th>S.No.</th>
                        <th>Emp. ID</th>
                        <th>Emp. Name</th>
                        <th>Designation</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>E015</td>
                        <td>Dr. Nidhi Gupta</td>
                        <td>Head Of The Department</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>E017</td>
                        <td>Dr. S.K. Dogra</td>
                        <td>Professor & Registrar</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>E018</td>
                        <td>Dr. Hakimuddin Khan</td>
                        <td>Associate Professor</td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- BAJMC dept -->
        <div class="box">
            <h2>Media & Communication Team</h2>
            <!-- to be integrated after setting up database for the org -->
            <div class="dept">
                <table>
                    <tr>
                        <th>S.No.</th>
                        <th>Emp. ID</th>
                        <th>Emp. Name</th>
                        <th>Designation</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>E016</td>
                        <td>Dr. Geeta Arora</td>
                        <td>Head Of The Department</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>E019</td>
                        <td>Prof.(Dr.) Ravi K.Dhar</td>
                        <td>Director</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>E020</td>
                        <td>Dr. Gaurav Yadav</td>
                        <td>Associate Professor</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <script src="script1.js"></script>
</body>

</html>