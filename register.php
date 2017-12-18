<!-- Program 3 -->
<!-- VIEW -->
<?php include 'header.php'; 
    if(!isset($error_message)) { $error_message = ''; } //if no error message set, set it to blank.
    if(!isset($name)) { $name = ''; }
    if(!isset($date_of_birth)) { $date_of_birth = ''; }
    if(!isset($email)) { $email = ''; }
    if(!isset($username)) { $username = ''; }
    if($username == 'root') { $username = '';}
    if(!isset($_SESSION)) { session_start(); }
?>
<!DOCTYPE html>
<main id="reg_form">
    <h2 class="center_heading">Please complete the form below to register!</h2>
        <form action="registration_controller.php" method="POST">
            <table id="reg_table">
		<tr>
                    <th><label>Name:</label></th>
                    <td><input type="text" size="25" name="name" value="<?php echo $name ?>"/></td>
		</tr>
                <tr> 
                    <!--put a date picker here??? --> 
                    <th><label>Date of Birth:</label></th>
                    <td><input type="date" size="25" name="dob" value="<?php echo $date_of_birth ?>"/></td>
                </tr>
                <tr>
                    <th><label>Email:</label></th>
                    <td><input type="text" size="25" name="email" value="<?php echo $email ?>"/></td> 
                </tr>
		<tr>
                    <th><label>Username:</label></th>
                    <td><input type="text" size="25" name="username" value="<?php echo $username ?>"/></td>
		</tr>
		<tr>
                    <th><label>Password:</label></th>
                    <td><input type="password" size="25" name="password"/></td>
		</tr>
		<tr>
                    <td>
                    <input type="hidden" name="action" value="register"></td>
                    <td><input type="submit" class="submit" name="submit" value="Register" /></td>
		</tr>
                <tr>
                    <td></td>
                    <td><span class="error_message_display"> <?php echo $error_message; ?></span></td>
                </tr>
                <tr> 
                    <td></td>
                    <td> <p>Already registered? Click <a href="login.php">here</a> to log in.</p> </td>
                </tr>
            </table>
	</form>
    </main>
<?php include 'footer.php'; ?>

    

