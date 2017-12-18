<!-- Program 3 --> 
<!-- VIEW -->
<?php include 'header.php'; 
if(!isset($_SESSION)) { session_start(); }
?>

<!DOCTYPE html>
<main>
  <!--here is where the 3 little circles will go to denote the different areas to enter data --> 
    <div class="circle_links">
        <div class="image_link">
            <a href="enter_stats.php#fitness_section1"><img src="images/activity.png" alt="Go outside!"></a>
            <h3>Track Activity</h3>
        </div>
        <div class="image_link">
            <a href="enter_stats.php#fitness_section2"><img src="images/sleep2.png" alt="Get some rest!"></a>
            <h3>Sleep Better</h3>
        </div>
        <div class="image_link">
            <a href="enter_stats.php#fitness_section3"><img src="images/weight2.png" alt="Don't get fat."></a>
            <h3>Calculate BMI</h3>
        </div>
    </div>
</main>   
<?php include 'footer.php'; ?>
