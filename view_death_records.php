<?php
session_start();
include('config.php');

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

// Get the user ID from the session
$user_id = $_SESSION["id"];

// Fetch death records for the user from the database
$sql = "SELECT * FROM DeathRecords WHERE UserID = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Death Records</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>View Death Records</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Deceased Name</th>
                <th>Father Name</th>
                <th>Father NIC</th>
                <th>Death Date</th>
                <th>Cause of Death</th>
                <th>NIC Number</th>
                <th>District</th>
                <th>Tehsil</th>
                <th>Union Council</th>
                <th>Fee</th>
                <th>Payment Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                // Fetch district name
                $district_query = "SELECT DistrictName FROM districts WHERE DistrictID = " . $row['DistrictID'];
                $district_result = mysqli_query($conn, $district_query);
                $district_row = mysqli_fetch_assoc($district_result);

                // Fetch tehsil name
                $tehsil_query = "SELECT TehsilName FROM tehsils WHERE TehsilID = " . $row['TehsilID'];
                $tehsil_result = mysqli_query($conn, $tehsil_query);
                $tehsil_row = mysqli_fetch_assoc($tehsil_result);

                // Fetch union council name
                $union_query = "SELECT UnionCouncilName FROM unioncouncils WHERE UnionCouncilID = " . $row['UnionCouncilID'];
                $union_result = mysqli_query($conn, $union_query);
                $union_row = mysqli_fetch_assoc($union_result);

                echo "<tr>";
                echo "<td>" . $row['DeceasedName'] . "</td>";
                echo "<td>" . $row['FatherName'] . "</td>";
                echo "<td>" . $row['FatherNIC'] . "</td>";
                echo "<td>" . $row['DeathDate'] . "</td>";
                echo "<td>" . $row['CauseOfDeath'] . "</td>";
                echo "<td>" . $row['NICNumber'] . "</td>";
                echo "<td>" . $district_row['DistrictName'] . "</td>";
                echo "<td>" . $tehsil_row['TehsilName'] . "</td>";
                echo "<td>" . $union_row['UnionCouncilName'] . "</td>";
                echo "<td>$" . number_format($row['Fee'], 2) . "</td>";
                echo "<td>" . htmlspecialchars($row['PaymentStatus']) . "</td>";
                if ($row['PaymentStatus'] === 'Unpaid') {
                    echo "<td><a href='death_paynow.php?id=" . $row['DeathRecordID'] . "' class='btn btn-success'>Pay Now</a></td>";
                } else {
                    echo "<td><a href='print_death_record.php?id=" . $row['DeathRecordID'] . "' class='btn btn-primary'>Print</a></td>";
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
