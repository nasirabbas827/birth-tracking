<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch Union Councils data
$sql = "SELECT u.UnionCouncilID, u.UnionCouncilName, t.TehsilName 
        FROM UnionCouncils u 
        INNER JOIN Tehsils t ON u.TehsilID = t.TehsilID";
$result = mysqli_query($conn, $sql);

// Delete Union Council
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM UnionCouncils WHERE UnionCouncilID = '$delete_id'";
    if (mysqli_query($conn, $delete_sql)) {
        echo "<script>alert('Union Council deleted successfully');</script>";
        header("Refresh:0; url=view_unions.php");
        exit;
    } else {
        echo "<script>alert('Error deleting Union Council');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Union Councils</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2 class="mb-4">View Union Councils</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Union Council ID</th>
                <th>Union Council Name</th>
                <th>Tehsil</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['UnionCouncilID'] . "</td>";
                    echo "<td>" . $row['UnionCouncilName'] . "</td>";
                    echo "<td>" . $row['TehsilName'] . "</td>";
                    echo "<td>
                            <a href='edit_union.php?UnionCouncilID=" . $row['UnionCouncilID'] . "' class='btn btn-primary btn-sm'>Edit</a>
                            <a href='view_unions.php?delete_id=" . $row['UnionCouncilID'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this Union Council?\");'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No Union Councils found.</td></tr>";
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
