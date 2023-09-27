<?php
$login = false;
if(isset($_POST['user_id'])){
    
    $server = "localhost";
    $username = "root";
    $password = "";
    $db = "blood_bank";

    $con = mysqli_connect($server, $username, $password, $db);

    if(!$con){
        die("connection to this database failed due to".
        mysqli_connect_error());
    }

    // echo "Success connectiong to the db";

    $user_id = $_POST['user_id'];
    $address = $_POST['address'];
    $password = $_POST['password'];
          
    $sql= "Select * from hospital where name ='$user_id' and password = '$password'";

    $result = mysqli_query($con,$sql);
    $num = mysqli_num_rows($result);
    if($num==1){
        header("location:index.php");
    }
    else {
        $sqlReg = "Insert into hospital (name, address, password) valuse (name ='$user_id', address = '$address' , password = '$password')";
        session_start();
        $_SESSION['hospital_login'] = true;
        $_SESSION['hospital_name'] = $user_id;
        header("location:dashboard.php");
    }

    $con->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation</title>
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat&family=EB+Garamond:wght@400;500&family=Josefin+Slab:wght@400;600&family=Poppins:wght@200;300;400;500&family=Quicksand&family=Raleway:wght@300&family=Roboto:wght@400;500;700&family=Rubik:ital,wght@0,400;0,700;1,500&family=Source+Code+Pro:wght@300;400&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="login-tab">
            <h1 class="title"><span>Blood</span> Donation</h1>
            <?php
            if (isset($_POST['user_id'])) {
                echo '<h3>Username and password not match</h3>';
            }
            ?>
            <form class="form" action="" method="post">
                <input type="text" name="user_id" id="user_id" placeholder="hospital_name" autofocus required>
                <input type="text" name="address" id="address" placeholder="hospital_address" autofocus required>
                <input type="password" name="password" id="password" placeholder="password" autofocus required>
                <button id="gotoreader" class="submit-button">Register    <span class="material-symbols-rounded">arrow_forward_ios</span></button>
                    <!-- Make this button -->
            </form>
            <h4>Already registered? <a href="index.php">Log in</a></h4>
            <!-- <div class="message">
                <p>Are you a member? Log in <span>here</span></p>
            </div> -->
        </div>
    </div>
    <script src="index.js"></script>
    <!-- INSERT INTO `patorns` (`srno`, `name`, `age`, `gender`, `email`, `phone`, `other`, `dt`) VALUES ('1', 'anonymous kumar', '18', 'male', 'annms2004@gmail.com', '8080370344', 'nothing much to say really', current_timestamp()); -->
</body>
</html>