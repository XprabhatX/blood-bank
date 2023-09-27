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

    $sqlRequest = "SELECT r.r_id, r.u_id, r.status, u.name
                   FROM request r
                   INNER JOIN user u ON r.u_id = u.u_id
                   WHERE r.h_id = '$hId'";
    $resultRequest = mysqli_query($con, $sqlRequest);

    $requests = [];
    if ($resultRequest && mysqli_num_rows($resultRequest) > 0) {
        while ($rowRequest = mysqli_fetch_assoc($resultRequest)) {
            $requests[] = $rowRequest;
        }
    }
}

if (isset($_POST['updateStatus'])) {
    $rId = $_POST['rId'];
    $status = $_POST['status'];

    $sqlUpdateStatus = "UPDATE request SET status = '$status' WHERE r_id = '$rId'";
    mysqli_query($con, $sqlUpdateStatus);
    
    header("Location: request.php");
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
                    <li>
                        <span class="material-symbols-outlined">
                            discover_tune
                        </span>
                        <a href="manage.php">Manage Blood Info</a>
                    </li>
                    <li class="active">
                        <span class="material-symbols-outlined active-icon">
                            manage_accounts
                        </span>
                        <a href="#">Manage Requests</a>
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
            <h1 style="text-align:center">Manage Requests</h1>

            <div class="manage">
            <h2 class="hospital-name"><span>Hospital Name:</span> <?php echo $hospitalName; ?></h2>

            <div class="update-form">
                <h2 class="update-info">Update Requests Status</h1>
                <form method="post">
                    <input type="text" name="rId" id="requestIdInput" readonly>
                    <input type="text" name="userName" id="userNameInput" readonly>
                    <select name="status">
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                    <button type="submit" name="updateStatus">Update</button>
                </form>
            </div>
            <br><br>
            <h2>click on requests to update it</h2>
            <table>
                <tr>
                    <th>Request ID</th>
                    <th>User Name</th>
                    <th>Status</th>
                </tr>
                <?php
                if (!empty($requests)) {
                    foreach ($requests as $request) {
                        echo '<tr class="request-row" data-rid="' . $request['r_id'] . '">';
                        echo '<td>' . $request['r_id'] . '</td>';
                        echo '<td>' . $request['name'] . '</td>';
                        echo '<td>' . $request['status'] . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="3">No requests available for this hospital.</td></tr>';
                }
                ?>
            </table>
            </div>
            </div>
        </div>

        <script>
            const requestRows = document.querySelectorAll('.request-row');
            const requestIdInput = document.getElementById('requestIdInput');
            const userNameInput = document.getElementById('userNameInput');

            requestRows.forEach(row => {
                row.addEventListener('click', () => {
                    requestIdInput.value = row.getAttribute('data-rid');
                    userNameInput.value = row.cells[1].textContent;
                });
            });
        </script>
    </body>
    </html>