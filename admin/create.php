<?php
session_start();
include('includes/config.php');
error_reporting(E_ALL);

// Check if admin is logged in
if (!isset($_SESSION['alogin'])) {
    echo "<script>document.location = 'index.php';</script>";
    exit();
}

// Handle form submission
if (isset($_POST['create'])) {
    $username = htmlspecialchars(trim($_POST['username']));
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $mobile = htmlspecialchars(trim($_POST['mobile']));
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    // Check if the username or email already exists
    $checkSql = "SELECT * FROM admin WHERE UserName = :username OR EmailId = :email";
    $checkQuery = $dbh->prepare($checkSql);
    $checkQuery->bindParam(':username', $username, PDO::PARAM_STR);
    $checkQuery->bindParam(':email', $email, PDO::PARAM_STR);
    $checkQuery->execute();

    if ($checkQuery->rowCount() > 0) {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>
            document.addEventListener("DOMContentLoaded", () => {
                Swal.fire({
                    title: "Error!",
                    text: "Username or Email already exists.",
                    icon: "error",
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        </script>';
    } else {
        // Insert the new admin into the database
        $sql = "INSERT INTO admin (UserName, Name, EmailId, MobileNumber, Password) VALUES (:username, :name, :email, :mobile, :password)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);

        if ($query->execute()) {
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo '<script>
                document.addEventListener("DOMContentLoaded", () => {
                    Swal.fire({
                        title: "Success!",
                        text: "Admin account created successfully.",
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        document.location = "create.php";
                    });
                });
            </script>';
        } else {
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo '<script>
                document.addEventListener("DOMContentLoaded", () => {
                    Swal.fire({
                        title: "Error!",
                        text: "Failed to create admin account.",
                        icon: "error",
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
            </script>';
        }
    }
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Create Admin | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
<div class="container mt-5">
    <h2 class="text-center">Create New Admin</h2>
    <div class="card p-4">
        <form method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" id="username" required>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" name="name" id="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>
            <div class="mb-3">
                <label for="mobile" class="form-label">Mobile Number</label>
                <input type="text" class="form-control" name="mobile" id="mobile" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            <button type="submit" class="btn btn-success" name="create">Create Admin</button>
        </form>
    </div>
</div>
</body>

</html>
