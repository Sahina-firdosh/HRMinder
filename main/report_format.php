<?php

    session_start();
    $error="";
    $db_name= "hrm_db";
    $conn = new mysqli("localhost",  "root", "", $db_name, 3306);

    if($conn->connect_error)
    {
        $server_err= "Error connecting with the server";
    }
    else
    {
        $sno = 1;
        if (isset($_GET['format'])) 
        {
            $format = $_GET['format'];
            $result = null;
            switch ($format) 
            {
                case 1:
                    $sql = "Select Name, ID_number_Aadhar_number, Email, Gender, Designation, Date_of_joining, Number_of_sanctioned_posts_during_the_year from  jims_emp_data_tb";
                    $result= $conn->query($sql);
                    echo ' 
                    <h2>3.1 a) Number of full time teachers during the year 2023-24</h2>
                    <table>
                        <tr>
                            <th>S.No.</th>
                            <th>Name</th>
                            <th>ID number/Aadhar number (not mandatory)</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Designation</th>
                            <th>Date of joining institution</th>
                            <th>Number of sanctioned posts during the year</th>
                        </tr>';
                    while ($row = $result->fetch_assoc()) {
                    echo '<tr>
                            <td>' . $sno++ . '</td>
                            <td>' . $row['Name'] . '</td>
                            <td>' . $row['ID_number_Aadhar_number'] . '</td>
                            <td>' . $row['Email'] . '</td>
                            <td>' . $row['Gender'] . '</td>
                            <td>' . $row['Designation'] . '</td>
                            <td>' . $row['Date_of_joining'] . '</td>
                            <td>' . $row['Number_of_sanctioned_posts_during_the_year'] . '</td>
                        </tr>';
                    }
                    echo '</table>';

                    $sql = "Select Name, ID_number_Aadhar_number, Date_of_leaving, Email, Gender, Designation, Date_of_joining,  IF(Date_of_leaving ='NA', 'N/A', YEAR(STR_TO_DATE(Date_of_leaving, '%d.%m.%Y'))) AS Year_Of_Leaving from  jims_emp_data_tb;";
                    $result= $conn->query($sql);
                    echo ' 
                    <h2>3.1 b) Number of full time teachers who left/joined the institution during the year (01 August 2023 to 31 July 2024)</h2>
                    <table>
                        <tr>
                            <th>Name</th>
                            <th>ID number/Aadhar number</th>
                            <th>Year in which left/joined/resigned/ retired etc.</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Designation</th>
                            <th>Date of joining</th>
                            <th>Date of leaving</th>
                        </tr>';
                    while ($row = $result->fetch_assoc()) {
                    echo '<tr>
                            <td>' . $row['Name'] . '</td>
                            <td>' . $row['ID_number_Aadhar_number'] . '</td>
                            <td>' . $row['Year_Of_Leaving'] . '</td>
                            <td>' . $row['Email'] . '</td>
                            <td>' . $row['Gender'] . '</td>
                            <td>' . $row['Designation'] . '</td>
                            <td>' . $row['Date_of_joining'] . '</td>
                            <td>' . $row['Date_of_leaving'] . '</td>
                        </tr>';
                    }
                    echo '</table>';
                    break;
                case 2:
                    $sql = "SELECT Name, PAN, Designation,  YEAR(STR_TO_DATE(Date_of_joining, '%d.%m.%Y')) AS Year_of_appointment, Nature_of_appointment, Department, TIMESTAMPDIFF(YEAR, STR_TO_DATE(Date_of_joining, '%d.%m.%Y'), '2024-07-31') AS Total_years_of_Experience_in_the_same_institution, Date_of_leaving, CASE WHEN Date_of_leaving='NA' THEN 'Yes' ELSE YEAR(STR_TO_DATE(Date_of_leaving, '%d.%m.%Y')) END AS job_status FROM jims_emp_data_tb";
                        $result = $conn->query($sql);

                        echo ' 
                        <h2>2.4.1 Number of full-time teachers against sanctioned posts during the year 2023-24</h2>
                        <h2>2.4.3 Number of years of teaching experience of full time teachers in the same institution (Data for the latest completed academic year) 2023-24</h2>
                        <table>
                            <tr>
                                <th>S.No.</th>
                                <th>Name of the Full-time teacher</th>
                                <th>PAN</th>
                                <th>Designation</th>
                                <th>Year of appointment</th>
                                <th>Nature of appointment (Against Sanctioned post, temporary, permanent)</th>
                                <th>Name of the Department</th>
                                <th>Total years of Experience in the same institution upto 31.07.2024</th>
                                <th>Is the teacher still serving the institution/If not last year of the service of Faculty to the Institution</th>
                            </tr>';

                        $serialNo = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>
                                    <td>' . $serialNo++ . '</td>
                                    <td>' . $row['Name'] . '</td>
                                    <td>' . $row['PAN'] . '</td>
                                    <td>' . $row['Designation'] . '</td>
                                    <td>' . $row['Year_of_appointment'] . '</td>
                                    <td>' . $row['Nature_of_appointment'] . '</td>
                                    <td>' . $row['Department'] . '</td>
                                    <td>' . $row['Total_years_of_Experience_in_the_same_institution'] . '</td>
                                    <td>' . $row['job_status'] . '</td>
                                </tr>';
                        }
                        echo '</table>';
                    break;
                case 3:
                    $sql = "SELECT Name, Highest_Qualification, Year_of_obtaining,  YEAR(STR_TO_DATE(Date_of_joining, '%d.%m.%Y')) AS Year_of_appointment, Topic_of_the_Ph_D__Degree___Subject_of_UGC_NET, Date_of_leaving, CASE WHEN Date_of_leaving='NA' THEN 'Yes' ELSE YEAR(STR_TO_DATE(Date_of_leaving, '%d.%m.%Y')) END AS job_status FROM jims_emp_data_tb WHERE Highest_Qualification IN ('Ph.D', 'D.M', 'M.Ch.', 'D.N.B Superspeciality', 'D.Sc.', 'D.Litt.')";
                        $result = $conn->query($sql);

                        echo ' 
                        <h2>2.4.2 Number of full time teachers with Ph. D. / D.M. / M.Ch. /D.N.B Superspeciality / D.Sc. / D.Litt. during the year 2023-24 (consider only highest degree for count)</h2>
                        <table>
                            <tr>
                                <th>Name of full time teacher with Ph.D./D.M/M.Ch./D.N.B Superspeciality/D.Sc./D’Lit.</th>
                                <th>Qualification (Ph.D./D.M/M.Ch./D.N.B Superspeciality/ D.Sc./D’Lit. )</th>
                                <th>Year of obtaining</th>
                                <th>Year of appointment</th>
                                <th>Topic of the Ph.D. Degree / Subject of UGC-NET</th>
                                <th>Is the teacher still serving the institution/If not last year of the service of Faculty to the Institution</th>
                            </tr>';

                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>
                                    <td>' . $row['Name'] . '</td>
                                    <td>' . $row['Highest_Qualification'] . '</td>
                                    <td>' . $row['Year_of_obtaining'] . '</td>
                                    <td>' . $row['Year_of_appointment'] . '</td>
                                    <td>' . $row['Topic_of_the_Ph_D__Degree___Subject_of_UGC_NET'] . '</td>
                                    <td>' . $row['job_status'] . '</td>
                                </tr>';
                        }
                        echo '</table>';
                    break;
                case 4:

                    break;
                case 5:
                    $sql = "SELECT Country_Name, Passport_Number, Department, Name, Email, Mobile_No, Designation, Gender, Date_of_Birth, Social_Category, Social_Category, Religious_Community, PWD, Nature_of_appointment, Selection_Mode, Date_of_joining, Date_of_Joining_Teaching_Profession, Highest_Qualification, Additional_Eligibility_Qualification, Broad_Discipline_Group_Category, Broad_Discipline_Group_Name, Whether_Vocational_Course, Year_Spent_Other_Than_Teaching_job, Date_of_leaving, CASE WHEN Date_of_leaving IS NULL THEN 'Working' ELSE 'Not Working' END AS job_status FROM jims_emp_data_tb ";
                    $result = $conn->query($sql);
                    
                        echo ' 
                        <h2>Teaching Staff Details</h2>
                        <table>
                            <tr>
                                <th>S.No.</th>
                                <th>Country Name</th>
                                <th>Passport Number</th>
                                <th>Department Name</th>
                                <th>Name Of The Employee</th>
                                <th>Email Id</th>
                                <th>Mobile No</th>
                                <th>Designation</th>
                                <th>Gender</th>
                                <th>DOB</th>
                                <th>Social Category</th>
                                <th>Religious Community</th>
                                <th>PWD</th>
                                <th>Nature Of Appointment</th>
                                <th>Selection Mode</th>
                                <th>Date of Joining</th>
                                <th>Date of Joining Teaching Profession</th>
                                <th>Highest Qualification</th>
                                <th>Additional/Eligibility Qualification</th>
                                <th>Broad Discipline Group Category</th>
                                <th>Broad Discipline Group Name</th>
                                <th>Whether Vocational Course</th>
                                <th>Year Spent Other Than Teaching job</th>
                                <th>Job Status</th>
                            </tr>';

                        $serialNo = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>
                                    <td>' . $serialNo++ . '</td>
                                    <td>' . $row['Country_Name'] . '</td>
                                    <td>' . $row['Passport_Number'] . '</td>
                                    <td>' . $row['Department'] . '</td>
                                    <td>' . $row['Name'] . '</td>
                                    <td>' . $row['Email'] . '</td>
                                    <td>' . $row['Mobile_No'] . '</td>
                                    <td>' . $row['Designation'] . '</td>
                                    <td>' . $row['Gender'] . '</td>
                                    <td>' . $row['Date_of_Birth'] . '</td>
                                    <td>' . $row['Social_Category'] . '</td>
                                    <td>' . $row['Religious_Community'] . '</td>
                                    <td>' . $row['PWD'] . '</td>
                                    <td>' . $row['Nature_of_appointment'] . '</td>
                                    <td>' . $row['Selection_Mode'] . '</td>
                                    <td>' . $row['Date_of_joining'] . '</td>
                                    <td>' . $row['Date_of_Joining_Teaching_Profession'] . '</td>
                                    <td>' . $row['Highest_Qualification'] . '</td>
                                    <td>' . $row['Additional_Eligibility_Qualification'] . '</td>
                                    <td>' . $row['Broad_Discipline_Group_Category'] . '</td>
                                    <td>' . $row['Broad_Discipline_Group_Name'] . '</td>
                                    <td>' . $row['Whether_Vocational_Course'] . '</td>
                                    <td>' . $row['Year_Spent_Other_Than_Teaching_job'] . '</td>
                                    <td>' . $row['job_status'] . '</td>
                                </tr>';
                        }
                        echo '</table>';
                    break; 
                case 6:
                    $sql = "SELECT Name, Gender, PAN, Designation, Date_of_Birth, Highest_Qualification, Date_of_joining, Association_type,Date_of_Joining_Teaching_Profession, TIMESTAMPDIFF(YEAR, STR_TO_DATE(Date_of_Joining_Teaching_Profession, '%d.%m.%Y'), '2024-07-31') AS Total_industry_Experience_in_month, (TIMESTAMPDIFF(YEAR, STR_TO_DATE(Date_of_Joining_Teaching_Profession, '%d.%m.%Y'), '2024-07-31') + (Year_Spent_Other_Than_Teaching_job * 12)) AS Total_teaching_Experience_in_month, Date_of_leaving, CASE WHEN Date_of_leaving = 'NA' THEN 'Yes' ELSE 'No' END AS job_status FROM jims_emp_data_tb";
                    $result = $conn->query($sql);
                    
                        echo ' 
                        <h2>2.4.1 Number of full-time teachers against sanctioned posts during the year 2023-24</h2>
                        <h2>2.4.3 Number of years of teaching experience of full time teachers in the same institution (Data for the latest completed academic year) 2023-24</h2>
                        <table>
                            <tr>
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Designation</th>
                                <th>Date of Birth</th>
                                <th>Highest University Degree</th>
                                <th>PAN Number</th>
                                <th>Total Teaching Experience (In Months) as on 31st July 2023</th>
                                <th>Total Industry Experience (In Months) as on 31st July 2023</th>
                                <th>Currently working with institution?</th>
                                <th>Date of Joining the institute</th>
                                <th>"Association type(Regular/Adhoc/ Visiting)"</th>
                                <th>Date of leaving the institute</th>
                                </tr>';
                                
                        $serialNo = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>
                                    <td>' . $serialNo++ . '</td>
                                    <td>' . $row['Name'] . '</td>
                                    <td>' . $row['Gender'] . '</td>
                                    <td>' . $row['Designation'] . '</td>
                                    <td>' . $row['Date_of_Birth'] . '</td>
                                    <td>' . $row['Highest_Qualification'] . '</td>
                                    <td>' . $row['PAN'] . '</td>
                                    <td>' . $row['Total_teaching_Experience_in_month'] . '</td>
                                    <td>' . $row['Total_industry_Experience_in_month'] . '</td>

                                    <td>' . $row['job_status'] . '</td>
                                    <td>' . $row['Date_of_joining'] . '</td>
                                    <td>' . $row['Association_type'] . '</td>
                                    <td>' . $row['Date_of_leaving'] . '</td>
                                </tr>';
                        }
                        echo '</table>';
                    break;
                default:
                    echo "Invalid format selected.";
            }
        }
    }

?>