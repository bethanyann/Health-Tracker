<?php include 'header.php'; 
// Program 3
//put session cookie stuff here
//if(!isset($_SESSION)) { session_start(); }
include_once('database.php');
include_once('fusion_php/fusioncharts.php');
$loggedInStatus=1;
$chart_working = generate_bmi_chart($username); //have to call this method to create chart from page chart will be displayed on.
$chart_working2 = generate_sleep_chart($username);
$chart_working3 = generate_steps_chart($username);
?>
<main>
    <div id="profile_info">
        <h1> User Info </h1>
        <table id="user_info">
            <tr>
                <th>Name: </th>
                <td><?php echo $name ?></td>
            </tr>
            <tr>
                <th>Username: </th>
                <td><?php echo $username ?></td>
            </tr>
            <tr>
                <th>Date of Birth: </th>
                <td><?php echo $dateofbirth ?></td>
            </tr>
            <tr>
                <th>Email: </th>
                <td><?php echo $email ?></td>
            <tr>
                <td colspan="4"><h4>Click on the icons below to add data to your profile.</h4></td>
            </tr>
            </tr>
            <tr id="images_div">
            <!--<td class="image_float_left2"><a href="enter_stats.php#fitness_section3"><img src="images/weight2.png"  height="90px" width="90px"></a></td>
                <td class="image_float_left2"><a href="enter_stats.php#fitness_section2"><img src="images/sleep2.png"  height="90px" width="90px"></a></td>
                <td class="image_float_left2"><a href="enter_stats.php#fitness_section1"><img src="images/activity2.png"  height="90px" width="90px"></a></td>-->
                
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<td></td><td></td>
                <td class="image_float_left2"><a href="enter_stats_controller.php?action=profile_weight"><img src="images/weight2.png" alt="Check BMI" title="Check BMI" height="110px" width="110px"><br></a></td>
                <td class="image_float_left2"><a href="enter_stats_controller.php?action=profile_sleep"><img src="images/sleep2.png" alt="Track Sleep Habits" title="Track Sleep Habits" height="110px" width="110px"></a></td>
                <td class="image_float_left2"><a href="enter_stats_controller.php?action=profile_activity"><img src="images/activity2.png" alt="Count Steps" title="Counts Steps" height="110px" width="110px"></a></td>
            </tr>
        </table>        
    </div>
    <div id="bmi_chart" class="floatright"> <!--this is where the BMI chart will go; change the name of the div? -->
    
    </div>
    <div id="linebar"></div>
    <div id="sleep_chart"> <!--this is where the BMI chart will go; change the name of the div? -->
    
    </div>
    <div id="steps_chart"> <!--this is where the BMI chart will go; change the name of the div? -->
    
    </div>
</main>

<?php include 'footer.php'; ?>.