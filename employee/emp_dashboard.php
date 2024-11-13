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
    <!-- <link rel="stylesheet" href="emp_basic_structure.css"> -->
    <link rel="stylesheet" href="basic_structure.css">
    <link rel="stylesheet" href="emp_dashboard.css">
    <title>HRMinder | Employee Survey & Feedback</title>
</head>

<body>
    <nav>
        <div class="nav_left">
            <a href="/Minor_project/Home/index.html"><img src="/Minor_project/main/HRM_logo.png" alt="HRMinder"></a>
            <!-- <img src="menu_logo2.png" alt="Menu" id="menu_logo"> -->
        </div>
        <!-- Organisation Name -->
        <a href="https://jimsd.org/" class="org_name">JIMS VK</a>
        <div class="nav_right">

            <img src="/Minor_project/main/user.png" alt="User-Profile" class="Profile_pic">
            <div class="username">USER NAME</div>
            <!-- dropdown list -->
            <ul class="profile_card">
                <li>
                    <a href="#">
                        <img src="/Minor_project/main/user_profile.png" alt="My Profile">
                        <span>My Profile</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img src="/Minor_project/main/change_pass.png" alt="Change Password">
                        <span>Change Password</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img src="/Minor_project/main/logout.png" alt="Log Out">
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
                    <img src="/Minor_project/main/Dashborad.png" alt="Dashboard">
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="">
                    <img src="/Minor_project/main/contact-data.png" alt="Employee Data">
                    <span>Employee Data</span>
                </a>
            </li>
            <li>
                <a href="emp_feedback_survey.php">
                    <img src="/Minor_project/main/Survey.png" alt="Survey & feedback">
                    <span>Survey & Feedback</span>
                </a>
            </li>
            <li>
                <a href="emp_help_support.php">
                    <img src="/Minor_project/main/Help_Support.png" alt="Help and Supprt">
                    <span>Help & Support</span>
                </a>
            </li>
        </ul>
    </div> 

<div class="main_container">
    <!-- welcome message -->

     <div class="welcome">
        <h1>Welcome!</h1>
        <h3>Here’s to a productive day ahead.</h3>
     </div>

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

            <!-- announcements -->
            <div class="box announcements">
                <h2>Announcements</h2>
                <ul>
                    <li>New health and wellness program launching on January 20th!</li>
                    <li>Company holiday on December 31st and January 1st.</li>
                    <li>Monthly team meeting scheduled for December 26th, 10 AM.</li>
                    <li>End-of-year performance reviews will begin on December 25th.</li>
                    <li>New parking policy: All vehicles must be registered by December 30th.</li>
                </ul>
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
    </div>
    
    </div>
    <script src="script1.js"></script>

</body>

</html>