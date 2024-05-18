<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Check if DistrictID is provided in the URL
if (!isset($_GET['DistrictID'])) {
    header("Location: view_districts.php");
    exit;
}

// Fetch district details based on DistrictID
$DistrictID = $_GET['DistrictID'];
$sql = "SELECT * FROM districts WHERE DistrictID = '$DistrictID'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// Check if the district exists
if (!$row) {
    $_SESSION['message'] = "District not found.";
    header("Location: view_districts.php");
    exit;
}

// Initialize variables with district details
$DistrictName = $row['DistrictName'];

// Update district details if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newDistrictName = $_POST['DistrictName'];

    // Update district in the database
    $updateQuery = "UPDATE districts SET DistrictName = '$newDistrictName' WHERE DistrictID = '$DistrictID'";
    if (mysqli_query($conn, $updateQuery)) {
        $_SESSION['message'] = "District updated successfully.";
        header("Location: view_districts.php");
        exit;
    } else {
        echo "Error updating district: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit District</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>Edit District</h2>
    <?php
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);
    }
    ?>
    <form method="post">
        <div class="form-group">
            <label for="DistrictName">District Name:</label>
            <input type="text" class="form-control" id="DistrictName" name="DistrictName" value="<?php echo $DistrictName; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
