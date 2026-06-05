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

// Get the birth record ID from the form submission
if (!isset($_POST['birthRecordId']) || empty($_POST['birthRecordId'])) {
    header("location: view_birth_records.php");
    exit;
}
$birthRecordId = intval($_POST['birthRecordId']);

// Get the amount from the form submission
if (!isset($_POST['amount']) || empty($_POST['amount'])) {
    header("location: view_birth_records.php");
    exit;
}
$amount = $_POST['amount'];

// Stripe API keys
$stripe_secret_key = "YOUR_OWN_API_KEY";

\Stripe\Stripe::setApiKey($stripe_secret_key);

try {
    // Create a charge
    $charge = \Stripe\Charge::create([
        'amount' => $amount * 100, // Convert amount to cents
        'currency' => 'usd',
        'description' => 'Payment for Birth Record ID ' . $birthRecordId,
        'source' => $_POST['stripeToken'],
    ]);

    // Payment successful, update payment status to 'paid'
    $sqlUpdate = "UPDATE BirthRecords SET PaymentStatus = 'Paid' WHERE BirthRecordID = ? AND UserID = ?";
    if ($stmtUpdate = $conn->prepare($sqlUpdate)) {
        $stmtUpdate->bind_param("ii", $birthRecordId, $user_id);
        $stmtUpdate->execute();
        $stmtUpdate->close();
    }

    $message = "Payment successful!";
} catch (Exception $e) {
    $message = "Error: " . $e->getMessage();
}

$conn->close();

// Redirect back to the view_birth_records.php with the message
header("location: view_birthrecord.php?message=" . urlencode($message));
exit;
?>
