<?php
session_start();

// Initialize the error login session data if it's not set
if (!isset($_SESSION['ERROR_LOGIN'])) {
    $_SESSION['ERROR_LOGIN'] = [];
}

// Check if attempts need to be reset after 24 hours
if (isset($_SESSION['ERROR_LOGIN'][$_POST['email']]) && $_SESSION['ERROR_LOGIN'][$_POST['email']]['date'] < date('Y-m-d')) {
    $_SESSION['ERROR_LOGIN'][$_POST['email']] = ['count' => 0, 'date' => date('Y-m-d')]; // Reset attempts after 24 hours
}

if (isset($_POST['signin'])) {
    $email = htmlspecialchars(stripslashes(trim($_POST['email'])));
    
    // Check if the user has exceeded 3 attempts and if 24 hours haven't passed
    if (isset($_SESSION['ERROR_LOGIN'][$email]) && $_SESSION['ERROR_LOGIN'][$email]['count'] >= 3) {
        $last_attempt = $_SESSION['ERROR_LOGIN'][$email]['date'];
        $current_date = date('Y-m-d');
        
        // If more than 24 hours have passed, reset attempts
        if ($last_attempt !== $current_date) {
            $_SESSION['ERROR_LOGIN'][$email] = ['count' => 0, 'date' => $current_date];
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Login trial expired, please try again later',
                    icon: 'error',
                    timer: 1500,
                    showConfirmButton: false
                });
                </script>";
            echo "<script>window.location.href = 'index.php';</script>";            
            exit;
        }
    }

    // Google reCAPTCHA verification
    $recaptcha_secret = '6LezNpMqAAAAAKA-tks15YZHfdpFeWhQZo2kj-gb'; // Secret key
    $recaptcha_response = $_POST['g-recaptcha-response'];
    
    // Make request to verify reCAPTCHA response
    $recaptcha_verify_url = "https://www.google.com/recaptcha/api/siteverify";
    $recaptcha_verify_response = file_get_contents($recaptcha_verify_url . "?secret=" . $recaptcha_secret . "&response=" . $recaptcha_response);
    $recaptcha_result = json_decode($recaptcha_verify_response);

    if (!$recaptcha_result->success) {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Please complete the reCAPTCHA verification.',
                icon: 'error',
                timer: 1500,
                showConfirmButton: false
            });
            </script>";
        echo "<script>window.location.href = 'index.php';</script>";
        exit;
    }

    // Continue with your existing login logic
    $password = htmlspecialchars(stripslashes(trim($_POST['password'])));
    $status = 2;

    // SQL query to fetch user details based on email and password
    $sql = "SELECT id, FullName, EmailId, fname, lname, Password, Status FROM tblusers WHERE EmailId=:email AND Status = :stat";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':stat', $status, PDO::PARAM_INT);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($query->rowCount() > 0) {
        if ($user['Status'] == 2) {
            // Set session variables upon successful login
            if (password_verify($password, $user['Password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['FullName'];
                $_SESSION['login'] = $user['EmailId'];
                $_SESSION['fname'] = $user['fname'];
                $_SESSION['lname'] = $user['lname'];
                // Redirect to a dashboard or home page after successful login
                echo "<script>window.location.href = 'package-list.php';</script>";
            } else {
                handleFailedLogin($email);
            }
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Please confirm your account first',
                    icon: 'error',
                    timer: 1500,
                    showConfirmButton: false
                });
                </script>";
            handleFailedLogin($email);
        }
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Account does not exist',
                icon: 'error',
                timer: 1500,
                showConfirmButton: false
            });
            </script>";
        handleFailedLogin($email);
    }
}

function handleFailedLogin($email) {
    // Increment failed attempts for this email
    if (!isset($_SESSION['ERROR_LOGIN'][$email])) {
        $_SESSION['ERROR_LOGIN'][$email] = ['count' => 1, 'date' => date('Y-m-d')];
    } else {
        $_SESSION['ERROR_LOGIN'][$email]['count']++;
    }

    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Incorrect email or password',
            icon: 'error',
            timer: 1500,
            showConfirmButton: false
        });
        </script>";
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
}
?>

<!-- Your HTML Form and Scripts as they are -->
