<?php
session_start();
require 'db_connection.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $otp = $_POST['otp'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the OTP is valid
    $stmt = $conn->prepare("SELECT * FROM otp_verification WHERE email = ? AND otp = ? AND expiration_time > NOW()");
    $stmt->bind_param("ss", $_SESSION['email'], $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Check if passwords match
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

            // Update the password in the users table
            $stmt = $conn->prepare("UPDATE users SET Password = ? WHERE EmailId = ?");
            $stmt->bind_param("ss", $hashed_password, $_SESSION['email']);
            $stmt->execute();

            // Remove the OTP from otp_verification table
            $stmt = $conn->prepare("DELETE FROM otp_verification WHERE email = ?");
            $stmt->bind_param("s", $_SESSION['email']);
            $stmt->execute();

            echo "<script>alert('Password reset successful!'); window.location.href = 'login.php';</script>";
        } else {
            echo "<script>alert('Passwords do not match!');</script>";
        }
    } else {
        echo "<script>alert('Invalid or expired OTP!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center mb-4">Reset Password</h3>
                        <form action="reset_password.php" method="POST">
                            <div class="mb-3">
                                <label for="otp" class="form-label">OTP</label>
                                <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter the OTP" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter your new password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm your new password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
