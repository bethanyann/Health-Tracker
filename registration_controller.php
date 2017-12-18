<?php
// Program 3
// SESSION COOKIE STUFF HERE
session_start();

require_once('database.php');
require_once('ValidationClass.php');
//make a validation class object
$validate = new ValidationClass();

$action = filter_input(INPUT_POST, 'action');
if ($action === NULL) {
    $action = filter_input(INPUT_GET, 'action');
        if ($action === NULL) {
        $action = 'register';
    }
}

switch($action)
{
    case 'check_loggedin_status':
        if(isset($loggedInStatus))
        {
            if($loggedInStatus === 1)
            {
                include('register.php');
                exit();
            }
            else
            {   
                $username = '';
                include('register.php');
                exit();
            }
        }
        else
        {
            $loggedInStatus=0;
            include('register.php');
            exit();
        }
        exit();
        break;
    case 'register':
        $name = filter_input(INPUT_POST, "name");
        $date_of_birth = filter_input(INPUT_POST, "dob");
        $weight = filter_input(INPUT_POST, "weight");
        $height = filter_input(INPUT_POST, "height");
        $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
        $username = filter_input(INPUT_POST, "username" );
        $password = filter_input(INPUT_POST, "password");
        $error_message = "";
        //maybe change this so gender isn't required - make gender a radio button?  ------------------>
        //check each field going down the list in order

        if($validate->isValuePresent($name, "Your Name") && $validate->isValidString($name, "Your Name")) //testing name
        {
           if($validate->isValuePresent($dob, "date of birth") ) //testing date of birth
           {
              if($validate->isValuePresent($email, "email address")) //testing email address 
              {
                  if($validate->isValuePresent($username, "username") && $validate->isUsernameFormatAllowed($username, "username"))
                  {
                      if($validate->isValuePresent($password, "password") && $validate->isPasswordFormatAllowed($password, "password"))
                      {
                        //IF EVERYTHING DOESNT SUCK, PROCEED WITH USER REGISTRATION
                        $name_filtered = htmlspecialchars($name);
                        $dob_filtered = htmlspecialchars($date_of_birth);
                        $gender_filtered = htmlspecialchars($gender);
                        $weight_filtered = htmlspecialchars ($weight);
                        $height_filtered = htmlspecialchars ($height);
                        $email_filtered = htmlspecialchars($email);
                        $username_filtered = htmlspecialchars($username);
                        $password_filtered = htmlspecialchars($password);

                        //Password hash stuff here
                        $options = [ 'cost' => 10,
                                     //'salt' => "psalt"
                            ];

                        $hash = password_hash($password_filtered, PASSWORD_BCRYPT, $options);

                        //call database and check if username is unique
                        $exists = check_username_exists($username_filtered);

                        //if username is unique, create a new user in the database
                        if(!$exists)
                        {
                           //create new user
                           $new_user_created = create_new_user_account($name_filtered, $dob_filtered, $gender_filtered, $email_filtered, $username_filtered, $hash);
                           if($new_user_created)
                           { //put the user into a session variable
                                $user = get_user($username_filtered);
                                $_SESSION['userNameLoggedIn'] = $user[0]; //name of the session array
                                $name = $_SESSION['userNameLoggedIn']['Name'];
                                $username = $_SESSION['userNameLoggedIn']['Username'];
                                $email = $_SESSION['userNameLoggedIn']['Email'];
                                $dateofbirth = $_SESSION['userNameLoggedIn']['DOB'];

                                $loggedInStatus = 1;
                                include('profile.php');
                                exit();
                           }
                           else
                           {
                              $error_message = "Your profile was not successfully created. Please enter your information and try again";
                              $loggedInStatus=0;
                              include('register.php');
                              exit();

                           }
                        }
                        else //send an error message to the user that their username must be unique
                        {
                           $error_message = "That username is already in use. Please choose a different username";
                           include('register.php');
                           exit();
                        }  
                     }
                     else{
                        $error_message = $validate->getErrorMessage();
                        include('register.php');
                        exit();
                     }
                  }
                  else{
                     $error_message = $validate->getErrorMessage();
                     include('register.php');
                     exit();
                  }
             }
             else{
                $error_message = $validate->getErrorMessage();
                include('register.php');
                exit();
              }
           }
           else{
             $error_message = $validate->getErrorMessage();
             include('register.php');
             exit();
           }
        }
        else{
            $error_message = $validate->getErrorMessage();
            include('register.php');
            exit();
        }
        
    break;
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
            //$date = filter_input(INPUT_POST, "date"); PLACEHOLDER FOR THE DATE VALUE TO GO HERE 
            if($validate->isValuePresent($weight, "weight") && $validate->isValueNumeric($weight,"weight") && $validate->isWithinRange($weight, "weight", 50, 700)) 
            {
                if($validate->isValuePresent($height_ft,"height in feet") && $validate->isValueNumeric($height_ft,"height in feet") && $validate->isWithinRange($height_ft, "height", 2, 8)  )
                {
                if($validate->isValuePresent($height_in,"height in inches") && $validate->isValueNumeric($height_in,"height in inches") && $validate->isWithinRange($height_in, "height in inches", 1, 11)  )
                {
//DO VALIDATION FOR THE DATE HERE //PLACEHOLDER FOR THE DATE VALIDATION HERE
                    $height_ft_filtered = htmlspecialchars($height_ft);
                    $height_in_filtered = htmlspecialchars($height_in);
                    $weight_filtered = htmlspecialchars($weight);
                    
                    $total_height_in_inches = ($height_ft_filtered*12)+$height_in_filtered;
                    //calculate BMI here? 
                    $weight_kgs = $weight_filtered*.45;
                    $height_meters = $total_height_in_inches*.025;
                    $height_squared = $height_meters*$height_meters;
                    $bmi_unformatted = $weight_kgs/$height_squared;
                    $bmi=sprintf("%.2f", $bmi_unformatted);
                    $username = $_SESSION['userNameLoggedIn']['Username'];
                    $date = "March 11, 2017"; //FIX THE DATE STUFF
                    //insert data into database
                    $success = insert_bmi_data($username,$date,$weight,$total_height_in_inches,$bmi);
            
                    if($success){//then go back to profile page to generate chart of new data
                         //$columnChart = generate_bmi_chart($username); //cant call chart method from here
                        include_once('profile.php');
                    }
                    //$columnChart->render();  
                    exit();
                }
                else
                {
                    $error_message = $validate->getErrorMessage();
                    include('enter_stats.php');
                    exit(); 
                }
                }
                else
                {
                    $error_message = $validate->getErrorMessage();
                    include('enter_stats.php');
                    exit();   
                }
            }
            else
            {
                $error_message = $validate->getErrorMessage();
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
            if($validate->isValuePresent($hours_slept, "hours slept") && $validate->isValueNumeric($hours_slept,"hours slept") && $validate->isWithinRange($hours_slept, "hours slept", .5, 24)) 
            {
                $hours_filtered = htmlspecialchars($hours_slept);
                $username = $_SESSION['userNameLoggedIn']['Username'];
                    $date = "March 11, 2017"; //FIX THE DATE STUFF
                    //insert data into database
                    $success = insert_sleep_data($username,$date,$hours_filtered);
            
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
?>