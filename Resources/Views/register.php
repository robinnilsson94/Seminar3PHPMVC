<?php
require_once '../Database/config.php';

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
           
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
         
            $param_username = trim($_POST["username"]);
            
            
            if(mysqli_stmt_execute($stmt)){
              
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already in use.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } 
            else{
                echo "Sorry, a mistake was made! Please try again later.";
            }
        }
        mysqli_stmt_close($stmt);
    }
    
    
    if(empty(trim($_POST['password']))){
        $password_err = "Please enter a password!";     
    } elseif(strlen(trim($_POST['password'])) < 4){
        $password_err = "Password must have atleast 4 characters.";
    } else{
        $password = trim($_POST['password']);
    }
    

    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = 'Please confirm password.';     
    } else{
        $confirm_password = trim($_POST['confirm_password']);
        if($password != $confirm_password){
            $confirm_password_err = 'Password did not match.';
        }
    }
   
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
     
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
         
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
         
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); 
          
            if(mysqli_stmt_execute($stmt)){
               
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
 
    mysqli_close($link);
}
?>
<?php include '../Fragments/sessionStart.php' ?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="../Css/reset.css">
    <link rel="stylesheet" type="text/css" href="../Css/styles.css">
    <style type="text/css">
        
        .wrapper{ width: 350px; padding: 20px; border-radius: 5px;}
    </style>
</head>
<body>
 <?php include '../Fragments/navigationBar.php' ?>
    <div class="wrapper">
        <h2>Register</h2>
        <?php if($_SESSION['loggedin'] == true) {
  echo '<p class= black>Do you really need a second account?</p>'; 
  }
  else {
    echo '<p class= black>Create your free account now to get started!</p>';
  }
  ?>
        <form action="../../do-register.php" method="post">
            <div class="form-group">
                <label>Username:<sup>*</sup></label>
                <input type="text" name="username"class="form-control">
            </div>    
            <div class="form-group">
                <label>Password:<sup>*</sup></label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group">
                <label>Confirm Password:<sup>*</sup></label>
                <input type="password" name="confirm_password" class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p class= black>Already a member? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>