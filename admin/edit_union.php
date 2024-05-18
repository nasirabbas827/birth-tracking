<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Initialize variables
$UnionCouncilID = $_GET['UnionCouncilID'] ?? null;
$UnionCouncilName = '';
$TehsilID = '';

// Fetch Union Council details
if ($UnionCouncilID) {
    $sql = "SELECT * FROM UnionCouncils WHERE UnionCouncilID = '$UnionCouncilID'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $UnionCouncilName = $row['UnionCouncilName'];
    $TehsilID = $row['TehsilID'];
}

// Update Union Council
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $UnionCouncilName = $_POST['UnionCouncilName'];
    $TehsilID = $_POST['TehsilID'];

    $update_sql = "UPDATE UnionCouncils SET UnionCouncilName = '$UnionCouncilName', TehsilID = '$TehsilID' WHERE UnionCouncilID = '$UnionCouncilID'";
    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('Union Council updated successfully');</script>";
        header("Refresh:0; url=view_unions.php");
        exit;
    } else {
        echo "<script>alert('Error updating Union Council');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Union Council</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>Edit Union Council</h2>
    <form method="post">
        <div class="form-group">
            <label for="UnionCouncilName">Union Council Name:</label>
            <input type="text" class="form-control" id="UnionCouncilName" name="UnionCouncilName" value="<?php echo $UnionCouncilName; ?>">
        </div>
        <div class="form-group">
            <label for="TehsilID">Tehsil:</label>
            <select class="form-control" id="TehsilID" name="TehsilID">
                <?php
                $sql = "SELECT * FROM Tehsils";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    $selected = ($row['TehsilID'] == $TehsilID) ? 'selected' : '';
                    echo "<option value='" . $row['TehsilID'] . "' $selected>" . $row['TehsilName'] . "</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
