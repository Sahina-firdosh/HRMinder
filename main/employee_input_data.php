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
                header("Location: employee_data.php");
            }   
            if($_SERVER["REQUEST_METHOD"]=="POST")
            {
                if(isset($_FILES["myfile"]) && $_FILES["myfile"]["error"]==0)
                {
                    $myfile = $_FILES["myfile"]["name"];
                    $myfile_type = $_FILES["myfile"]["type"];
                    $myfile_ext=  strtolower(pathinfo($myfile, PATHINFO_EXTENSION));
                    if((($myfile_type === "text/csv") || ($myfile_type === "application/vnd.ms-excel") || ($myfile_type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")) && in_array($myfile_ext, ["csv", "xls", "xlsx"]))
                    {
                        $myfile = htmlspecialchars(basename($_FILES["myfile"]["name"]));
                        $error = "correct file";

                        // if($myfile_ext == "xls" ||  $myfile_ext == "xlsx")
                        // {
                        //     require '../vendor/autoload.php';
                        //     $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES["myfile"]["tmp_name"]);
                        //     $csvFile = '/tmp/tempfile.csv';
                        //     $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Csv');
                        //     $writer->save($csvFile);
                        //     $error = "excel->csv";
                        // }
                        // else
                        // {
                            $csvFile = $_FILES["myfile"]["tmp_name"];

                            if (($handle = fopen($csvFile, 'r')) !== FALSE) 
                            {
                                fseek($handle, 0);
                                $header = fgetcsv($handle);
                                if ($header === NULL || fgetcsv($handle) === FALSE) 
                                {
                                    die("CSV file must contain data rows beyond the header.");
                                }
            
                                $header = array_map(function($item) 
                                {
                                    // Replace invalid characters with underscores and trim whitespace
                                    return preg_replace('/[^a-zA-Z0-9_]/', '_', trim($item));
                                }, $header);
                                
                                // Create the table name
                                $tableName = 'jims_emp_data_tb';
                                
                                // Construct the CREATE TABLE SQL statement
                                $columns = array_map(function($col) 
                                {
                                    return "`$col` TEXT"; // Ensure column names are quoted to handle special characters
                                }, $header);
                                
                                $createTableSQL = "CREATE TABLE IF NOT EXISTS `$tableName` (" . implode(", ", $columns) . ")";

                                // Execute the query and check for errors
                                if ($conn->query($createTableSQL) === FALSE) 
                                {
                                    $error= "Error creating table: ";
                                }

                                $clearTableSQL = "TRUNCATE TABLE `$tableName`";
                                $conn->query($clearTableSQL);
            
                                $insertSQL = "INSERT INTO `$tableName` (" . implode(", ", $header) . ") VALUES ";
                                $data = [];
                                fseek($handle, 0); // Reset to the beginning of the file
                                fgetcsv($handle);
                                while (($row = fgetcsv($handle)) !== FALSE) 
                                {
                                    if (count($row) === count($header)) 
                                    {
                                        $row = array_map([$conn, 'real_escape_string'], $row);
                                        $data[] = "('" . implode("', '", $row) . "')";
                                    }
                                }
                            
                                if (count($data) > 0) 
                                {
                                    // Execute batch insert
                                    $insertSQL .= implode(", ", $data);
                                    if ($conn->query($insertSQL) === FALSE) 
                                    {
                                        die("Error inserting data: " . $conn->error);
                                    } 
                                }
                                else 
                                {
                                    $error= "No data to insert.";
                                }
                                fclose($handle);
                                if (isset($csvFile)) unlink($csvFile); // Remove the file if it exists
                            } 
                            else 
                            {
                                $error= "Unable to open file.";
                            }
                            $sql = "SELECT 1 FROM information_schema.tables WHERE table_schema = 'hrm_db' AND table_name = '$tableName' LIMIT 1";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0) 
                            {
                                header("Location: employee_data.php");
                                exit();
                            }   
                    }
                    else
                    {
                        $error = "Invalid file type! Please select a csv or excel file to upload.";
                    }
                }
                else
                {
                    $error = "Please select a file to upload.";
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
    <link rel="stylesheet" href="basic_structure.css">
    <link rel="stylesheet" href="employee_input_data.css">
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
        <h1>No Data Found!</h1>
        <p id="nodata_msg">Seems like there is no employee data available yet.<br>Please add employee data to get started.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" enctype="multipart/form-data">
            <p class="p1">Enter your organisation's employee data here</p>
            <input type="file" name="myfile">
            <p class="p2">Please upload your employee data file in one of the following formats :-  CSV or Excel. <br>These formats ensure that your data is properly structured & easy to import into our system.</p>
            <button type="submit"> Upload File</button>
            <div class="php_err"><?php echo $error; ?></div>
        </form>
    </div>
</body>

</html>