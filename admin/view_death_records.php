<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Initialize search parameter
$search_term = isset($_POST['search_term']) ? $_POST['search_term'] : '';

// Fetch death records with joins for names
$query = "SELECT dr.*, u.username, d.DistrictName, t.TehsilName, uc.UnionCouncilName 
          FROM deathrecords dr
          JOIN users u ON dr.UserID = u.id
          JOIN districts d ON dr.DistrictID = d.DistrictId
          JOIN tehsils t ON dr.TehsilID = t.TehsilID
          JOIN unioncouncils uc ON dr.UnionCouncilID = uc.UnionCouncilID
          WHERE d.DistrictName LIKE '%$search_term%'
          OR t.TehsilName LIKE '%$search_term%'
          OR uc.UnionCouncilName LIKE '%$search_term%'";


$records = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Death Records</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Death Records</h2>

    <div class="row mt-4">
        <div class="col-md-12">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="search_term">Search</label>
                    <input type="text" class="form-control" id="search_term" name="search_term" value="<?php echo htmlspecialchars($search_term); ?>" placeholder="Search by District, Tehsil, or Union Council">
                </div>
                <button type="submit" class="btn btn-primary">Generate Report</button>
            </form>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered" id="recordsTable">
                    <thead>
                        <tr>
                            <th>DeathRecordID</th>
                            <th>DeceasedName</th>
                            <th>FatherName</th>
                            <th>FatherNIC</th>
                            <th>DeathDate</th>
                            <th>CauseOfDeath</th>
                            <th>NICNumber</th>
                            <th>PaymentStatus</th> <!-- Added column for Payment Status -->
                            <th>Fee</th> <!-- Added column for Fee -->
                            <th>Username</th>
                            <th>District</th>
                            <th>Tehsil</th>
                            <th>Union Council</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($record = mysqli_fetch_assoc($records)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($record['DeathRecordID']); ?></td>
                                <td><?php echo htmlspecialchars($record['DeceasedName']); ?></td>
                                <td><?php echo htmlspecialchars($record['FatherName']); ?></td>
                                <td><?php echo htmlspecialchars($record['FatherNIC']); ?></td>
                                <td><?php echo htmlspecialchars($record['DeathDate']); ?></td>
                                <td><?php echo htmlspecialchars($record['CauseOfDeath']); ?></td>
                                <td><?php echo htmlspecialchars($record['NICNumber']); ?></td>
                                <td><?php echo htmlspecialchars($record['PaymentStatus']); ?></td> <!-- Display Payment Status -->
                                <td><?php echo '$' . number_format($record['Fee'], 2); ?></td> <!-- Display Fee -->
                                <td><?php echo htmlspecialchars($record['username']); ?></td>
                                <td><?php echo htmlspecialchars($record['DistrictName']); ?></td>
                                <td><?php echo htmlspecialchars($record['TehsilName']); ?></td>
                                <td><?php echo htmlspecialchars($record['UnionCouncilName']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <button id="exportButton" class="btn btn-success float-right m-4">Export to Excel</button>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.2/xlsx.full.min.js"></script>
<script>
document.getElementById('exportButton').addEventListener('click', function() {
    var table = document.getElementById('recordsTable');
    var workbook = XLSX.utils.table_to_book(table, {sheet: "Sheet1"});
    XLSX.writeFile(workbook, 'death_records.xlsx');
});
</script>
</body>
</html>
