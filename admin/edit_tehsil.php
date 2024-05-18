<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Check if TehsilID is provided
if (!isset($_GET['TehsilID'])) {
    header("Location: view_tehsils.php");
    exit;
}

// Fetch Tehsil data to pre-fill the form
$tehsilID = $_GET['TehsilID'];
$sql = "SELECT * FROM Tehsils WHERE TehsilID = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $tehsilID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

// Check if Tehsil exists
if (!$row) {
    header("Location: view_tehsils.php");
    exit;
}

// Update Tehsil if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tehsilName = $_POST['tehsilName'];

    $sql = "UPDATE Tehsils SET TehsilName = ? WHERE TehsilID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $tehsilName, $tehsilID);
    mysqli_stmt_execute($stmt);

    header("Location: view_tehsils.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tehsil</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2 class="mb-4">Edit Tehsil</h2>
    <form method="post">
        <div class="form-group">
            <label for="tehsilName">Tehsil Name:</label>
            <input type="text" class="form-control" id="tehsilName" name="tehsilName" value="<?php echo $row['TehsilName']; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update Tehsil</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
