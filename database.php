<?php
// Program 3 
// ***** MODEL *****
// LOCAL Server database stuff
$dsn = 'mysql:host=localhost;dbname=healthtracker';
$username = 'root';
$password = '';
$db_name="healthtracker";

include_once('fusion_php/fusioncharts.php');

//try to connect to database
try {
    $db= new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch (PDOException $e) {
    $error_message = $e->getMessage();
    echo $error_message;
}

//if connected then 
//CHECK IF USERNAME EXSISTS ALREADY 
function check_username_exists($username){
    global $db;
    $exists = false;
        
    $query = 'SELECT COUNT(*) FROM user WHERE Username = :userNamePlaceholder';
    $statement = $db->prepare($query);
    $statement->bindValue(':userNamePlaceholder', $username);
        
    $statement->execute();
    $numRows = $statement->fetchColumn();
        
    if ($numRows == 0){
        $exists = FALSE;
    }
    else{
        $exists = TRUE;
    }     
    return $exists;       
}
//CREATE NEW USER - WORKING!
function create_new_user_account($name, $dob, $gender, $email, $username, $password )
{
    global $db;
    $success = true;
    try{
        $query = 'INSERT INTO user
                   (Name, DOB, Gender, Email, Username, Password)
                   VALUES
                   (:namePlaceholder, :dobPlaceholder, :genderPlaceholder, :emailPlaceholder, :userNamePlaceholder, :passwordPlaceholder)';

        $statement = $db->prepare($query);
        $statement->bindValue(':namePlaceholder', $name);
        $statement->bindValue(':dobPlaceholder', $dob);
        $statement->bindValue(':genderPlaceholder', $gender);
        $statement->bindValue(':emailPlaceholder', $email);
        $statement->bindValue(':userNamePlaceholder', $username); 
        $statement->bindValue(':passwordPlaceholder', $password);
        
        $statement->execute();
       // $idnum = $db->lastInsertId();
        $statement->closeCursor();
        //  return $idnum;
    }
    catch(Exception $e)
    {
        $success = false; 
    }
    
    return $success;
    //after creating new user, insert users weight and height into another table?
}
//GET AN EXISTING USER AND PULL INFO FROM DATABASE
function get_user($userName)
{
    global $db;
    $query = 'SELECT * from user WHERE userName=:userNamePlaceholder';
    //prepare the query, bind the values, then you execute
    $statement = $db->prepare($query);
    $statement->bindValue(':userNamePlaceholder', $userName);

    //the execute method returns a boolean TRUE on success or FALSE on failure.
    $success = $statement->execute();
    //after the statement is executed you can then fetch the results
    $results = $statement->fetchAll();

    if($success){ return $results; }
    else{ return $success; }
}
function insert_bmi_data($username,$date,$weight,$height,$bmi)
{
    global $db;
    $success = true;
    try{
        $query = 'INSERT INTO weight
                   (`Username`, `Date`, `Weight`, `Height`, `BMI`)
                   VALUES
                   (:usernamePlaceholder, :datePlaceholder, :weightPlaceholder, :heightPlaceholder, :bmiPlaceholder)';
        $statement = $db->prepare($query);
        $statement->bindValue(':usernamePlaceholder', $username);
        $statement->bindValue(':datePlaceholder', $date);
        $statement->bindValue(':weightPlaceholder', $weight);
        $statement->bindValue(':heightPlaceholder', $height);
        $statement->bindValue(':bmiPlaceholder', $bmi); 
        
        $statement->execute();
        $statement->closeCursor();
        $success = true;
    }
    catch(Exception $e)
    {
        $success = false; 
    }   
    return $success;
}
function generate_bmi_chart($user_name) 
{
    $dsn = "localhost:3306";
    $db_username = 'root';
    $db_password = '';
    $db_name="healthtracker";
    // Establish a connection to the database
    $db_handle = new mysqli($dsn, $db_username, $db_password, $db_name);

 // Render an error message, to avoid abrupt failure, if the database connection parameters are incorrect
    if ($db_handle->connect_error) {
         exit("There was an error with your connection: ".$db_handle->connect_error);
    }
    // where `username`=?");
    //$stmt->bind_param("s", $username);
    $query = 'SELECT BMI, Date FROM weight WHERE Username=?';
    $statement = $db_handle->prepare($query);
    $statement->bind_param("s",$user_name);

    ////$query_test='SELECT * FROM weight'; //this one returns data but is not correct - returns it for ALL users
    //$statement = $db->prepare($query);
    //$statement->bindValue(':userNamePlaceholder', $userName);
    $statement->execute();
    $result = $statement->get_result();
    //$result = $db_handle->query($query);

    //the execute method returns a boolean TRUE on success or FALSE on failure.
    // $success = $statement->execute();
    //after the statement is executed you can then fetch the results
    //$results = $statement->fetchAll();
    //$result = $db->query($query);

    if($result) // If the query returns a valid response, prepare the JSON string
    {
        // The `$arrData` array holds the chart attributes and data
        $arrData = array(
            "chart" => array(
            "caption" => "Body Mass Index Tracker",
           // "subcaption" => "calculated using your current weight",
            "yaxisname" => "BMI",
            "yaxismaxvalue" => "40",
            "rotatevalues" => "0",
            "placevaluesinside" => "0",
            "bgColor" => "#DDDDDD",
            "bgAlpha" => "50",
            "valuefontcolor" => "074868",
            "plotgradientcolor" => "#8DDB40",
            "showcanvasborder" => "0",
            "numdivlines" => "5",
            "showyaxisvalues" => "1",
            "palettecolors" => "#1F8A70",
            "canvasborderthickness" => "1",
            "canvasbordercolor" => "#1F8A70",
            "canvasborderalpha" => "30",
            "basefontcolor" => "#1F8A70",
            "divlinecolor" => "#1F8A70",
            "divlinealpha" => "10",
            "divlinedashed" => "1",
            //"theme" => "zune",
            "useEllipsesWhenOverflow" => "true"
            ),
        );
        
        // "treadlines" => array( "line" => array(
        //    "startvalue" => "19",
        //    "endvalue" => "26",
        //    "istrendzone" => "",
        //    "valueonright" => "1",
        //    "color" => "1F8A70",
        //   "displayvalue" => "Ideal High",
        //    "showontop" => "0",
        //    "thickness" => "2"    
        //)));
                
        $arrData["data"] = array();
         // Push the data into the array
        while($row = mysqli_fetch_array($result)) {
          array_push($arrData["data"], array(
              "label" => $row["Date"],
              "value" => $row["BMI"]
              )
          );
        }

        /*JSON Encode the data to retrieve the string containing the JSON representation of the data in the array. */

        $jsonEncodedData = json_encode($arrData);

        /*Create an object for the column chart using the FusionCharts PHP class constructor. 
         * Syntax for the constructor is ` FusionCharts("type of chart", "unique chart id", width of the chart, 
         * height of the chart, "div id to render the chart", "data format", "data source")`. 
         * Because we are using JSON data to render the chart, the data format will be `json`.
         *  The variable `$jsonEncodeData` holds all the JSON data for the chart, and will be passed as the 
         * value for the data source parameter of the constructor.*/
        //$db_handle->close();
        $columnChart = new FusionCharts("column2D", "BMI Tracking Chart" , 600, 300, "bmi_chart", "json", $jsonEncodedData);
        //return($columnChart);
        $columnChart->render();  
    }
    else {  }   
}

function insert_sleep_data($username,$date,$hours_filtered)
{
    global $db;
    $success = true;
    try{
        $query = 'INSERT INTO sleep
                   (`Username`, `Date`, `NumHoursSlept`)
                   VALUES
                   (:usernamePlaceholder, :datePlaceholder, :hoursPlaceholder)';
        $statement = $db->prepare($query);
        $statement->bindValue(':usernamePlaceholder', $username);
        $statement->bindValue(':datePlaceholder', $date);
        $statement->bindValue(':hoursPlaceholder', $hours_filtered);
  
        $statement->execute();
        $statement->closeCursor();
        $success = true;
    }
    catch(Exception $e)
    {
        $success = false; 
    } 
    return $success; 
}
function generate_sleep_chart($user_name) 
{
    $dsn = "localhost:3306";
    $db_username = 'root';
    $db_password = '';
    $db_name="healthtracker";
    // Establish a connection to the database - had to use mysqli to get it to work instead of PDO
    $db_handle = new mysqli($dsn, $db_username, $db_password, $db_name);

    // Render an error message, to avoid abrupt failure, if the database connection parameters are incorrect
    if ($db_handle->connect_error) {
         exit("There was an error with your connection: ".$db_handle->connect_error);
    }
    
    $query = 'SELECT NumHoursSlept, Date FROM sleep WHERE Username=?';
    $statement = $db_handle->prepare($query);
    $statement->bind_param("s",$user_name);
    $statement->execute();
    $result = $statement->get_result();
    
    if($result) // If the query returns a valid response, prepare the JSON string
    {
        // The `$arrData` array holds the chart attributes and data
        $arrData = array("chart" => array(
            "caption" => "Hours of Sleep per Night",
            //"subcaption" => "calculated using your current weight",
            "yaxisname" => "Number of Hours",
            "yaxismaxvalue" => "20",
            "rotatevalues" => "0",
            "placevaluesinside" => "0",
            "bgColor" => "#DDDDDD",
            "bgAlpha" => "50",
            "valuefontcolor" => "074868",
            "plotgradientcolor" => "#295e51",
            "showcanvasborder" => "0",
            "numdivlines" => "8",
            "showyaxisvalues" => "0",
            "palettecolors" => "#1F8A70",
            "canvasborderthickness" => "1",
            "canvasbordercolor" => "#1F8A70",
            "canvasborderalpha" => "30",
            "basefontcolor" => "#1F8A70",
            "divlinecolor" => "#1F8A70",
            "divlinealpha" => "10",
            "divlinedashed" => "0",
            //"theme" => "zune",
            "useEllipsesWhenOverflow" => "true"
            )
        );
        
        $arrData["data"] = array();
         // Push the data into the array
        while($row = mysqli_fetch_array($result)) {
          array_push($arrData["data"], array(
              "label" => $row["Date"],
              "value" => $row["NumHoursSlept"]
              )
          );
        }

        /*JSON Encode the data to retrieve the string containing the JSON representation of the data in the array. */

        $jsonEncodedData = json_encode($arrData);
        //$db_handle->close();
        $columnChart = new FusionCharts("column2D", "Sleep Tracking Chart" , 600, 300, "sleep_chart", "json", $jsonEncodedData);
        $columnChart->render();  
    }
    else {  }   
}

function  insert_step_data($username,$date,$weight_filtered,$steps_filtered,$num_cal_burned)
{
    global $db;
    $success = true;
    try{
        $query = 'INSERT INTO steps
                   (`Username`, `Date`, `CurrentWeight`, `NumSteps`, `CaloriesBurned`)
                   VALUES
                   (:usernamePlaceholder, :datePlaceholder, :weightPlaceholder, :stepsPlaceholder, :caloriesPlaceholder)';
        $statement = $db->prepare($query);
        $statement->bindValue(':usernamePlaceholder', $username);
        $statement->bindValue(':datePlaceholder', $date);
        $statement->bindValue(':weightPlaceholder', $weight_filtered);
        $statement->bindValue(':stepsPlaceholder', $steps_filtered);
        $statement->bindValue(':caloriesPlaceholder', $num_cal_burned);
        $statement->execute();
        $statement->closeCursor();
        $success = true;
    }
    catch(Exception $e)
    {
        $success = false; 
    } 
    return $success; 
}

function generate_steps_chart($user_name) 
{
    $dsn = "localhost:3306";
    $db_username = 'root';
    $db_password = '';
    $db_name="healthtracker";
    // Establish a connection to the database - had to use mysqli to get it to work instead of PDO
    $db_handle = new mysqli($dsn, $db_username, $db_password, $db_name);

    // Render an error message, to avoid abrupt failure, if the database connection parameters are incorrect
    if ($db_handle->connect_error) {
         exit("There was an error with your connection: ".$db_handle->connect_error);
    }
    
    $query = 'SELECT  Date, CaloriesBurned FROM steps WHERE Username=?';
    $statement = $db_handle->prepare($query);
    $statement->bind_param("s",$user_name); //"s" denotes binding one string object. 
    $statement->execute();
    $result = $statement->get_result();
    
    if($result) // If the query returns a valid response, prepare the JSON string
    {
        // The `$arrData` array holds the chart attributes and data
        $arrData = array("chart" => array(
            "caption" => "Calories Burned while Walking",
            //"subcaption" => "calculated using your current weight",
            "yaxisname" => "Number of Calories",
            "yaxismaxvalue" => "2000",
            "rotatevalues" => "0",
            "placevaluesinside" => "0",
            "bgColor" => "#DDDDDD",
            "bgAlpha" => "50",
            "valuefontcolor" => "074868",
            "plotgradientcolor" => "#FFE11A",
            "showcanvasborder" => "0",
            "numdivlines" => "20",
            "showyaxisvalues" => "0",
            "palettecolors" => "#fd7400",
            "canvasborderthickness" => "1",
            "canvasbordercolor" => "#fd7400",
            "canvasborderalpha" => "30",
            "basefontcolor" => "#1F8A70",
            "divlinecolor" => "#fd7400",
            "divlinealpha" => "10",
            "divlinedashed" => "0",
            //"theme" => "zune",
            "useEllipsesWhenOverflow" => "true"
            )
        );
        
        $arrData["data"] = array();
         // Push the data into the array
        while($row = mysqli_fetch_array($result)) {
          array_push($arrData["data"], array(
              "label" => $row["Date"],
              "value" => $row["CaloriesBurned"],
              )
          );
        }

        /*JSON Encode the data to retrieve the string containing the JSON representation of the data in the array. */

        $jsonEncodedData = json_encode($arrData);
        //$db_handle->close();
        $columnChart = new FusionCharts("column2D", "Calories Burned while Walking" , 600, 300, "steps_chart", "json", $jsonEncodedData);
        $columnChart->render();  
    }
    else {  }   
}