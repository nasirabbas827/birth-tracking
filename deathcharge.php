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

// Get the death record ID from the form submission
if (!isset($_POST['deathRecordId']) || empty($_POST['deathRecordId'])) {
    header("location: view_death_records.php");
    exit;
}
$deathRecordId = intval($_POST['deathRecordId']);

// Get the amount from the form submission
if (!isset($_POST['amount']) || empty($_POST['amount'])) {
    header("location: view_death_records.php");
    exit;
}
$amount = $_POST['amount'];

// Stripe API keys
$stripe_secret_key = 'sk_test_51PQinLRrUKhdzOsDK666N2V91NnsWKtb8mcYyrYwhPgDEheMluMygqAx0MnrgRTWyVwjMvRKsUjpxuyGvFFfuhKE00cD9K5EtD';

\Stripe\Stripe::setApiKey($stripe_secret_key);

try {
    // Create a charge
    $charge = \Stripe\Charge::create([
        'amount' => $amount * 100, // Convert amount to cents
        'currency' => 'usd',
        'description' => 'Payment for Death Record ID ' . $deathRecordId,
        'source' => $_POST['stripeToken'],
    ]);

    // Payment successful, update payment status to 'paid'
    $sqlUpdate = "UPDATE DeathRecords SET PaymentStatus = 'Paid' WHERE DeathRecordID = ? AND UserID = ?";
    if ($stmtUpdate = $conn->prepare($sqlUpdate)) {
        $stmtUpdate->bind_param("ii", $deathRecordId, $user_id);
        $stmtUpdate->execute();
        $stmtUpdate->close();
    }

    $message = "Payment successful!";
} catch (Exception $e) {
    $message = "Error: " . $e->getMessage();
}

$conn->close();

// Redirect back to the view_death_records.php with the message
header("location: view_death_records.php?message=" . urlencode($message));
exit;
?>
