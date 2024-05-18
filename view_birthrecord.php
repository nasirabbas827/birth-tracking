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

// Fetch birth records for the user from the database
$sql = "SELECT * FROM BirthRecords WHERE UserID = ?";
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
    <title>View Birth Records</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>View Birth Records</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Child Name</th>
                <th>Father Name</th>
                <th>Mother Name</th>
                <th>Birth Date</th>
                <th>Gender</th>
                <th>Mother NIC</th>
                <th>Father NIC</th>
                <th>Payment Method</th>
                <th>District</th>
                <th>Tehsil</th>
                <th>Union Council</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['ChildName'] . "</td>";
                echo "<td>" . $row['FatherName'] . "</td>";
                echo "<td>" . $row['MotherName'] . "</td>";
                echo "<td>" . $row['BirthDate'] . "</td>";
                echo "<td>" . $row['Gender'] . "</td>";
                echo "<td>" . $row['MotherNIC'] . "</td>";
                echo "<td>" . $row['FatherNIC'] . "</td>";
                echo "<td>" . $row['PaymentMethod'] . "</td>";
                // Fetch district name
                $district_query = "SELECT DistrictName FROM districts WHERE DistrictID = " . $row['DistrictID'];
                $district_result = mysqli_query($conn, $district_query);
                $district_row = mysqli_fetch_assoc($district_result);
                echo "<td>" . $district_row['DistrictName'] . "</td>";
                // Fetch tehsil name
                $tehsil_query = "SELECT TehsilName FROM tehsils WHERE TehsilID = " . $row['TehsilID'];
                $tehsil_result = mysqli_query($conn, $tehsil_query);
                $tehsil_row = mysqli_fetch_assoc($tehsil_result);
                echo "<td>" . $tehsil_row['TehsilName'] . "</td>";
                // Fetch union council name
                $union_query = "SELECT UnionCouncilName FROM unioncouncils WHERE UnionCouncilID = " . $row['UnionCouncilID'];
                $union_result = mysqli_query($conn, $union_query);
                $union_row = mysqli_fetch_assoc($union_result);
                echo "<td>" . $union_row['UnionCouncilName'] . "</td>";
                // Print button
                echo '<td><a href="generate_pdf.php?birthRecordId=' . $row['BirthRecordID'] . '" class="btn btn-primary">Print</a></td>';
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
