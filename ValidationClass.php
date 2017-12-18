<?php
class ValidationClass {//Validation class for user input fields 
    //property fields
    private $error_message; 
    //constructor
    public function __construct(){  $this->error_message = "";  }
    //getter
    public function getErrorMessage() { return $this->error_message; }
    //methods
    public function isValuePresent($textbox_value, $textbox_title) {//tests if the text box is blank or not
        if($textbox_value == "")
        {
            $this->error_message .= "Please enter a value for "." ".$textbox_title." and submit the form again.";
            return false;
        }
        else { return true; }
    }
    
    public function isValueNumeric($textbox_value, $textbox_title) //test if the value is a number 
    {
        if(preg_match('/[a-z]/', $textbox_value) == 0 || preg_match('/[A-Z]/', $textbox_value) == 0 )
        {
            //testing if this field contains any letters. if not, return true
            return true;
        }
        else 
        {
            //if this field is not actually numeric and does contain letters, return false
            $this->error_message .= "Please make sure ".$textbox_title." field just contains numbers";
            return false;
        }
    }
    
    public function isValidString($textbox_value, $textbox_title) //test if the value contains digits/symbols or not
    {
        if(preg_match('/[a-z][[:alpha:]]/',$textbox_value) === 0)
        {
            $this->error_message .= "Please only include letters for your ".$textbox_title." and submit the form again.";
            return false;
            //|| !preg_match('/[A-Z]/', $textbox_value)
        }
        else { return true; }
    }
    
    public function isWithinRange($textbox_value, $textbox_title, $min, $max)
    {
        if($textbox_value <= $max && $textbox_value >= $min)//value is within correct range
        {
            return true;
        }
        else if($textbox_value > $max)
        {
            $this->error_message .= "The value of ".$textbox_title." MUST be less than ".$max.".";
            return false;
        }
        else if($textbox_value < $min)
        {
            $this->error_message .= "The value of ".$textbox_title." MUST be greater than ".$min.".";
            return false;
        }
        
            
    }
    public function isPasswordFormatAllowed($password)
    {
        //will just test the password field and nothing else
        if(preg_match('/[A-Z]/', $password) === 0)
        {
           $this->error_message .="Please have at least one capital letter in your password.<br>";
           return false;
        }
        else{
            if(preg_match('/[a-z]/', $password) === 0)
            {
               $this->error_message .="Please have at least one lowercase letter in your password.<br>";
               return false;
            }
            else{
                if(preg_match('/\d/', $password) === 0)
                {
                   $this->error_message .="Please have at least 1 digit.<br>";
                   return false;
                }
                else{
                    if(preg_match('/[[:print:]]{10,16}/', $password) === 0)
                    {
                       $this->error_message .="Please have between 10 and 16 character for the password.<br>";
                       return false;
                    }
                    else{
                        if(preg_match('/[[:punct:]]/', $password) === 0)
                        {
                           $this->error_message .="Please have at least 1 special character !@#$%^&*()<br>";
                           return false;
                        }
                        else { return true;}
                    }
                }
            }
        }
    }
    public function isUsernameFormatAllowed($username)
    {
        //will just test the username field and nothing else
        if(preg_match('/[[:punct:]]/', $username) === 1){
            $this->error_message .= "Please only use numbers and letters in your username.<br>";
            return false;
        }
        else{
            return true;
        }
    }
}


