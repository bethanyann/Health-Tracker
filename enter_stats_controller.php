<?php
/* Program 3 */
/*CONTROLLER - will take user entered data, pass it to the database, and send to profile page to view updated data*/
session_start();

require_once('database.php');
require_once('ValidationClass.php');
include_once('fusion_php/fusioncharts.php');

//instantiate validation class 
$validate = new ValidationClass();

$action = filter_input(INPUT_POST, 'action');
if ($action === NULL) {
    $action = filter_input(INPUT_GET, 'action');
        if ($action === NULL) {
        $action = '';
    }
}

switch($action){
    case 'calc_bmi':
        if (!isset($_SESSION['userNameLoggedIn'])) //if user isn't logged in
        {
            $loggedInStatus = 0;
            $error_message="Please log in to your account before updating your info.";
            include('login.php');
            exit();
        }
        else //If user is logged in then update the stats
        {
            $loggedInStatus = 1;   
            $weight = filter_input(INPUT_POST, "weight");
            $height_ft = filter_input(INPUT_POST, "height_feet");
            $height_in = filter_input(INPUT_POST, "height_inches");
            $date_of_birth = filter_input(INPUT_POST, "dob");
            //$date = filter_input(INPUT_POST, "date"); PLACEHOLDER FOR THE DATE VALUE TO GO HERE 
            if($validate->isValuePresent($weight, "weight") && $validate->isValueNumeric($weight,"weight") && $validate->isWithinRange($weight, "weight", 50, 700)) 
            {
                if($validate->isValuePresent($height_ft,"height in feet") && $validate->isValueNumeric($height_ft,"height in feet") && $validate->isWithinRange($height_ft, "height", 2, 8)  )
                {
                if($validate->isValuePresent($height_in,"height in inches") && $validate->isValueNumeric($height_in,"height in inches") && $validate->isWithinRange($height_in, "height in inches", 1, 11)  )
                {
                    if($validate->isValuePresent($date_of_birth, "date of birth"))
                    {
//DO VALIDATION FOR THE DATE HERE //PLACEHOLDER FOR THE DATE VALIDATION HERE
                        $height_ft_filtered = htmlspecialchars($height_ft);
                        $height_in_filtered = htmlspecialchars($height_in);
                        $weight_filtered = htmlspecialchars($weight);
                        $dob_filtered = htmlspecialchars($date_of_birth);

                        $total_height_in_inches = ($height_ft_filtered*12)+$height_in_filtered;
                        //calculate BMI here? 
                        $weight_kgs = $weight_filtered*.45;
                        $height_meters = $total_height_in_inches*.025;
                        $height_squared = $height_meters*$height_meters;
                        $bmi_unformatted = $weight_kgs/$height_squared;
                        $bmi=sprintf("%.2f", $bmi_unformatted);

                        $username = $_SESSION['userNameLoggedIn']['Username'];
                       // $date = date("m-d-y"); //FIX THE DATE STUFF
                        //insert data into database
                        $success = insert_bmi_data($username,$dob_filtered,$weight_filtered,$total_height_in_inches,$bmi);

                        if($success){//then go back to profile page to generate chart of new data
                             //$columnChart = generate_bmi_chart($username); //cant call chart method from here
                            $name = $_SESSION['userNameLoggedIn']['Name'];
                            $email = $_SESSION['userNameLoggedIn']['Email'];
                            $dateofbirth = $_SESSION['userNameLoggedIn']['DOB'];
                            include_once('profile.php');
                        }  
                        exit();
                    }
                    else
                    {
                        $error_message_bmi = $validate->getErrorMessage();
                        include('enter_stats.php');
                        exit(); 
                    }
                }
                else
                {
                    $error_message_bmi = $validate->getErrorMessage();
                    include('enter_stats.php');
                    exit(); 
                }
                }
                else
                {
                    $error_message_bmi = $validate->getErrorMessage();
                    include('enter_stats.php');         
                    exit();   
                }
            }
            else
            {
                $error_message_bmi = $validate->getErrorMessage();
                include('enter_stats.php');
                exit(); 
            }
        }
        break;// END CALC BMI //
    case 'calc_sleep': 
        if (!isset($_SESSION['userNameLoggedIn'])) //if user isn't logged in
        {
            $loggedInStatus = 0;
            $error_message="Please log in to your account before updating your info.";
            include('login.php');
            exit();
        }
        else //if user is logged in, get info and insert into database
        {
            $loggedInStatus = 1;
            $hours_slept = filter_input(INPUT_POST,"num_hours");
            $date_of_birth = filter_input(INPUT_POST, "dob");

            if($validate->isValuePresent($hours_slept, "hours slept") && $validate->isValueNumeric($hours_slept,"hours slept") && $validate->isWithinRange($hours_slept, "hours slept", .5, 24)) 
            {
                if($validate->isValuePresent($date_of_birth, "date of birth"))
                {
                    $hours_filtered = htmlspecialchars($hours_slept);
                    $username = $_SESSION['userNameLoggedIn']['Username'];
                    $dob_filtered = htmlspecialchars($date_of_birth);  //FIX THE DATE STUFF
                        //insert data into database
                    $success = insert_sleep_data($username,$dob_filtered,$hours_filtered);
                    if($success)//then go back to profile page to generate chart of new data
                    {        //$columnChart = generate_sleep_chart($username); //cant call chart method from here
                            $name = $_SESSION['userNameLoggedIn']['Name'];
                            $username = $_SESSION['userNameLoggedIn']['Username'];
                            $email = $_SESSION['userNameLoggedIn']['Email'];
                            $dateofbirth = $_SESSION['userNameLoggedIn']['DOB'];
                            include_once('profile.php');
                    }  
                    else
                    {
                        $error_message_sleep = "Could not update your data";
                        include('enter_stats.php');
                        exit();
                    }
                }
                else //if validation does not pass
                {
                    $error_message_sleep = $validate->getErrorMessage();
                    include('enter_stats.php');
                    exit();   
                }
            }
            else //if validation does not pass
            {
                $error_message_sleep = $validate->getErrorMessage();
                include('enter_stats.php');
                exit();   
            }
            
        }
        break; //END CALC SLEEP //
    case 'calc_steps': //hopefully get this working? 
        if (!isset($_SESSION['userNameLoggedIn'])) //if user isn't logged in
        {
            $loggedInStatus = 0;
            $error_message="Please log in to your account before updating your info.";
            include('login.php');
            exit();
        }
        else //if user is logged in, get info and insert into database
        {
            $loggedInStatus=1;
            $weight = filter_input(INPUT_POST, "weight");
            $steps = filter_input(INPUT_POST, "steps");
            $date_of_birth = filter_input(INPUT_POST, "dob");

            
            if($validate->isValuePresent($weight, "weight") && $validate->isValueNumeric($weight,"weight") && $validate->isWithinRange($weight, "weight", 50, 700)) 
            {
                if($validate->isValuePresent($steps, "number of steps") && $validate->isValueNumeric($steps, "number of steps") && $validate->isWithinRange($steps, "number of steps", 50, 20000)) 
                {
                    if($validate->isValuePresent($date_of_birth, "date of birth"))
                    {
                    
                        $weight_filtered = htmlspecialchars($weight);
                        $steps_filtered = htmlspecialchars($steps);
                        $dob_filtered = htmlspecialchars($date_of_birth);
                        //do the math thing here
                        $cal_per_mile=$weight_filtered*0.57;
                        $num_steps_in_mile = 1800;
                        $cal_per_step = $cal_per_mile/$num_steps_in_mile;
                        $num_cal_burned=$steps_filtered*$cal_per_step;

                        $username = $_SESSION['userNameLoggedIn']['Username'];
                        //FIX THE DATE STUFF                    
                        //$date = date("m-d-y"); 

                        $success = insert_step_data($username,$dob_filtered,$weight_filtered,$steps_filtered,$num_cal_burned);
                        if($success)//then go back to profile page to generate chart of new data
                        {        //$columnChart = generate_sleep_chart($username); //cant call chart method from here
                                $name = $_SESSION['userNameLoggedIn']['Name'];
                                $username = $_SESSION['userNameLoggedIn']['Username'];
                                $email = $_SESSION['userNameLoggedIn']['Email'];
                                $dateofbirth = $_SESSION['userNameLoggedIn']['DOB'];
                                include_once('profile.php');
                        }  
                        else
                        {
                            $error_message_steps = "Could not update your data";
                            include('enter_stats.php');
                            exit();
                        }
                    }
                    else
                    {
                        $error_message_steps = $validate->getErrorMessage();
                        include('enter_stats.php');
                        exit();
                    }
                }
                else {
                    $error_message_steps = $validate->getErrorMessage();
                    include('enter_stats.php');
                    exit();
                }
            }
            else {
                $error_message_steps = $validate->getErrorMessage();
                include('enter_stats.php');
                exit();
            }
        }
        break;
    case 'profile_weight':
        $loggedInStatus = 1;
        header('Location: http://localhost/Project3/enter_stats.php#fitness_section3');
       // include('enter_stats.php#fitness_section3');
        exit();
        break;
    case 'profile_sleep':
        $loggedInStatus = 1;
        header('Location: http://localhost/Project3/enter_stats.php#fitness_section2');
        //include('enter_stats.php#fitness_section2');
        exit();
        break;
    case 'profile_activity':
        $loggedInStatus = 1;
        header('Location: http://localhost/Project3/enter_stats.php#fitness_section1');
        //include('enter_stats.php#fitness_section1');
        exit();
        break;
}