<?php
// define variable for message type
  $msg="";

//Function for input validation and sanitization
 function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
 }
//Define Variables and set empty values
$nameErr = $lastnameErr = $emailErr = $phoneErr = $genderErr= "";
$name = $last_name=$email = $phone = $gender= "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
    $name = test_input($_POST["name"]);
    //Check if name only contains Letters and whitespace
    if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
        $nameErr = "Only letters and white space allowed";
      }

  }
  if (empty($_POST["last_name"])) {
    $lastnameErr = "Last Name is required";
  } else {
    $last_name = test_input($_POST["last_name"]);
    //Check if name only contains Letters and whitespace
    if (!preg_match("/^[a-zA-Z-' ]*$/",$last_name)) {
        $lastnameErr = "Only letters and white space allowed";
      }
  }
  
  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
  } else {
    $email = test_input($_POST["email"]);
    //Check if email is well formatted
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
      }
  }

  if(empty($_POST["phone"])) {
    $phoneErr = "Phone is Required";
  } else {
    $phone = test_input($_POST["phone"]);
    //Check if the maximum number of characters(10) has been entered
    if (!preg_match("/^\d{1,10}$/", $phone)){
        $phoneErr = "Maximum Digits should be 10!";
      }

  }
  
  if (empty($_POST["gender"])) {
    $genderErr = "Gender is required";
  } else {
    $gender = test_input($_POST["gender"]);
  }
  
  //Display Success Message
  if (empty($nameErr) && empty($lastnameErr) && empty($emailErr) && empty($phoneErr) && empty($genderErr)) {
    $msg = "Form submitted successfully.";
  } else{
    $msg= "Form submission failed.";
  }

  //Write form submission data to JSON file
  $submission=["First Name"=>$name, "Last Name"=>$last_name, "Email"=>$email,"Phone"=>$phone, "Genders"=>$gender];
  $submission=json_encode($submission);

  $myfile= fopen("database.json", "w");
  fwrite($myfile, $submission);
  fclose($myfile);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Data Form</title>
</head>
<body>
<h2>Information Form</h2>
<p><?php echo $msg; ?></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

    <input type="text" name="name" placeholder="Enter FirstName" value="<?php echo isset($name) ? $name : ''; ?>" >
    <span class="error">* <?php echo $nameErr;?></span><br><br>

    <input type="text" name="last_name"  placeholder="Enter LastName" value="<?php echo isset($last_name) ? $last_name : ''; ?>">
    <span class="error">* <?php echo $lastnameErr;?></span><br><br>

    <input type="text"  name="other_names" placeholder="Other Name" ><br><br>

    <input type="email"  name="email" placeholder="Enter Email" value="<?php echo isset($email) ? $email : ''; ?>">
    <span class="error">* <?php echo $emailErr;?></span><br><br>

    <input type="tel"  name="phone" placeholder="Enter Phonenumber" value="<?php echo isset($phone) ? $phone : ''; ?>">
    <span class="error">* <?php echo $phoneErr;?></span><br><br>

    <select name="gender" >
        <option value="" disabled selected>Choose Gender..</option>
        <option value="male" <?php echo ($gender == 'male') ? 'selected' : ''; ?>>Male</option>
        <option value="female" <?php echo ($gender == 'female') ? 'selected' : ''; ?>>Female</option>
    </select><span class="error">* <?php echo $genderErr;?></span><br><br>
    
    <input type="submit" value="Submit">
</form>

</body>
</html>
