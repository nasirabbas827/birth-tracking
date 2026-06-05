<?php
include('config.php');
require_once('stripe-php-master/init.php');

session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

// Get the user ID from the session
$user_id = $_SESSION["id"];

// Fetch the birth record ID from the URL
if (!isset($_GET['birthRecordId']) || empty($_GET['birthRecordId'])) {
    header("location: view_birth_records.php");
    exit;
}

$birthRecordId = intval($_GET['birthRecordId']);

// Fetch the birth record details including the fee
$sql = "SELECT * FROM BirthRecords WHERE BirthRecordID = ? AND UserID = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ii", $birthRecordId, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $record = $result->fetch_assoc();
    $stmt->close();

    // If record does not exist or does not belong to the user, redirect to view_birth_records.php
    if (!$record) {
        header("location: view_birth_records.php");
        exit;
    }
}

// Stripe API keys
$stripe_public_key = 'pk_test_51PQinLRrUKhdzOsDnpHkYJbi0HZIsF9xOVIcPZtsAr4nbH5h1p3o1jblMCPoB0glvFG3o1pbxQZLSiKRHgvuZRMt009qg1bTkq';
$stripe_secret_key = "YOUR_OWN_API_KEY";

\Stripe\Stripe::setApiKey($stripe_secret_key);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>Payment for Birth Record ID: <?php echo htmlspecialchars($birthRecordId); ?></h2>
    <?php
    if (isset($message)) {
        echo '<div class="alert alert-info">' . htmlspecialchars($message) . '</div>';
    }
    ?>
    <form action="charge.php" method="post">
        <input type="hidden" name="birthRecordId" value="<?php echo htmlspecialchars($birthRecordId); ?>">
        <input type="hidden" name="amount" value="<?php echo htmlspecialchars($record['Fee']); ?>">
        <script
            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
            data-key="<?php echo $stripe_public_key; ?>"
            data-amount="<?php echo $record['Fee'] * 100; ?>"
            data-name="Birth Record Payment"
            data-description="Payment for Birth Record ID <?php echo htmlspecialchars($birthRecordId); ?>"
            data-currency="usd"
            data-locale="auto">
        </script>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
