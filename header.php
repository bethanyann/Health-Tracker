<?php 
if(!isset($loggedInStatus)){$loggedInStatus = 0;}?>
<!DOCTYPE html>
<!-- Program 3 --> 
<html>
<!--Bethany Jauken -->
	<head>
		<title>Fitness tracking Website - PHP Program 3</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="project3.css">
                <script type="text/javascript" src="fusion_javascript/fusioncharts.js"></script>
	</head>
<body>
<div id="wrapper">
    <header>
        <div class="floatleft center_vertical"><img src="images/title.png" alt="health tracker" width="280px" height="20px"></div>
            <nav> 
                <ul class="floatright center_vertical">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="registration_controller.php?action=check_loggedin_status">Create An Account</a></li>
                    <li><a href="login_controller.php?action=check_if_logged_in_profile">Profile</a></li>
                   <!-- <li><a href="phpgoeshere if($loggedInStatus == 1){ echo 'Log Out'; } else{ echo 'Log In'; } ?>"</a></li>-->
                    <li><a href="<?php if($loggedInStatus == 1){ echo 'login_controller.php?action=logout"\>Logout'; } else{ echo 'login_controller.php?action=login_page"\>Login'; } ?></a></li>
                </ul>
            </nav>
    </header>