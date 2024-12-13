<?php
session_start();

if (isset($_SESSION['ERROR_LOGIN'])) {
    if ($_SESSION['date'] == date('Y-m-d')) {
        unset($_SESSION['ERROR_LOGIN']);
    }
}

if (isset($_POST['signin'])) {
    // Check if the user is locked out
    if (isset($_SESSION['ERROR_LOGIN']) && $_SESSION['ERROR_LOGIN']['count'] >= 3) {
        echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Login trial expired, please try again later',
            icon: 'error',
            showConfirmButton: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
        });
        </script>";
        echo "<script>window.location.href = 'index.php';</script>";
        exit();
    }

    // Google reCAPTCHA verification
    $recaptcha_secret = '6LezNpMqAAAAAKA-tks15YZHfdpFeWhQZo2kj-gb'; // Secret key
    $recaptcha_response = $_POST['g-recaptcha-response'];
    $recaptcha_verify_url = "https://www.google.com/recaptcha/api/siteverify";
    $recaptcha_verify_response = file_get_contents($recaptcha_verify_url . "?secret=" . $recaptcha_secret . "&response=" . $recaptcha_response);
    $recaptcha_result = json_decode($recaptcha_verify_response);

    if (!$recaptcha_result->success) {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Please complete the reCAPTCHA verification.',
                icon: 'error',
                showConfirmButton: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
            });
            </script>";
        echo "<script>window.location.href = 'index.php';</script>";
        exit();
    }

    // Continue with login logic
    include('includes/config.php');
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $status = 2;

    $sql = "SELECT id, FullName, EmailId, fname, lname, Password, Status, login_attempts, lock_time FROM tblusers WHERE EmailId=:email AND Status = :stat";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':stat', $status, PDO::PARAM_INT);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($query->rowCount() > 0) {
        if ($user['Status'] == 2) {
            if ($user['lock_time'] && strtotime($user['lock_time']) > time()) {
                $time_left = strtotime($user['lock_time']) - time();
                echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Account locked. Try again in " . ceil($time_left / 60) . " minutes.',
                        icon: 'error',
                        showConfirmButton: true,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                    });
                    </script>";
                echo "<script>window.location.href = 'index.php';</script>";
                exit();
            }

            if (password_verify($password, $user['Password'])) {
                // Reset login attempts
                $sql_update = "UPDATE tblusers SET login_attempts = 0, lock_time = NULL WHERE EmailId = :email";
                $update_query = $dbh->prepare($sql_update);
                $update_query->bindParam(':email', $email, PDO::PARAM_STR);
                $update_query->execute();

                // Update or create a unique session ID
                $sessionId = session_id();
                $sql_update_session = "UPDATE tblusers SET session_id = :session_id WHERE id = :id";
                $session_query = $dbh->prepare($sql_update_session);
                $session_query->bindParam(':session_id', $sessionId, PDO::PARAM_STR);
                $session_query->bindParam(':id', $user['id'], PDO::PARAM_INT);
                $session_query->execute();

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['FullName'];
                $_SESSION['login'] = $user['EmailId'];
                $_SESSION['fname'] = $user['fname'];
                $_SESSION['lname'] = $user['lname'];

                echo "<script>window.location.href = 'package-list.php';</script>";
            } else {
                // Increment login attempts
                $sql_update = "UPDATE tblusers SET login_attempts = login_attempts + 1 WHERE EmailId = :email";
                $update_query = $dbh->prepare($sql_update);
                $update_query->bindParam(':email', $email, PDO::PARAM_STR);
                $update_query->execute();

                if ($user['login_attempts'] + 1 >= 3) {
                    $lock_time = date('Y-m-d H:i:s', strtotime('+5 minutes'));
                    $sql_lock = "UPDATE tblusers SET lock_time = :lock_time WHERE EmailId = :email";
                    $lock_query = $dbh->prepare($sql_lock);
                    $lock_query->bindParam(':lock_time', $lock_time, PDO::PARAM_STR);
                    $lock_query->bindParam(':email', $email, PDO::PARAM_STR);
                    $lock_query->execute();

                    echo "<script>
                        Swal.fire({
                            title: 'Error!',
                            text: 'Too many failed attempts. Please try again in 5 minutes.',
                            icon: 'error',
                            showConfirmButton: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                        });
                        </script>";
                } else {
                    echo "<script>
                        Swal.fire({
                            title: 'Error!',
                            text: 'Incorrect email or password',
                            icon: 'error',
                            showConfirmButton: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                        });
                        </script>";
                }
                echo "<script>window.location.href = 'index.php';</script>";
            }
        } else {
            echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Please confirm your account first',
                icon: 'error',
                showConfirmButton: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
            });
            </script>";
            echo "<script>window.location.href = 'index.php';</script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Email not registered',
                icon: 'error',
                showConfirmButton: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
            });
            </script>";
        echo "<script>window.location.href = 'index.php';</script>";
    }
}
?>


<script>
    let showPass2 = document.getElementById('show-pass2');
    showPass2.onclick = () => {
        let passwordInp = document.forms['login']['password'];
        if (passwordInp.getAttribute('type') == 'password') {
            showPass2.classList.replace('fa-eye', 'fa-eye-slash')
            passwordInp.setAttribute('type', 'text')
        } else {
            showPass2.classList.replace('fa-eye-slash', 'fa-eye')
            passwordInp.setAttribute('type', 'password')
        }
    }
</script>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
