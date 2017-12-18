<!-- Program 3 --> 
<!-- VIEW -->
<?php include 'header.php'; 
      if(!isset($username)){ $username = ''; }
      if($username == 'root') { $username = '';}
      if(!isset($password)) { $password = ''; }
      if(!isset($error_message)) { $error_message = ''; $username='';}
     // if(!isset($_SESSION)) { session_start(); }
      
?>

<!DOCTYPE html>
    <main id="login_form">
      <h2 class="center_heading">Log in to your account</h2>
        <form action="login_controller.php" method="POST">
            <table id="login_table">
		<tr>
                    <th><label>Username:</label></th>
                    <td><input type="text" size="25" name="username" value="<?php {echo $username;} ?>"/></td>
		</tr>
                <tr>  
                    <th><label>Password:</label></th>
                    <td><input type="password" size="25" name="password" value=""/></td>
                </tr>
                <tr>
                    <td><br><input type="hidden" name="action" value="login"></td> <!--is this the right way to do this--> 
                    <td><input type="submit" class="submit" name="login" value="Login" /></td>
		</tr>
                <tr>
                    <td></td>
                    <td><span class="error_message_display"> <?php echo $error_message; ?></span></td>
                </tr>
                <tr>
                    <td></td>
                    <td> <p>Don't have an account? Click <a href="register.php">here</a> to sign up.</p> </td>
                </tr>
            </table>
	</form>
    </main>
<?php include 'footer.php'; ?>
