<?php
session_start();
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['token'])) {
    $token = $_GET['token'];

    // Validate token and check expiration
    $query = $dbh->prepare("SELECT id, token_expiration FROM tblusers WHERE reset_token = :token");
    $query->bindParam(':token', $token, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result && strtotime($result['token_expiration']) > time()) {
        $_SESSION['user_id'] = $result['id']; // Save user ID for password update
    } else {
        die("Invalid or expired token.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match!";
    } else {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Update the password and clear reset token
        $update = $dbh->prepare("UPDATE tblusers SET Password = :password, reset_token = NULL, token_expiration = NULL WHERE id = :id");
        $update->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $update->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
        $update->execute();

        $success = "Password has been reset successfully!";
        session_destroy(); // Clear session after successful password reset
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card p-4 mx-auto" style="max-width: 400px;">
            <h2 class="text-center">Reset Password</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
                <div class="text-center">
                    <a href="login.php" class="btn btn-primary">Go to Login</a>
                </div>
            <?php else: ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
