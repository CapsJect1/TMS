<?php
session_start();
include('includes/config.php');

if (!isset($_SESSION['email'])) {
    // Redirect to forgot-password1.php if email is not set
    header("Location: forgot-password1.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_SESSION['email'];
    $otp = $_POST['otp'];
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if passwords match
    if ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match!";
    } else {
        // Verify OTP and its expiration
        $query = $dbh->prepare("SELECT otp, otp_expiration FROM tblusers WHERE EmailId = :email");
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result && $result['otp'] === $otp && strtotime($result['otp_expiration']) > time()) {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            // Update the password and clear OTP fields
            $update = $dbh->prepare("UPDATE tblusers SET Password = :password, otp = NULL, otp_expiration = NULL WHERE EmailId = :email");
            $update->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $update->bindParam(':email', $email, PDO::PARAM_STR);
            $update->execute();

            $success = "Password has been reset successfully!";
            session_destroy(); // Clear session after successful password reset
        } else {
            $error = "Invalid OTP or OTP has expired.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
        <a href="login.php">Go to Login</a>
    <?php else: ?>
        <form method="POST" action="">
            <label for="otp">Enter OTP:</label>
            <input type="text" id="otp" name="otp" required>

            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Reset Password</button>
        </form>
    <?php endif; ?>
</body>
</html>
