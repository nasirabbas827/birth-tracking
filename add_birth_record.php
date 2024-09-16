<?php
include('config.php');

session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

// Retrieve user ID from session
$user_id = $_SESSION["id"];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $child_name = $_POST['child_name'];
    $father_name = $_POST['father_name'];
    $mother_name = $_POST['mother_name'];
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];
    $mother_nic = $_POST['mother_nic'];
    $father_nic = $_POST['father_nic'];
    $payment_method = 'Online'; // Hardcoded value for payment method
    $payment_status = 'Unpaid'; // Default value for payment status
    $fee = 500; // Hardcoded fee value
    $district_id = $_POST['district_id'];
    $tehsil_id = $_POST['tehsil_id'];
    $union_council_id = $_POST['union_council_id'];

    // Insert data into BirthRecords table
    $sql = "INSERT INTO BirthRecords (ChildName, FatherName, MotherName, BirthDate, Gender, MotherNIC, FatherNIC, PaymentMethod, PaymentStatus, Fee, DistrictID, TehsilID, UnionCouncilID, UserID) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssssiiiis", $child_name, $father_name, $mother_name, $birth_date, $gender, $mother_nic, $father_nic, $payment_method, $payment_status, $fee, $district_id, $tehsil_id, $union_council_id, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    // Redirect to dashboard or any other page after insertion
    header("Location: view_birthrecord.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Birth Record</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>Add Birth Record</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Child Name</label>
                    <input type="text" name="child_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Father Name</label>
                    <input type="text" name="father_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Mother Name</label>
                    <input type="text" name="mother_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Birth Date</label>
                    <input type="date" name="birth_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" class="form-control" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Mother NIC Number</label>
                    <input type="text" name="mother_nic" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Father NIC Number</label>
                    <input type="text" name="father_nic" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Payment Method</label>
                    <input type="text" name="payment_method" class="form-control" value="Online" disabled>
                </div>
                <div class="form-group">
                    <label>Payment Status</label>
                    <input type="text" name="payment_status" class="form-control" value="Unpaid" disabled>
                </div>
                <div class="form-group">
                    <label>Fee</label>
                    <input type="text" name="fee" class="form-control" value="$500" disabled>
                </div>
                <div class="form-group">
                    <label>District</label>
                    <select name="district_id" class="form-control" required>
                        <?php
                        // Fetch districts from the database
                        $district_query = "SELECT DistrictID, DistrictName FROM districts";
                        $district_result = mysqli_query($conn, $district_query);
                        while ($district_row = mysqli_fetch_assoc($district_result)) {
                            echo "<option value='" . $district_row['DistrictID'] . "'>" . $district_row['DistrictName'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tehsil</label>
                    <select name="tehsil_id" class="form-control" required>
                        <?php
                        // Fetch tehsils from the database
                        $tehsil_query = "SELECT TehsilID, TehsilName FROM tehsils";
                        $tehsil_result = mysqli_query($conn, $tehsil_query);
                        while ($tehsil_row = mysqli_fetch_assoc($tehsil_result)) {
                            echo "<option value='" . $tehsil_row['TehsilID'] . "'>" . $tehsil_row['TehsilName'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Union Council</label>
                    <select name="union_council_id" class="form-control" required>
                        <?php
                        // Fetch union councils from the database
                        $union_query = "SELECT UnionCouncilID, UnionCouncilName FROM unioncouncils";
                        $union_result = mysqli_query($conn, $union_query);
                        while ($union_row = mysqli_fetch_assoc($union_result)) {
                            echo "<option value='" . $union_row['UnionCouncilID'] . "'>" . $union_row['UnionCouncilName'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
