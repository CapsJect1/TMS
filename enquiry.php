<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (isset($_POST['submit1'])) {
    $fname = $_POST['fname'];
    $email = $_POST['email'];
    $mobile = $_POST['mobileno'];
    $subject = $_POST['subject'];
    $description = $_POST['description'];
    $error = "";

    // Server-side validation
    if (!preg_match("/^[a-zA-Z\s]+$/", $fname)) {
        $error .= "Full name should only contain letters and spaces.<br>";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error .= "Invalid email format.<br>";
    }

    if (!preg_match("/^\d{11}$/", $mobile)) {
        $error .= "Mobile number should be 11 digits only.<br>";
    }

    if (!preg_match("/^[a-zA-Z\s]+$/", $subject)) {
        $error .= "Subject should only contain letters and spaces.<br>";
    }

    if ($error) {
        $msg = "";
    } else {
        $sql = "INSERT INTO tblenquiry (FullName, EmailId, MobileNumber, Subject, Description) 
                VALUES (:fname, :email, :mobile, :subject, :description)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':fname', $fname, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $query->bindParam(':subject', $subject, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();

        if ($lastInsertId) {
            $msg = "Enquiry Successfully submitted";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>TMS | Tourism Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Tourism Management System In PHP" />
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link href="css/font-awesome.css" rel="stylesheet">
    <script src="js/jquery-1.12.0.min.js"></script>
    <script src="js/sweet_alert.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }
        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }
    </style>
</head>
<body>
    <?php include('includes/header.php'); ?>
    <div class="banner-1 ">
        <div class="container">
            <h1>SFP-Management System</h1>
        </div>
    </div>
    <div class="privacy">
        <div class="container">
            <h3>Enquiry Booking</h3>
            <form name="enquiry" method="post">
                <?php if ($error) { ?>
                    <div class="errorWrap"><strong>ERROR</strong>: <?php echo $error; ?></div>
                <?php } elseif ($msg) { ?>
                    <div class="succWrap"><strong>SUCCESS</strong>: <?php echo $msg; ?></div>
                <?php } ?>
                <p style="width: 350px;">
                    <b>Full name</b>
                    <input type="text" name="fname" class="form-control" id="fname"
                           placeholder="Full Name" required="" oninput="validateFullName()">
                    <span id="fname-error" style="color:red;font-size:12px;"></span>
                </p>
                <p style="width: 350px;">
                    <b>Email</b>
                    <input type="email" name="email" class="form-control" id="email"
                           placeholder="Valid Email id" required="" oninput="validateEmail()">
                    <span id="email-error" style="color:red;font-size:12px;"></span>
                </p>
                <p style="width: 350px;">
                    <b>Mobile No</b>
                    <input type="text" name="mobileno" class="form-control" id="mobileno"
                           maxlength="11" minlength="11" placeholder="09000000000" required=""
                           oninput="validateMobileNo()">
                    <span id="mobile-error" style="color:red;font-size:12px;"></span>
                </p>
                <p style="width: 350px;">
                    <b>Subject</b>
                    <input type="text" name="subject" class="form-control" id="subject"
                           placeholder="Subject" required="" oninput="validateSubject()">
                    <span id="subject-error" style="color:red;font-size:12px;"></span>
                </p>
                <p style="width: 350px;">
                    <b>Description</b>
                    <textarea name="description" class="form-control" rows="6" cols="50"
                              id="description" placeholder="Description" required=""></textarea>
                </p>
                <p style="width: 350px;">
                    <button type="submit" name="submit1" class="btn-primary btn">Submit</button>
                </p>
            </form>
        </div>
    </div>
    <?php include('includes/footer.php'); ?>
    <script>
        function validateFullName() {
            const fname = document.getElementById('fname').value;
            const fnameError = document.getElementById('fname-error');
            if (/[^a-zA-Z\s]/.test(fname)) {
                fnameError.textContent = "Full name should contain only letters and spaces.";
                return false;
            } else {
                fnameError.textContent = "";
                return true;
            }
        }

        function validateEmail() {
            const email = document.getElementById('email').value;
            const emailError = document.getElementById('email-error');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                emailError.textContent = "Invalid email format.";
                return false;
            } else {
                emailError.textContent = "";
                return true;
            }
        }

        function validateMobileNo() {
            const mobile = document.getElementById('mobileno').value;
            const mobileError = document.getElementById('mobile-error');
            if (!/^\d{11}$/.test(mobile)) {
                mobileError.textContent = "Mobile number should be 11 digits only.";
                return false;
            } else {
                mobileError.textContent = "";
                return true;
            }
        }

        function validateSubject() {
            const subject = document.getElementById('subject').value;
            const subjectError = document.getElementById('subject-error');
            if (/[^a-zA-Z\s]/.test(subject)) {
                subjectError.textContent = "Subject should contain only letters and spaces.";
                return false;
            } else {
                subjectError.textContent = "";
                return true;
            }
        }

        document.querySelector("form[name='enquiry']").addEventListener("submit", function (e) {
            if (!validateFullName() || !validateEmail() || !validateMobileNo() || !validateSubject()) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
