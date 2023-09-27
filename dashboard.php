<?php
$server = "localhost";
$username = "root";
$password = "";
$db = "blood_bank";

$con = mysqli_connect($server, $username, $password, $db);

if (!$con) {
    die("Connection to this database failed due to" . mysqli_connect_error());
}

$sql = "SELECT name, blood_group, quantity FROM blood_info, hospital WHERE hospital.h_id = blood_info.h_id";
$result = mysqli_query($con, $sql);

$blood_samples = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $blood_samples[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Dashboard</title>
    <link rel="stylesheet" href="./styles/dashboard.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat&family=EB+Garamond:wght@400;500&family=Josefin+Slab:wght@400;600&family=Poppins:wght@200;300;400;500&family=Quicksand&family=Raleway:wght@300&family=Roboto:wght@400;500;700&family=Rubik:ital,wght@0,400;0,700;1,500&family=Source+Code+Pro:wght@300;400&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <ul>
                <li class="active">
                    <span class="material-symbols-outlined active-icon">
                        water_drop
                    </span>
                    <a href="#">Available Blood</a>
                </li>
                <li>
                    <span class="material-symbols-outlined">
                        discover_tune
                    </span>
                    <a href="manage.php">Manage Blood Info</a>
                </li>
                <li>
                    <span class="material-symbols-outlined">
                        manage_accounts
                    </span>
                    <a href="request.php">Manage Requests</a>
                </li>
                <li>
                    <span class="material-symbols-outlined">
                        logout
                    </span>
                    <a href="logout.php">Log Out</a>
                </li>
            </ul>
        </div>
        <div class="main-box">
        <h1>Available Blood Samples</h1>
        <div class="available">

        <?php
        if (!empty($blood_samples)) {
            foreach ($blood_samples as $sample) {
                echo '<div class="blood-card">';
                echo '<p><strong>Hospital:</strong> '.$sample['name'].'</p>';
                echo '<p><strong>Blood Group:</strong> '.$sample['blood_group'].'</p>';
                echo '<p><strong>Quantity:</strong> '.$sample['quantity'].'ml</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No blood samples available.</p>';
        }
        ?>
            
        </div>
        </div>
    </div>
</body>
</html>