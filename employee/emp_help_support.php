<?php

$error="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Retrieve and sanitize common form data
    $empId = htmlspecialchars($_POST['EmpId']);
    $name = htmlspecialchars($_POST['name']);
    $department = htmlspecialchars($_POST['department']);

    // Check if the form is for a query or grievance
    if (isset($_POST['AskQuery'])) {
        $messageType = "Query";
        $messageContent = htmlspecialchars($_POST['AskQuery']);
    } elseif (isset($_POST['grievances'])) {
        $messageType = "Grievance";
        $messageContent = htmlspecialchars($_POST['grievances']);
    } else {
        $error= "Invalid form submission.";
        exit;
    }

   
    $servername = "localhost";
    $username = " ";
    $password = "  ";
    $dbname = "hrm_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert data into the database
    $sql = "INSERT INTO employee_messages (emp_id, name, department, message_type, message_content) VALUES ('$empId', '$name', '$department', '$messageType', '$messageContent')";

    if (!($conn->query($sql))) 
    {
        $error= "Error: " . $sql . "<br>" . $conn->error;
    }
    // Close connection
    $conn->close();
} else {
    $error=  "Invalid request method.";
}
?> 


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="emp_basic_structure.css"> -->
    <link rel="stylesheet" href="basic_structure.css">
    <link rel="stylesheet" href="emp_help_support.css">
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
            <li>
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
                <a href="">
                    <img src="/Minor_project/main/Survey.png" alt="Survey & feedback">
                    <span>Survey & Feedback</span>
                </a>
            </li>
            <li class="active">
                <a href="emp_help_support.php">
                    <img src="/Minor_project/main/Help_Support.png" alt="Help and Supprt">
                    <span>Help & Support</span>
                </a>
            </li>
        </ul>
    </div>


    <div class="help-container">
        <h1>Need Help?</h1>
        <div class="help-button">
            <img src="query_logo.png" alt="Ask Query Icon"> Ask Query
        </div>
        <div class="ask_query">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

                <label for="EmpId">Employee ID:</label>
                <input type=text name="EmpId" id="EmpId" required placeholder="Enter your Employee ID"></input>
                <br><br>
                <label for="name">Name:</label>
                <input type=text name="name" id="name" required placeholder="Enter your Name"></input>
                <br><br>
                <label for="department">Choose a Department:</label>

                <select name="department" id="department">
                    <option value="IT">Information Technology</option>
                    <option value="media">Media and Communication</option>
                    <option value="business">Management</option>

                </select>
                <br><br>
                <label for="AskQuery">Ask Query:</label>
                <br>
                <textarea name="AskQuery" id="AskQuery" rows="4" required  placeholder="Enter your Query"></textarea>
                <br><br>
                <button type="submit" class="submit-button">Submit</button>
            </form>
        </div>

        <div class="help-button">
            <img src="grievance_logo.png" alt="Grievances Icon"> Grievances
        </div>
        <div class="grievances">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"> 

                <label for="EmpId">Employee ID:</label>
                <input type=text name="EmpId" id="EmpId" required placeholder="Enter your Employee ID"></input>
                <br><br>
                <label for="name">Name:</label>
                <input type=text name="name" id="name" required placeholder="Enter your Name"></input>
                <br><br>
                <label for="department">Choose a Department:</label>

                <select name="department" id="department">
                    <option value="IT">Information Technology</option>
                    <option value="media">Media and Communication</option>
                    <option value="business">Management</option>

                </select>
                <br><br>
                <label for="grievance">Enter Message:</label>
                <br>
                <textarea name="grievances" id="grievance" rows="4" required  placeholder="Enter your Grievance"></textarea>
                <br><br>
                <button type="submit" class="submit-button">Submit</button>
            </form>
        </div>

        <footer>
            <img src="email_logo.png" alt="Contact Icon">
            <div class="contact-email">
            <a href="mailto:jims.vk@gmail.com" class="email-link">jims.vk@gmail.com</a>
        </footer>
    </div>

</body>
</html>