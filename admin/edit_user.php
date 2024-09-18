<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch user details for editing
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$user) {
        echo '<div class="alert alert-danger">User not found.</div>';
        exit;
    }
} else {
    echo '<div class="alert alert-danger">Invalid request.</div>';
    exit;
}

// Handle user update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $age = trim($_POST["age"]);
    $cnic = trim($_POST["cnic"]);
    $password = trim($_POST["password"]);

    $sql_update = "UPDATE users SET username = ?, email = ?, phone = ?, age = ?, cnic = ?";

    if (!empty($password)) {
        $sql_update .= ", password = ?";
    }
    
    $sql_update .= " WHERE id = ?";
    $stmt_update = mysqli_prepare($conn, $sql_update);
    
    if (!empty($password)) {
        mysqli_stmt_bind_param($stmt_update, "ssssssi", $username, $email, $phone, $age, $cnic, $password, $user_id);
        $param_password = password_hash($password, PASSWORD_DEFAULT);
    } else {
        mysqli_stmt_bind_param($stmt_update, "sssssi", $username, $email, $phone, $age, $cnic, $user_id);
    }
    
    if (mysqli_stmt_execute($stmt_update)) {
        echo '<div class="alert alert-success">User updated successfully.</div>';
    } else {
        echo '<div class="alert alert-danger">Update failed. Please try again later.</div>';
    }
    mysqli_stmt_close($stmt_update);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include('admin_navbar.php'); ?>
    
    <div class="container mt-5">
        <h2 class="text-center">Edit User</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . htmlspecialchars($user['id']); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="number" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label>Password (leave blank to keep unchanged)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group">
                <label>Age</label>
                <input type="number" name="age" class="form-control" value="<?php echo htmlspecialchars($user['age']); ?>" required>
            </div>
            <div class="form-group">
                <label>CNIC Number</label>
                <input type="text" name="cnic" class="form-control" value="<?php echo htmlspecialchars($user['cnic']); ?>" required>
            </div>
            <div class="form-group text-center">
                <input type="submit" class="btn btn-primary" value="Update User">
                <a href="view_users.php" class="btn btn-outline-dark">Back to Users</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php mysqli_close($conn); ?>
