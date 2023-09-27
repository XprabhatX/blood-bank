<?php
session_start();

if (!isset($_SESSION['hospital_name'])) {
    header("Location: index.php");
    exit;
}

$server = "localhost";
$username = "root";
$password = "";
$db = "blood_bank";

$con = mysqli_connect($server, $username, $password, $db);

if (!$con) {
    die("Connection to this database failed due to" . mysqli_connect_error());
}

$hospitalName = $_SESSION['hospital_name'];

$sqlHospital = "SELECT h_id FROM hospital WHERE name = '$hospitalName'";
$resultHospital = mysqli_query($con, $sqlHospital);

if ($resultHospital && mysqli_num_rows($resultHospital) > 0) {
    $rowHospital = mysqli_fetch_assoc($resultHospital);
    $hId = $rowHospital['h_id'];

    // Query the blood_info table using the obtained h_id
    $sqlBloodInfo = "SELECT * FROM blood_info WHERE h_id = '$hId'";
    $resultBloodInfo = mysqli_query($con, $sqlBloodInfo);

    $bloodSamples = [];
    if ($resultBloodInfo && mysqli_num_rows($resultBloodInfo) > 0) {
        while ($rowBloodInfo = mysqli_fetch_assoc($resultBloodInfo)) {
            $bloodSamples[] = $rowBloodInfo;
        }
    }
}

if (isset($_POST['updateBlood'])) {
    $bloodGroup = $_POST['bloodGroup'];
    $quantity = $_POST['quantity'];

    // Check if a row with the same h_id and blood_group exists
    $sqlCheckExistence = "SELECT * FROM blood_info WHERE h_id = '$hId' AND blood_group = '$bloodGroup'";
    $resultCheckExistence = mysqli_query($con, $sqlCheckExistence);

    if ($resultCheckExistence && mysqli_num_rows($resultCheckExistence) > 0) {
        // Row exists, update the quantity
        $sqlUpdate = "UPDATE blood_info SET quantity = quantity + $quantity WHERE h_id = '$hId' AND blood_group = '$bloodGroup'";
        mysqli_query($con, $sqlUpdate);
    } else {
        // Row does not exist, insert a new row
        $sqlInsert = "INSERT INTO blood_info (h_id, blood_group, quantity) VALUES ('$hId', '$bloodGroup', '$quantity')";
        mysqli_query($con, $sqlInsert);
    }

    // Redirect to the same page to refresh the data after the update/insert
    header("Location: manage.php");
    exit;

}

mysqli_close($con);
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manage Blood Info</title>
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
                    <li>
                        <span class="material-symbols-outlined">
                            water_drop
                        </span>
                        <a href="dashboard.php">Available Blood</a>
                    </li>
                    <li class="active">
                        <span class="material-symbols-outlined active-icon">
                            discover_tune
                        </span>
                        <a href="#">Manage Blood Info</a>
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
            <h1 style="text-align:center">Manage Blood info</h1>

            <div class="manage">
            <h2 class="hospital-name"><span>Hospital Name:</span> <?php echo $hospitalName; ?></h2>

            <div class="update-form">
                <h2 class="update-info">Update Blood Information</h1>
                <form method="post">
                    <input type="text" name="hId" value="<?php echo $hId; ?>" readonly>
                    <select name="bloodGroup">
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                        <option value="O">O</option>
                    </select>
                    <input type="number" name="quantity" placeholder="Quantity (ml)" required>
                    <button type="submit" name="updateBlood">Update</button>
                </form>
            </div>
            <?php
            if (!empty($bloodSamples)) {
                echo '<table>';
                echo '<tr><th>Blood Group</th><th>Quantity (ml)</th></tr>';
                foreach ($bloodSamples as $sample) {
                    echo '<tr>';
                    echo '<td>' . $sample['blood_group'] . '</td>';
                    echo '<td>' . $sample['quantity'] . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo '<p>No blood samples available for this hospital.</p>';
            }
            ?>
            </div>
            </div>
        </div>
    </body>
    </html>