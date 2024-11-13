<?php
    session_start();
    $work_days_per_week = $work_hours_per_day = $promotion_in_5_years = $curr_salary = $curr_salary_satisfaction = "";
    $curr_role_satisfaction = $work_environment = $compensation_competitiveness = $leave = "";
    $leave_reasons = [];
    $error = "";
    // $username

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $work_days_per_week = isset($_POST['work_days_per_week']) ? htmlspecialchars(trim($_POST['work_days_per_week'])) : "";
        $work_hours_per_day = isset($_POST['work_hours_per_day']) ? htmlspecialchars(trim($_POST['work_hours_per_day'])) : "";
        $promotion_in_5_years = isset($_POST['promotion_in_5_years']) ? htmlspecialchars(trim($_POST['promotion_in_5_years'])) : "";
        $curr_salary = isset($_POST['curr_salary']) ? htmlspecialchars(trim($_POST['curr_salary'])) : "";
        $curr_salary_satisfaction = isset($_POST['curr_salary_satisfaction']) ? htmlspecialchars(trim($_POST['curr_salary_satisfaction'])) : "";
        $curr_role_satisfaction = isset($_POST['curr_role_satisfaction']) ? htmlspecialchars(trim($_POST['curr_role_satisfaction'])) : "";
        $work_environment = isset($_POST['work_environment']) ? htmlspecialchars(trim($_POST['work_environment'])) : "";
        $compensation_competitiveness = isset($_POST['compensation_competitiveness']) ? htmlspecialchars(trim($_POST['compensation_competitiveness'])) : "";
        $leave = isset($_POST['leave']) ? htmlspecialchars(trim($_POST['leave'])) : "";
        // $leave_reasons = isset($_POST['leave_reasons']) ? htmlspecialchars(trim($_POST['
        if (
            strlen(trim($_POST['work_days_per_week'])) === 0 ||
            strlen(trim($_POST['work_hours_per_day'])) === 0 ||
            strlen(trim($_POST['promotion_in_5_years'])) === 0 ||
            strlen(trim($_POST['curr_salary'])) === 0 ||
            strlen(trim($_POST['curr_salary_satisfaction'])) === 0 ||
            strlen(trim($_POST['curr_role_satisfaction'])) === 0 ||
            strlen(trim($_POST['work_environment'])) === 0 ||
            strlen(trim($_POST['compensation_competitiveness'])) === 0 ||
            strlen(trim($_POST['leave'])) === 0) 
        {
            $error = "Please fill all the fields";
        }
        elseif (!preg_match("/^(2[0-4]|[1-9])$/", $_POST['work_days_per_week'])) {
            $error = "Please fill valid work hours between 1 and 24.";
        }
        elseif ($leave == "1" && isset($_POST['leave_reason']) && !empty($_POST['leave_reason'])) 
        {
            $leave_reasons = $_POST['leave_reason']; 
        } 
        else 
        {
            $db_name= "hrm_db";
            $conn = new mysqli("localhost",  "root", "", $db_name, 3306);
            if($conn->connect_error)
            {
                $error= "Error connecting with the server";
            }
            else
            {
                $sql = "Select 1 from information_schema.tables where table_schema='hrm_db' and table_name='survey_&_feedback_tb'";
                $result= $conn->query($sql);

                if($result && $result->num_rows<=0)
                {
                    $create_tb_sql= "CREATE TABLE survey_feedback_tb (
                        username VARCHAR(50) NOT NULL,
                        emp_id VARCHAR(10) NOT NULL,
                        department VARCHAR(100) NOT NULL,
                        work_days_per_week INT NOT NULL,
                        work_hours_per_day INT NOT NULL,
                        avg_monthly_hours INT GENERATED ALWAYS AS (work_days_per_week * work_hours_per_day * 4) STORED,
                        promotion_in_5_years ENUM('yes', 'no') NOT NULL,
                        employee_salary DECIMAL(10, 2) NOT NULL,
                        salary_satisfaction INT CHECK (salary_satisfaction BETWEEN 1 AND 5),
                        salary_competitiveness INT CHECK (salary_competitiveness BETWEEN 1 AND 5),
                        role_satisfaction INT CHECK (role_satisfaction BETWEEN 1 AND 5),
                        work_environment_rating INT CHECK (work_environment_rating BETWEEN 1 AND 5),
                        leaving_status ENUM('yes', 'no') NOT NULL,
                        leave_reason TEXT,
                        PRIMARY KEY (username),
                        FOREIGN KEY (username, department) REFERENCES employee_data_tb(username, department)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8; ";

                    if ($conn->query($create_tb_sql) === TRUE) {
                        $error= "Table survey_feedback_tb created successfully.";
                    } else {
                        $error= "Error creating table";
                    }                
                }   
                if($result && $result->num_rows>0)
                {
                    $insert_sql = "INSERT INTO survey_feedback_tb (username, department, work_days_per_week, work_hours_per_day, average_monthly_hours, promotion_in_5_years, employee_salary, salary_satisfaction, salary_competitiveness, current_role_satisfaction, work_environment, leaving, reason_for_leaving) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $stmt = $conn->prepare($insert_sql);
                    $stmt->bind_param("ssiiisiiissis", $username, $department, $work_days_per_week, $work_hours_per_day, $average_monthly_hours, $promotion_in_5_years, $employee_salary, $salary_satisfaction, $salary_competitiveness, $current_role_satisfaction, $work_environment, $leaving, $reason_for_leaving);
                    $stmt->execute();

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
    <!-- <link rel="stylesheet" href="emp_basic_structure.css"> -->
    <link rel="stylesheet" href="basic_structure.css">
    <link rel="stylesheet" href="emp_feedback_survey.css">
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
            <li class="active">
                <a href="">
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
        <h1>Survey & Feedback</h1>
        <p class="p1">
            We value your feedback and aim to improve the work environment and employee experience at our institution.
            <br>This survey is designed to help us understand the factors that contribute to employee satisfaction and
            attrition. 
        </p>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <ol type="1">
                <div class="question">
                    <li><label class="ques">On average, how many days do you work per week?</label></li>
                    <select name="work_days_per_week">
                        <option value="" disabled selected>Select Days</option>
                        <option value="1">1 Day</option>
                        <option value="2">2 Days</option>
                        <option value="3">3 Days</option>
                        <option value="4">4 Days</option>
                        <option value="5">5 Days</option>
                        <option value="6">6 Days</option>
                        <option value="7">7 Days</option>
                    </select>
                </div>

                <div class="question">
                    <li><label class="ques">On average, how many hours do you work per day?</label></li>
                    <input type="number" placeholder="Enter hours" name="work_hours_per_day">
                </div>

                <div class="question">
                    <li><label class="ques">Were you promoted in the last 5 years in this organization?</label></li>
                    <input type="radio" name="promotion_in_5_years" value="1" id="promotion_y"><label for="promotion_y">Yes</label>
                    <input type="radio" name="promotion_in_5_years" value="0" id="promotion_n"><label for="promotion_n">No</label>
                </div>

                <div class="question">
                    <li><label class="ques">What is your current monthly compensation?</label></li>
                    <div><input type="radio" name="curr_salary" value="1" id="sal1"><label for="sal1">Less than 30k</label></div>
                    <div><input type="radio" name="curr_salary" value="2" id="sal2"><label for="sal2">Between 30k and 50k</label></div>
                    <div><input type="radio" name="curr_salary" value="3" id="sal3"><label for="sal3">Between 50k and 70k</label></div>
                    <div><input type="radio" name="curr_salary" value="4" id="sal4"><label for="sal4">Between 70k and 1L</label></div>
                    <div><input type="radio" name="curr_salary" value="5" id="sal5"><label for="sal5">More than 1L</label></div>
                </div>

                <div class="question">
                    <li><label class="ques">How satisfied are you with your current salary?</label></li>
                    <div><input type="radio" name="curr_salary_satisfaction" value="5" id="sal_satisfaction5"><label for="sal_satisfaction5">Very Satisfied</label></div>
                    <div><input type="radio" name="curr_salary_satisfaction" value="4" id="sal_satisfaction4"><label for="sal_satisfaction4">Satisfied</label></div>
                    <div><input type="radio" name="curr_salary_satisfaction" value="3" id="sal_satisfaction3"><label for="sal_satisfaction3">Neutral</label></div>
                    <div><input type="radio" name="curr_salary_satisfaction" value="2" id="sal_satisfaction2"><label for="sal_satisfaction2">Dissatisfied</label></div>
                    <div><input type="radio" name="curr_salary_satisfaction" value="1" id="sal_satisfaction1"><label for="sal_satisfaction1">Very Dissatisfied</label></div>
                </div>

                <div class="question">
                    <li><label class="ques">How competitive do you think the compensation is compared to similar roles at other institutions?</label></li>
                    <div><input type="radio" name="compensation_competitiveness" value="5" id="sal_compete5"><label for="sal_compete5">Much more competitive</label></div>
                    <div><input type="radio" name="compensation_competitiveness" value="4" id="sal_compete4"><label for="sal_compete4">Somewhat more competitive</label></div>
                    <div><input type="radio" name="compensation_competitiveness" value="3" id="sal_compete3"><label for="sal_compete3">About the same</label></div>
                    <div><input type="radio" name="compensation_competitiveness" value="2" id="sal_compete2"><label for="sal_compete2">Somewhat less competitive</label></div>
                    <div><input type="radio" name="compensation_competitiveness" value="1" id="sal_compete1"><label for="sal_compete1">Very less competitive</label></div>
                </div>
                
                <div class="question">
                    <li><label class="ques">How satisfied are you with your current role?</label></li>
                    <div><input type="radio" name="curr_role_satisfaction" value="5" id="role_satisfaction5"><label for="role_satisfaction5">Very Satisfied</label></div>
                    <div><input type="radio" name="curr_role_satisfaction" value="4" id="role_satisfaction4"><label for="role_satisfaction4">Satisfied</label></div>
                    <div><input type="radio" name="curr_role_satisfaction" value="3" id="role_satisfaction3"><label for="role_satisfaction3">Neutral</label></div>
                    <div><input type="radio" name="curr_role_satisfaction" value="2" id="role_satisfaction2"><label for="role_satisfaction2">Dissatisfied</label></div>
                    <div><input type="radio" name="curr_role_satisfaction" value="1" id="role_satisfaction1"><label for="role_satisfaction1">Very Dissatisfied</label></div>
                </div>

                <div class="question">
                    <li><label class="ques">How would you rate the overall work environment in the institution?</label></li>
                    <div><input type="radio" name="work_environment" value="4" id="work_env4"><label for="work_env4">Excellent</label></div>
                    <div><input type="radio" name="work_environment" value="3" id="work_env3"><label for="work_env3">Good</label></div>
                    <div><input type="radio" name="work_environment" value="2" id="work_env2"><label for="work_env2">Fair</label></div>
                    <div><input type="radio" name="work_environment" value="1" id="work_env1"><label for="work_env1">Poor</label></div>
                </div>

                <div class="question">
                    <li><label class="ques">Are you considering leaving the institution?</label></li>
                    <input type="radio" name="leave" value="1" id="leave_y"><label for="leave_y">Yes</label>
                    <input type="radio" name="leave" value="0" id="leave_n"><label for="leave_n">No</label>
                </div>

                <div class="question">
                    <li><label class="ques">If you are considering leaving the institution, what are your primary reasons? (optional)</label></li>
                    <div><input type="checkbox" id="reason_1" name="leave_reason" value="Better job opportunity"><label for="reason_1">Better job opportunity</label></div>
                    <div><input type="checkbox" id="reason_2" name="leave_reason" value="Lack of growth opportunities"><label for="reason_2">Lack of growth opportunities</label></div>
                    <div><input type="checkbox" id="reason_3" name="leave_reason" value="Work-life balance Issues"><label for="reason_3">Work-life balance Issues</label></div>
                    <div><input type="checkbox" id="reason_4" name="leave_reason" value="Compensation and benefits"><label for="reason_4">Compensation and benefits</label></div>
                    <div><input type="checkbox" id="reason_5" name="leave_reason" value="Unhealthy work environment"><label for="reason_5">Unhealthy work environment</label></div>
                    <div><input type="checkbox" id="reason_6" name="leave_reason" value="Personal reasons"><label for="reason_6">Personal reasons</label></div>
                    <div><input type="checkbox" id="reason_7" name="leave_reason" value="Other"><label for="reason_7">Other</label></div>
                </div>
            </ol>
                <div class="php_error"><?php echo  $error; ?></div>
                <input type="submit" value="Submit">
        </form>
    </div>

    <footer>
        Your responses will remain confidential and will be used solely for analysis to enhance employee retention
        and well-being. Please take a few minutes to share your honest feedback.
    </footer>
</body>
</html>