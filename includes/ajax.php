<?php
session_start();
include('db_connection.php'); // Include your database connection here

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = htmlspecialchars(stripslashes(trim($_POST['email'])));
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
            // Verify the password
            if (password_verify($password, $user['Password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['FullName'];
                $_SESSION['login'] = $user['EmailId'];
                $_SESSION['fname'] = $user['fname'];
                $_SESSION['lname'] = $user['lname'];

                // Return a success response
                echo json_encode(['status' => 'success', 'redirect' => 'package-list.php']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Incorrect email or password']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Please confirm your account first']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User  not found']);
    }
}
?>