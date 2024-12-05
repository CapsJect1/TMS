<?php
if (isset($_POST['signin'])) {
    $email = htmlspecialchars(stripslashes(trim($_POST['email'])));
    $password = htmlspecialchars(stripslashes(trim($_POST['password'])));
    $status = 2;
    $maxAttempts = 3; // Maximum allowed login attempts

    // SQL query to fetch user details based on email
    $sql = "SELECT id, FullName, EmailId, fname, lname, Password, Status, login_attempts, last_attempt_time 
            FROM tblusers WHERE EmailId=:email AND Status = :stat";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':stat', $status, PDO::PARAM_INT);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);

    // Check if user exists
    if ($query->rowCount() > 0) {
        // Check if user is allowed to log in
        if ($user['Status'] == 2) {

            // Check if the user has exceeded login attempts
            if ($user['login_attempts'] >= $maxAttempts) {
                // Calculate the time difference between the last attempt and the current time
                $currentTime = new DateTime();
                $lastAttemptTime = new DateTime($user['last_attempt_time']);
                $timeDiff = $currentTime->diff($lastAttemptTime);

                // If less than 24 hours have passed since the last attempt
                if ($timeDiff->h < 24) {
                    echo "<script>
                        Swal.fire({
                            title: 'Error!',
                            text: 'You have exceeded the login attempts. Please try again tomorrow or contact support.',
                            icon: 'error',
                            showConfirmButton: true
                        });
                    </script>";
                    exit;
                } else {
                    // Reset login attempts after 24 hours
                    $resetAttemptsSql = "UPDATE tblusers SET login_attempts = 0 WHERE EmailId = :email";
                    $resetQuery = $dbh->prepare($resetAttemptsSql);
                    $resetQuery->bindParam(':email', $email, PDO::PARAM_STR);
                    $resetQuery->execute();
                }
            }

            // Check if password is correct
            if (password_verify($password, $user['Password'])) {
                // Reset login attempts after successful login
                $resetAttemptsSql = "UPDATE tblusers SET login_attempts = 0 WHERE EmailId = :email";
                $resetQuery = $dbh->prepare($resetAttemptsSql);
                $resetQuery->bindParam(':email', $email, PDO::PARAM_STR);
                $resetQuery->execute();

                // Set session variables upon successful login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['FullName'];
                $_SESSION['login'] = $user['EmailId'];
                $_SESSION['fname'] = $user['fname'];
                $_SESSION['lname'] = $user['lname'];

                // Redirect to a dashboard or home page after successful login
                echo "<script>
                    window.location.href = 'package-list.php';
                </script>";
            } else {
                // Increment login attempts on incorrect password
                $updateAttemptsSql = "UPDATE tblusers SET login_attempts = login_attempts + 1, last_attempt_time = NOW() WHERE EmailId = :email";
                $updateQuery = $dbh->prepare($updateAttemptsSql);
                $updateQuery->bindParam(':email', $email, PDO::PARAM_STR);
                $updateQuery->execute();

                echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Incorrect email or password.',
                        icon: 'error',
                        timer: 1500,
                        showConfirmButton: false
                    });
                </script>";
            }
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Please confirm your account first.',
                    icon: 'error',
                    timer: 1500,
                    showConfirmButton: false
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'No account found with this email.',
                icon: 'error',
                timer: 1500,
                showConfirmButton: false
            });
        </script>";
    }
}
?>

<!-- HTML form for login -->
<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-info">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body modal-spa">
                <div class="login-grids">
                    <div class="login">
                        <div class="login-right">
                            <form method="post" name="login">
                                <h3>Sign in with your account</h3>
                                <input type="text" name="email" id="email" placeholder="Enter your Email" required="">
                                <div style="position: relative;">
                                    <input type="password" name="password" id="password" placeholder="Password" value=""
                                        required="">
                                    <i class="fa fa-eye" id="show-pass2" style="position: absolute; top: 0; right: 0; margin: 35px 10px 0 0;"></i>
                                </div>
                                <h4><a href="forgot-password.php">Forgot password</a></h4>
                                <input type="submit" name="signin" value="SIGN IN">
                            </form>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <p>By logging in you agree to our <a href="page.php?type=terms">Terms and Conditions</a> and <a
                            href="page.php?type=privacy">Privacy Policy</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let showPass2 = document.getElementById('show-pass2');
    showPass2.onclick = () => {
        let passwordInp = document.forms['login']['password'];
        if (passwordInp.getAttribute('type') == 'password') {
            showPass2.classList.replace('fa-eye', 'fa-eye-slash');
            passwordInp.setAttribute('type', 'text');
        } else {
            showPass2.classList.replace('fa-eye-slash', 'fa-eye');
            passwordInp.setAttribute('type', 'password');
        }
    };
</script>
