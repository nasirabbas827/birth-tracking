<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Define variables and initialize with empty values
$tehsilName = $districtID = "";
$tehsilName_err = $districtID_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Tehsil name
    if (empty(trim($_POST["tehsilName"]))) {
        $tehsilName_err = "Please enter Tehsil name.";
    } else {
        $tehsilName = trim($_POST["tehsilName"]);
    }

    // Validate District ID
    if (empty(trim($_POST["districtID"]))) {
        $districtID_err = "Please select a district.";
    } else {
        $districtID = trim($_POST["districtID"]);
    }

    // Check input errors before inserting into database
    if (empty($tehsilName_err) && empty($districtID_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO Tehsils (TehsilName, DistrictID) VALUES (?, ?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_tehsilName, $param_districtID);

            // Set parameters
            $param_tehsilName = $tehsilName;
            $param_districtID = $districtID;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to view Tehsils page
                header("location: view_tehsils.php");
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Tehsil</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>Add Tehsil</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Tehsil Name</label>
            <input type="text" name="tehsilName" class="form-control <?php echo (!empty($tehsilName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $tehsilName; ?>">
            <span class="invalid-feedback"><?php echo $tehsilName_err; ?></span>
        </div>
        <div class="form-group">
            <label>District</label>
            <select name="districtID" class="form-control <?php echo (!empty($districtID_err)) ? 'is-invalid' : ''; ?>">
                <option value="">Select District</option>
                <?php
                $districts_sql = "SELECT * FROM Districts";
                $districts_result = mysqli_query($conn, $districts_sql);
                while ($district = mysqli_fetch_assoc($districts_result)) {
                    echo "<option value='" . $district['DistrictID'] . "'>" . $district['DistrictName'] . "</option>";
                }
                ?>
            </select>
            <span class="invalid-feedback"><?php echo $districtID_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="view_tehsils.php" class="btn btn-secondary ml-2">Cancel</a>
        </div>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
