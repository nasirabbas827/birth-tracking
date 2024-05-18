<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch districts data
$sql = "SELECT * FROM districts";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Districts</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2 class="mb-4">View Districts</h2>
    <?php
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);
    }
    ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>District ID</th>
                <th>District Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['DistrictID'] . "</td>";
                    echo "<td>" . $row['DistrictName'] . "</td>";
                    echo "<td>
                            <a href='edit_district.php?DistrictID=" . $row['DistrictID'] . "' class='btn btn-primary btn-sm'>Edit</a>
                            <a href='view_districts.php?deleteID=" . $row['DistrictID'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this district?\")'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No districts found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="add_district.php" class="btn btn-success">Add District</a>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Delete district if deleteID is set
if (isset($_GET['deleteID'])) {
    $deleteID = $_GET['deleteID'];
    $deleteQuery = "DELETE FROM districts WHERE DistrictID = '$deleteID'";
    if (mysqli_query($conn, $deleteQuery)) {
        $_SESSION['message'] = "District deleted successfully.";
        header("Location: view_districts.php");
        exit;
    } else {
        echo "Error deleting district: " . mysqli_error($conn);
    }
}
?>
