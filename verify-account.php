<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>
<body>
<?php
session_start();
include ('includes/config.php');


    if (isset($_GET['verification'])) {
        $email = trim($_GET['email']);
        $verification = trim($_GET['verification']);

        $stmt = $dbh->prepare("SELECT * FROM tblusers WHERE EmailId = :email AND Verification = :verification");
        $stmt->execute([':email' => $email, ':verification' => $verification]);
        
        if ($stmt->rowCount() > 0) {
            $status = 2;
            $update = $dbh->prepare("UPDATE tblusers SET Status = :status WHERE EmailId = :email AND Verification = :verification");
            $update->execute([':status' => $status, ':email' => $email, ':verification' => $verification]);
           

            if ($update) {
                echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Account confirmed successfully, You can login now',
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: false
                }).then(function(){
                    window.location.href = 'index.php'
                });
            </script>";
            }

        }else{
            echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Incorrect Verification or Email',
                icon: 'error',
                timer: 1500,
                showConfirmButton: false
            });
        </script>";
        }
    }
    ?>

</body>

</html>