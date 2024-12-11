<?php
session_start();
include('includes/config.php');

if (!isset($_GET['token'])) {
    header("Location: forgot-password2.php");
    exit();
}

$token = $_GET['token'];

// Check if the token exists and is valid
$query = $dbh->prepare("SELECT EmailId, token_expiration FROM tblusers WHERE reset_token = :token");
$query->bindParam(':token', $token, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);

if ($result && strtotime($result['token_expiration']) > time()) {
    // Valid token and not expired
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $result['EmailId'];
        $newPassword = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        // Check if passwords match
        if ($newPassword !== $confirmPassword) {
            $error = "Passwords do not match!";
        } else {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            // Update the password and clear reset token
            $update = $dbh->prepare("UPDATE tblusers SET Password = :password, reset_token = NULL, token_expiration = NULL WHERE EmailId = :email");
            $update->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $update->bindParam(':email', $email, PDO::PARAM_STR);
            $update->execute();

            $success = "Password has been reset successfully!";
        }
    }
} else {
    $error = "Invalid or expired token.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
        }
        .card {
            width: 100%;
            max-width: 400px;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #3AAF08 !important;
            border: none !important;
        }
        .alert {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="card p-4">
        <h2 class="text-center mb-4">Reset Password</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger text-center">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success text-center">
                <?php echo $success; ?>
            </div>
            <div class="text-center">
                <a href="index.php" class="btn btn-primary w-100 mt-2">Go to Login</a>
            </div>
        <?php else: ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        placeholder="Enter new password" 
                        required>
                </div>

                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        class="form-control" 
                        placeholder="Confirm new password" 
                        required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Reset Password</button>
            </form>
        <?php endif; ?>
    </div>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
