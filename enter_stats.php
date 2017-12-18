<?php include 'header.php';
if(!isset($_SESSION)) { session_start(); }
$loggedInStatus;
?>

<!--Bethany Jauken -->

<main id="reg_form"><!--individual page stuff goes here --> 
<!--SEC 1--><div id="fitness_section3">
                <h1>Calculate your BMI</h1>
                <div class="image_float_left">
                        <img src="images/weight.png" alt="Calc your BMI" height="150px" width="150px">
                </div>
                <form action="enter_stats_controller.php" method="POST">
                    <table class="stats_table">
                        <tr>
                            <th>Your Weight: </th>
                            <td><input type="text" size="8" name="weight"> </td>
                            <td><p>(in pounds)<p></td>
                        </tr>
                        <tr>
                            <th>Your Height: </th>
                            <td><input type="text" size="4" name="height_feet">  feet </td>
                            <td><input type="text" size="4" name="height_inches">  inches</td>

                        </tr>
                        <tr>
                            <th>Date: </th>
                            <td><input type="date" name="dob"> </td>
                            <td></td><td></td> 
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="submit" class="submit" name="submit" value="Submit" /></td>
                            <td><input type="hidden" name="action" value="calc_bmi"</td>
                        </tr>
                        <span class="error"><?php if(isset($error_message_bmi)){echo $error_message_bmi;} ?></span>
                        
                    </table>
                </form>
            </div><!--END OF SEC 1-->
<!--SEC 2--><div id="fitness_section2">
                <h1>Track your sleeping habits</h1>
                <div class="image_float_left">
                        <img src="images/sleep2.png" alt="Calc your BMI" height="150px" width="150px">
                </div>
                <form action="enter_stats_controller.php" method="POST">
                    <table class="stats_table">
                        <tr>
                            <th>Number of hours slept: </th>
                            <td><input type="text" size="8" name="num_hours"> </td>
                        </tr>
                        <tr>
                            <th>Date: </th>
                            <td><input type="date" name="dob"> </td>

                        </tr>
                        <tr><td></td><td></td> </tr>
                        <tr>
                            <td></td>
                            <td><input type="submit" class="submit" name="submit" value="Submit" /></td>
                            <td><input type="hidden" name="action" value="calc_sleep"></td>
                        </tr>
                        <span class="error"><?php if(isset($error_message_sleep)){echo $error_message_sleep;} ?></span>

                    </table>
                </form>
            </div><!--END OF SEC 2-->
<!--SEC 3--><div id="fitness_section1">
                <h1>Calculate calories burned</h1>
                <div class="image_float_left">
                        <img src="images/activity2.png" alt="Calc your BMI" height="150px" width="150px">
                </div>
                <form action="enter_stats_controller.php" method="POST">
                    <table class="stats_table">
                        <tr>
                            <th>Current Weight: </th>
                            <td><input type="text" size="8" name="weight"> (in pounds)</td>     
                        </tr>
                        <tr>
                            <th>Number of Steps Walked:</th>
                            <td><input type="text" size="4" name="steps"></td>
                        <tr>
                            <th>Date: </th>
                            <td><input type="date" name="dob"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="submit" class="submit" name="submit" value="Submit" /></td>
                            <td><input type="hidden" name="action" value="calc_steps"</td>
                        </tr>
                        <span class="error"><?php if(isset($error_message_steps)){echo $error_message_steps;} ?></span>
                    </table>
                </form>
            </div><!--END OF SEC 3-->
</main>

<?php include 'footer.php'; ?>