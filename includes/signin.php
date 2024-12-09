session_start();

if (isset($_POST['signin'])) {
    // Check if the email has attempted to login today
    $email = htmlspecialchars(stripslashes(trim($_POST['email'])));
    
    // Check if there are login attempts stored in the session
    if (isset($_SESSION['ERROR_LOGIN'])) {
        // If the date of last attempt is not today, reset the count
        if ($_SESSION['ERROR_LOGIN']['date'] !== date('Y-m-d')) {
            $_SESSION['ERROR_LOGIN'] = [
                'count' => 1,
                'date' => date('Y-m-d')
            ];
        } else {
            // If the count exceeds 3 attempts, block the login
            if ($_SESSION['ERROR_LOGIN']['count'] >= 3) {
                echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Login trial expired, please try again later.',
                    icon: 'error',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                });
                </script>";
                echo "<script>window.location.href = 'index.php';</script>";
                exit;
            }
            $_SESSION['ERROR_LOGIN']['count']++;
        }
    } else {
        $_SESSION['ERROR_LOGIN'] = [
            'count' => 1,
            'date' => date('Y-m-d')
        ];
    }

    // Google reCAPTCHA verification
    $recaptcha_secret = '6LezNpMqAAAAAKA-tks15YZHfdpFeWhQZo2kj-gb'; // Secret key
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // Make request to verify reCAPTCHA response
    $recaptcha_verify_url = "https://www.google.com/recaptcha/api/siteverify";
    $recaptcha_verify_response = file_get_contents($recaptcha_verify_url . "?secret=" . $recaptcha_secret . "&response=" . $recaptcha_response);
    $recaptcha_result = json_decode($recaptcha_verify_response);

    // If reCAPTCHA verification failed, show an error
    if (!$recaptcha_result->success) {
        echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Please complete the reCAPTCHA verification.',
            icon: 'error',
            showConfirmButton: true,
            confirmButtonText: 'OK'
        });
        </script>";
        echo "<script>window.location.href = 'index.php';</script>";
        exit;
    }

    // Continue with the login logic
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
                echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Incorrect email or password',
                        icon: 'error',
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    });
                    </script>";
                echo "<script>window.location.href = 'index.php';</script>";
            }
        } else {
            echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Please confirm your account first',
                icon: 'error',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            });
            </script>";
        }
    } else {
        echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Account not found',
            icon: 'error',
            showConfirmButton: true,
            confirmButtonText: 'OK'
        });
        </script>";
    }
}
