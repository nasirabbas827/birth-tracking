<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Function to delete a Tehsil by ID
function deleteTehsil($conn, $tehsilID) {
    $sql = "DELETE FROM Tehsils WHERE TehsilID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $tehsilID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Check if TehsilID is provided for deletion
if (isset($_GET['TehsilID'])) {
    $tehsilID = $_GET['TehsilID'];
    deleteTehsil($conn, $tehsilID);
    header("Location: view_tehsils.php");
    exit;
}

// Fetch Tehsils data
$sql = "SELECT t.*, d.DistrictName FROM Tehsils t INNER JOIN Districts d ON t.DistrictID = d.DistrictID";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Tehsils</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2 class="mb-4">View Tehsils</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Tehsil ID</th>
                <th>Tehsil Name</th>
                <th>District</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['TehsilID'] . "</td>";
                    echo "<td>" . $row['TehsilName'] . "</td>";
                    echo "<td>" . $row['DistrictName'] . "</td>";
                    echo "<td>
                            <a href='edit_tehsil.php?TehsilID=" . $row['TehsilID'] . "' class='btn btn-primary btn-sm'>Edit</a>
                            <a href='?TehsilID=" . $row['TehsilID'] . "' class='btn btn-danger btn-sm'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No Tehsils found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="add_tehsil.php" class="btn btn-success">Add Tehsil</a>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
