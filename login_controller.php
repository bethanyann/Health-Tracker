<?php
/* Program 3 */
/*CONTROLLER - will contain the login stuff as well as adding data to a user's profile page*/
$lifetime = 60 * 60 * 24 * 14;    // 2 weeks in seconds
session_set_cookie_params($lifetime, '/');
session_start();

require_once('database.php');
require_once('ValidationClass.php');
//instantiate validation class 
$validate = new ValidationClass();

$action = filter_input(INPUT_POST, 'action');
if ($action === NULL) {
    $action = filter_input(INPUT_GET, 'action');
        if ($action === NULL) {
        $action = 'login';
    }
}

switch($action){
    case 'check_if_logged_in_profile':
        if (!isset($_SESSION['userNameLoggedIn'])){
           $loggedInStatus = 0;
           $error_message = "You must be logged in to view your profile.";
           include('login.php');
           exit();
        }
        else{
            $loggedInStatus = 1;
            //set user values from session 
             $name = $_SESSION['userNameLoggedIn']['Name'];
             $username = $_SESSION['userNameLoggedIn']['Username'];
             $email = $_SESSION['userNameLoggedIn']['Email'];
             $dateofbirth = $_SESSION['userNameLoggedIn']['DOB'];
             //maybe make a call here to get height from the database and store in another session variable?
            include('profile.php');
            exit();
        }
        break;
    case 'userIsLoggedIn':
       
        break;
     case 'check_if_logged_in_index':
        if (!isset($_SESSION['userNameLoggedIn'])){
           $loggedInStatus = 0;
           $error_message = "You must be logged in to view your profile.";
           include('login.php');
           exit();
        }
        else{
            $loggedInStatus = 1;
            //set user values from session 
             $name = $_SESSION['userNameLoggedIn']['Name'];
             $username = $_SESSION['userNameLoggedIn']['Username'];
             $email = $_SESSION['userNameLoggedIn']['Email'];
             $dateofbirth = $_SESSION['userNameLoggedIn']['DOB'];
             //maybe make a call here to get height from the database and store in another session variable?
            include('profile.php');
            exit();
        }
        break;
    case 'login_page':
        $loggedInStatus = 0;
        include 'login.php';
        break;
    case 'logout': //hopefully get this working? $login_status = 0; 
        session_destroy();
        $loggedInStatus = 0;
        include 'login.php';
        break;
    case 'login': //coming from the login page
        //session_destroy();
        $username = filter_input(INPUT_POST, "username");
        $password = filter_input(INPUT_POST, "password");

        if($validate->isValuePresent($username, "username") && $validate->isUsernameFormatAllowed($username, "username"))
        {
            if($validate->isValuePresent($password, "password") && $validate->isPasswordFormatAllowed($password, "password"))
            {
                //if everything passes validation then 
                $password_filtered = htmlspecialchars($password);
                $user_name_filtered = htmlspecialchars($username);
                //check to see if username exsists then check to see if password matches
               
                $user_exists = check_username_exists($user_name_filtered);
                if($user_exists) //if the user does exist in the database
                {  
                    //puts user info in array $user
                    $user = get_user($user_name_filtered);
                    $stored_password = $user[0]['Password'];
                    
                   if(password_verify($password_filtered, $stored_password))
                   { //if username and password match, create session and go to the "profile" page. 
                        $_SESSION['userNameLoggedIn'] = $user[0]; //name of the session array
                        $name = $_SESSION['userNameLoggedIn']['Name'];
                        $username = $_SESSION['userNameLoggedIn']['Username'];
                        $email = $_SESSION['userNameLoggedIn']['Email'];
                        $dateofbirth = $_SESSION['userNameLoggedIn']['DOB'];
                        
                        $loggedInStatus = 1; //come back here and fix this stuff and things later maybe
                        include('profile.php');
                        exit();
                    }
                    else //if the password does not match the username
                    {
                        $login_error = "That seems to be the wrong password. Try, try again. "; 
                        include('login.php');
                        exit();
                    }
                }
                else //if the username does not exsist in the database
                {
                    $login_error = "That username does not exist. Try logging in again.";
                    include('login.php');
                    exit();
                }
        }
        else{ //if password does not pass validation
           $error_message = $validate->getErrorMessage();
           include('login.php');
           exit();
        }
    }
    else{ //if username does not pass validation 
       $error_message = $validate->getErrorMessage();
       include('login.php');
       exit();
    }
    break;
}//end of switch