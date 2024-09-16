<?php
session_start();
include('config.php');

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

$user_id = $_SESSION["id"];
$deceased_name = $father_name = $father_nic = $death_date = $cause_of_death = $nic_number = "";
$district_id = $tehsil_id = $union_council_id = "";
$deceased_name_err = $father_name_err = $father_nic_err = $death_date_err = $cause_of_death_err = $nic_number_err = "";
$district_id_err = $tehsil_id_err = $union_council_id_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate deceased name
    if (empty(trim($_POST["deceased_name"]))) {
        $deceased_name_err = "Please enter the name of the deceased.";
    } else {
        $deceased_name = trim($_POST["deceased_name"]);
    }

    // Validate father name
    if (empty(trim($_POST["father_name"]))) {
        $father_name_err = "Please enter the father's name.";
    } else {
        $father_name = trim($_POST["father_name"]);
    }

    // Validate father NIC
    if (empty(trim($_POST["father_nic"]))) {
        $father_nic_err = "Please enter the father's NIC.";
    } elseif (!preg_match('/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/', trim($_POST["father_nic"]))) {
        $father_nic_err = "Invalid NIC format.";
    } else {
        $father_nic = trim($_POST["father_nic"]);
    }

    // Validate death date
    if (empty(trim($_POST["death_date"]))) {
        $death_date_err = "Please enter the date of death.";
    } else {
        $death_date = trim($_POST["death_date"]);
    }

    // Validate cause of death
    if (empty(trim($_POST["cause_of_death"]))) {
        $cause_of_death_err = "Please enter the cause of death.";
    } else {
        $cause_of_death = trim($_POST["cause_of_death"]);
    }

    // Validate NIC number
    if (empty(trim($_POST["nic_number"]))) {
        $nic_number_err = "Please enter the NIC number.";
    } elseif (!preg_match('/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/', trim($_POST["nic_number"]))) {
        $nic_number_err = "Invalid NIC format.";
    } else {
        $nic_number = trim($_POST["nic_number"]);
    }

    // Validate district ID
    if (empty(trim($_POST["district_id"]))) {
        $district_id_err = "Please select the district.";
    } else {
        $district_id = trim($_POST["district_id"]);
    }

    // Validate tehsil ID
    if (empty(trim($_POST["tehsil_id"]))) {
        $tehsil_id_err = "Please select the tehsil.";
    } else {
        $tehsil_id = trim($_POST["tehsil_id"]);
    }

    // Validate union council ID
    if (empty(trim($_POST["union_council_id"]))) {
        $union_council_id_err = "Please select the union council.";
    } else {
        $union_council_id = trim($_POST["union_council_id"]);
    }

    // Check input errors before inserting in database
    if (empty($deceased_name_err) && empty($father_name_err) && empty($father_nic_err) && empty($death_date_err) && empty($cause_of_death_err) && empty($nic_number_err) && empty($district_id_err) && empty($tehsil_id_err) && empty($union_council_id_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO DeathRecords (DeceasedName, FatherName, FatherNIC, DeathDate, CauseOfDeath, NICNumber, DistrictID, TehsilID, UnionCouncilID, UserID, PaymentStatus, Fee) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Unpaid', 500)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssiiii", $param_deceased_name, $param_father_name, $param_father_nic, $param_death_date, $param_cause_of_death, $param_nic_number, $param_district_id, $param_tehsil_id, $param_union_council_id, $param_user_id);

            // Set parameters
            $param_deceased_name = $deceased_name;
            $param_father_name = $father_name;
            $param_father_nic = $father_nic;
            $param_death_date = $death_date;
            $param_cause_of_death = $cause_of_death;
            $param_nic_number = $nic_number;
            $param_district_id = $district_id;
            $param_tehsil_id = $tehsil_id;
            $param_union_council_id = $union_council_id;
            $param_user_id = $user_id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to the death records page
                header("location: view_death_records.php");
                exit;
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
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
    <title>Add Death Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>Add Death Record</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Deceased Name</label>
                <input type="text" name="deceased_name" class="form-control <?php echo (!empty($deceased_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $deceased_name; ?>" required>
                <span class="invalid-feedback"><?php echo $deceased_name_err; ?></span>
            </div>
            <div class="form-group">
                <label>Father Name</label>
                <input type="text" name="father_name" class="form-control <?php echo (!empty($father_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $father_name; ?>" required>
                <span class="invalid-feedback"><?php echo $father_name_err; ?></span>
            </div>
            <div class="form-group">
                <label>Father NIC</label>
                <input type="text" name="father_nic" class="form-control <?php echo (!empty($father_nic_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $father_nic; ?>" required>
                <span class="invalid-feedback"><?php echo $father_nic_err; ?></span>
            </div>
            <div class="form-group">
                <label>Death Date</label>
                <input type="date" name="death_date" class="form-control <?php echo (!empty($death_date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $death_date; ?>" required>
                <span class="invalid-feedback"><?php echo $death_date_err; ?></span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Cause of Death</label>
                <textarea name="cause_of_death" class="form-control <?php echo (!empty($cause_of_death_err)) ? 'is-invalid' : ''; ?>" required><?php echo $cause_of_death; ?></textarea>
                <span class="invalid-feedback"><?php echo $cause_of_death_err; ?></span>
            </div>
            <div class="form-group">
                <label>NIC Number</label>
                <input type="text" name="nic_number" class="form-control <?php echo (!empty($nic_number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nic_number; ?>" required>
                <span class="invalid-feedback"><?php echo $nic_number_err; ?></span>
            </div>
            <div class="form-group">
                <label>District</label>
                <select name="district_id" class="form-control <?php echo (!empty($district_id_err)) ? 'is-invalid' : ''; ?>" required>
                    <option value="">Select District</option>
                    <?php
                    // Fetch districts
                    $sql = "SELECT * FROM districts";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['DistrictID'] . "'>" . $row['DistrictName'] . "</option>";
                    }
                    ?>
                </select>
                <span class="invalid-feedback"><?php echo $district_id_err; ?></span>
            </div>
            <div class="form-group">
                <label>Tehsil</label>
                <select name="tehsil_id" class="form-control <?php echo (!empty($tehsil_id_err)) ? 'is-invalid' : ''; ?>" required>
                    <option value="">Select Tehsil</option>
                    <?php
                    // Fetch tehsils
                    $sql = "SELECT * FROM tehsils";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['TehsilID'] . "'>" . $row['TehsilName'] . "</option>";
                    }
                    ?>
                </select>
                <span class="invalid-feedback"><?php echo $tehsil_id_err; ?></span>
            </div>
            <div class="form-group">
                <label>Union Council</label>
                <select name="union_council_id" class="form-control <?php echo (!empty($union_council_id_err)) ? 'is-invalid' : ''; ?>" required>
                    <option value="">Select Union Council</option>
                    <?php
                    // Fetch union councils
                    $sql = "SELECT * FROM unioncouncils";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['UnionCouncilID'] . "'>" . $row['UnionCouncilName'] . "</option>";
                    }
                    ?>
                </select>
                <span class="invalid-feedback"><?php echo $union_council_id_err; ?></span>
            </div>
            <div class="form-group">
                <label>Fee</label>
                <input type="text" class="form-control" value="500" readonly>
            </div>
        </div>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Submit">
    </div>
</form>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
