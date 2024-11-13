<?php
session_start();
$error = "";

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['username'])) 
{
    header('Location: /Minor_project/main/login.php');
    exit();
} 
elseif ($_SESSION['Role'] !== 'HR') 
{
    exit();  
} 
else 
{
    $username = $_SESSION['username'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        if (isset($_POST["job_description"]) && !empty($_POST["job_description"]) && isset($_FILES["resume_files"]) )
        {
            $job_description = $_POST["job_description"];
            $uploaded_files = $_FILES["resume_files"];

            $resume_paths = [];
            $upload_dir = "uploads/";

            // Ensure the upload directory exists
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Loop through each uploaded file
            foreach ($uploaded_files['tmp_name'] as $index => $tmp_name) 
            {
                // Check for errors in file upload
                if ($uploaded_files['error'][$index] !== 0) 
                {
                    $error = "Error uploading files.";
                    break;
                }

                // Get file type and validate
                $file_type = mime_content_type($tmp_name);
                $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain'];
                
                if (!in_array($file_type, $allowed_types)) 
                {
                    $error = "Invalid file type for file " . htmlspecialchars($uploaded_files['name'][$index]) . ". Only PDF, Word, and text files are allowed.";
                    break;
                }
                
                $filename = basename($uploaded_files['name'][$index]);
                $target_path = $upload_dir . $filename;
                if (move_uploaded_file($tmp_name, $target_path)) 
                {
                    $resume_paths[] = realpath($target_path); // Collect full file path
                } 
                else 
                {
                    $error = "Failed to save file $filename.";
                    break;
                }
            }
            
            if (empty($error) && !empty($resume_paths)) 
            {
                // Call Flask app with cURL
                foreach ($resume_paths as $file_path) 
                {
                    // Call Flask app with cURL for each resume
                    $response = process_resume_with_ml($file_path, $job_description);

                    // Handle the response from Flask
                    if ($response && isset($response['score'])) 
                    {
                        // Display each resume's score
                        echo "Resume: " . htmlspecialchars($file_path) . " - Score: " . htmlspecialchars($response['score']) . "<br>";
                    } 
                    else 
                    {
                        echo "Error processing resume: " . htmlspecialchars($file_path) . "<br>";
                    }
                }
            } 
            else 
            {
                $error = "Please upload valid resumes and provide a job description.";
            }
        } 
        else 
        {
            $error = "Please upload resumes and provide a job description.";
        }
    }
}

// Function to process resumes with the ML model (stubbed out)
function process_resume_with_ml($file_path, $job_description) {
    $url = "http://127.0.0.1:5001/process";
    
    // Prepare data
    $data = [
        'job_description' => $job_description,
        'resume_files' => new CURLFile($file_path)
    ];

    // Initialize cURL and set options
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($json_data)
    ]);

    // Execute cURL request
    $response = curl_exec($ch);
    
    // Check for errors
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);

    // Decode the response from the Flask app
    return json_decode($response, true);
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Minor_project/main/basic_structure.css">
    <link rel="stylesheet" href="resume_screening.css">
    <title>HRMinder | Ai Resume Screening</title>
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
            <div class="username"><?php echo  $_SESSION['username']; ?></div>
            <!-- <div class="username">USER NAME</div> -->
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
                <a href="/Minor_project/main/dashboard.php">
                    <img src="/Minor_project/main/Dashborad.png" alt="Dashboard">
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="/Minor_project/main/employee_data.php">
                    <img src="/Minor_project/main/contact-data.png" alt="Employee Data">
                    <span>Employee Data</span>
                </a>
            </li>
            <li>
                <a href="/Minor_project/main/report.php">
                    <img src="/Minor_project/main/Report.png" alt="Analysis and Report">
                    <span>Analysis & Report</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="/Minor_project/main/attrition.png" alt="Attrition Prediction">
                    <span>Attrition Prediction</span>
                </a>
            </li>
            <li class="active">
                <a href="#">
                    <img src="/Minor_project/main/resume_icon.png" alt="AI Resume Screening">
                    <span>AI Resume Screening</span>
                </a>
            </li>        
            <li>
                <a href="/Minor_project/main/feedback_survey.php">
                    <img src="/Minor_project/main/Survey.png" alt="Survey & feedback">
                    <span>Survey & Feedback</span>
                </a>
            </li>
            <li>
                <a href="help_support.php">
                    <img src="/Minor_project/main/Help_Support.png" alt="Help and Supprt">
                    <span>Help & Support</span>
                </a>
            </li>
        </ul>
    </div>   
    
    <div class="main_container">
        <h1>AI-Powered Resume Screening</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST" enctype="multipart/form-data">
        <!-- <form action="resume_screening.php" method="post" enctype="multipart/form-data"> -->
            <label>Job Description</label><br>
            <textarea name="job_description"></textarea><br>
            <label>Upload Resume</label><br>
            <input type="file" name="resume_files[]" multiple><br>
            <p>Please upload resumes in PDF, DOCX, or TXT format for best results. Our AI-powered screening tool will analyze and match candidate skills, experience, and qualifications to streamline your hiring process. You can also upload multiple files at once for faster bulk screening.</p>
            <div class="php_err"><?php echo $error; ?></div>
            <button type="submit" class="submit_but">Submit Resume</button>
        </form>

        <div class="resume_data">
        <h3>Resume Data</h3>

            <table>
                <tr>
                    <th>Resume ID</th>
                    <th>Candidate Name</th>
                    <th>Email ID</th>
                    <th>Phone Number</th>
                    <th>Skills</th>
                    <th>Education</th>
                </tr>
                <tr>

                <?php
                    $db_name = "hrm_db";
                    $conn = new mysqli("localhost", "root", "", $db_name, 3306);
        
                    if ($conn->connect_error) 
                    {
                        die("Connection failed: " . $conn->connect_error);
                    } 

                    $sql = "SELECT resume_id, candidate_name, email_id, phone_number, skills, education FROM resume_screening_tb";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['resume_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['candidate_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['skills']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['education']) . "</td>";
                            echo "</tr>";
                        }
                    } 
                ?>



            </table>
        </div>
    </div>

</body>

</html>