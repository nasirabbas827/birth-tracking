<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Define variables and initialize with empty values
$unionCouncilName = $tehsilID = "";
$unionCouncilName_err = $tehsilID_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Union Council Name
    if (empty(trim($_POST["unionCouncilName"]))) {
        $unionCouncilName_err = "Please enter Union Council Name.";
    } else {
        $unionCouncilName = trim($_POST["unionCouncilName"]);
    }

    // Validate Tehsil ID
    if (empty(trim($_POST["tehsilID"]))) {
        $tehsilID_err = "Please select Tehsil.";
    } else {
        $tehsilID = trim($_POST["tehsilID"]);
    }

    // Check input errors before inserting into database
    if (empty($unionCouncilName_err) && empty($tehsilID_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO UnionCouncils (UnionCouncilName, TehsilID) VALUES (?, ?)";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_unionCouncilName, $param_tehsilID);
            
            // Set parameters
            $param_unionCouncilName = $unionCouncilName;
            $param_tehsilID = $tehsilID;
            
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to view page
                header("location: view_unions.php");
                exit;
            } else {
                echo "Something went wrong. Please try again later.";
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
    <title>Add Union Council</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2 class="mb-4">Add Union Council</h2>
    <form method="post">
        <div class="form-group">
            <label for="unionCouncilName">Union Council Name:</label>
            <input type="text" class="form-control <?php echo (!empty($unionCouncilName_err)) ? 'is-invalid' : ''; ?>" id="unionCouncilName" name="unionCouncilName" value="<?php echo $unionCouncilName; ?>">
            <span class="invalid-feedback"><?php echo $unionCouncilName_err; ?></span>
        </div>
        <div class="form-group">
            <label for="tehsilID">Select Tehsil:</label>
            <select class="form-control <?php echo (!empty($tehsilID_err)) ? 'is-invalid' : ''; ?>" id="tehsilID" name="tehsilID">
                <option value="">Select Tehsil</option>
                <?php
                // Fetch Tehsils from database
                $sql = "SELECT TehsilID, TehsilName FROM Tehsils";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['TehsilID'] . "'>" . $row['TehsilName'] . "</option>";
                }
                ?>
            </select>
            <span class="invalid-feedback"><?php echo $tehsilID_err; ?></span>
        </div>
        <button type="submit" class="btn btn-primary">Add Union Council</button>
        <a href="view_unions.php" class="btn btn-secondary ml-2">View Unions</a>

    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
