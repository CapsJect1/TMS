<?php
session_start();
include('includes/config.php');

if (!isset($_SESSION['login'])) {
?>
    <script>
        alert("Please login first")
        window.location.href = "index.php"
    </script>
<?php
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require "./phpmailer/src/Exception.php";
require "./phpmailer/src/PHPMailer.php";
require "./phpmailer/src/SMTP.php";


$user_name = clean($_SESSION['user_name']);

$sql = "SELECT id,rate,name FROM ticket_category WHERE isActive = 1";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (isset($_POST['submit'])) {
    $user_id = clean($_SESSION['user_id']);
    $reference_num = uniqid();
    $fname = clean($_POST['fname']);
    $lname = clean($_POST['lname']);
    $email = clean($_POST['email']);
    $ship = clean($_POST['ship']);
    $regular = clean($_POST['regular']);
    $student = clean($_POST['student']);
    $senior_pwd = clean($_POST['senior_pwd']);
    $package_id = clean($_POST['package_id']);
    $arriv_date = clean($_POST['arriv_date']);
    $arriv_time = clean($_POST['arriv_time']);
    $dept_date = clean($_POST['dept_date']);
    $dept_time = clean($_POST['dept_time']);
    $agreement = clean($_POST['agreement']);

    $validated = true;

    if (empty($fname)) {
        $error_fname = "Please fill firstname";
        $validated = false;
    }

    if (empty($lname)) {
        $error_lname = "Please fill lastname";
        $validated = false;
    }

    if (empty($email)) {
        $error_email = "Please fill email";
        $validated = false;
    }else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_email = "Invalid email format";
        $validated = false;
    }

    if (empty($package_id)) {
        $error_package = "Please select package";
        $validated = false;
    }

    if (empty($arriv_date)) {
        $error_arriv_date = "Please select date of arrival";
        $validated = false;
    }

    if (empty($arriv_time)) {
        $error_arriv_time = "Please select time of arrival";
        $validated = false;
    }

    if (empty($dept_date)) {
        $error_dept_date = "Please select date of departure";
        $validated = false;
    }

    if (empty($dept_time)) {
        $error_dept_time = "Please select time of departure";
        $validated = false;
    }
    
    if ($validated) {
        $stmt = $dbh->prepare("INSERT INTO booking(user_id,reference_num,fname,lname,email,ship,regular,student,senior_pwd,package_id,arriv_date,arriv_time,dept_date,dept_time) VALUES(:user_id,:reference_num,:fname,:lname,:email,:ship,:regular,:student,:senior_pwd,:package_id,:arriv_date,:arriv_time,:dept_date,:dept_time)");
        $stmt->execute([':user_id' => $user_id, ':reference_num' => $reference_num,':fname' => $fname,':lname' => $lname,':email' => $email,':ship' => $ship,':regular' => $regular,':student' => $student,':senior_pwd' => $senior_pwd,':package_id' => $package_id,':arriv_date' => $arriv_date,':arriv_time' => $arriv_time,':dept_date' => $dept_date,':dept_time' => $dept_time]);

        if ($stmt) {
            $success = "Booking submitted successfully";

               $mail = new PHPMailer(true);
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'percebuhayan12@gmail.com';
                $mail->Password = 'zdsoiajiywxfhetk';
                $mail->Port = 587;

                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );

                $mail->setFrom('tmssantafe@gmail.com', 'Sta Fe Port Tourist Biological Fee Collection And Staycation Management System');

                $mail->addAddress($email);
                $mail->Subject = "Booking Submitted successfully";
                $mail->Body = "Your appointment date has been set, this is reference number: ". $reference_num;

                $mail->send();

            header('refresh: 3; url=issuetickets.php');
        }

    }

}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>TMS | Package List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="applijewelleryion/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
    <link href="css/font-awesome.css" rel="stylesheet">
    <!-- Custom Theme files -->
    <script src="js/jquery-1.12.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!--animate-->
    <link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
    <script src="js/wow.min.js"></script>
    <script>
        new WOW().init();
    </script>
    <!--//end-animate-->
</head>

<body>
    <?php include('includes/header.php'); ?>
    <!--- banner ---->
    <div class="banner-3">
        <div class="container">
            <h1 class="wow zoomIn animated animated" data-wow-delay=".5s" style="visibility: visible; animation-delay: 0.5s; animation-name: zoomIn;"> Get Ticket To Santa Fe, Bantayan Island</h1>
        </div>
    </div>
    <!--- /banner ---->
    <div class="container">
        <h2 style="margin: 20px 0;">Book Ticket</h2>
        <form method="post">

            <div style="color: green; margin: 20px 0;">
                <?= $success ?? '' ?>
            </div>


            <div class="row" style="margin-bottom: 10px;">
                <div class="col-lg-6">
                    <label for="">First Name <span class="text-danger">*</span> </label>
                    <input type="text" name="fname" class="form-control" style="margin: 10px 0;" value="<?= clean($_SESSION['fname']) ?>" readonly required>
                    <p class="text-danger"><?= $error_fname ?? '' ?></p>
                </div>

                <div class="col-lg-6">
                    <label for="">Last Name <span class="text-danger">*</span></label>
                    <input type="text" name="lname" class="form-control" style="margin: 10px 0;" value="<?= clean($_SESSION['lname']) ?>" readonly required>
                   <p class="text-danger"> <?= $error_lname ?? '' ?></p>
                </div>

                <div class="col-lg-12">
                    <label for="">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" style="margin: 10px 0;" value="<?= clean($_SESSION['login']) ?>" readonly required>
                    <p class="text-danger"><?= $error_email ?? '' ?></p>
                </div>

                <div class="col-lg-12">
                    <label for="" style="margin-bottom: 10px;">Ship Ticket<span class="text-danger">*</span></label>
                </div>

                <div class="col-lg-6" style="margin-bottom: 10px;">
                    <p style="font-weight: bold;"><input type="radio" name="ship" value="Shuttle Ferry" required checked> Shuttle Ferry</p>
                    <p>Regular: 300.00</p>
                    <p>Student: 240.00</p>
                    <p>Senior/PWD: 214</p>
                </div>

                <div class="col-lg-6" style="margin-bottom: 10px;">
                    <p style="font-weight: bold;"><input type="radio" name="ship" value="Island Shipping"> Island Shipping</p>
                    <p>Regular: 300.00</p>
                    <p>Student: 240.00</p>
                    <p>Senior/PWD: 214</p>
                </div>

                <div class="col-lg-6">
                    <label for="" style="font-weight: 100;">Number of Regular </label>
                    <input type="number" name="regular" min="0" class="form-control" value="0" style="margin: 10px 0;" required>
                </div>

                <div class="col-lg-6">
                    <label for="" style="font-weight: 100;">Number of Student</label>
                    <input type="number" name="student" min="0" class="form-control" value="0" style="margin: 10px 0;" required>
                </div>

                <div class="col-lg-12">
                    <label for="" style="font-weight: 100;">Number of Senior / PWD(Person With Disabilty)</label>
                    <input type="number" name="senior_pwd" min="0" class="form-control" value="0" style="margin: 10px 0;" required>
                </div>

                <div class="col-lg-12">
                    <label for="">Select Package <span class="text-danger">*</span></label>
                    <?php
                    $package_sql = "SELECT * from tbltourpackages";
                    $query_package = $dbh->prepare($package_sql);
                    $query_package->execute();
                    $result_package = $query_package->fetchAll(PDO::FETCH_OBJ);
                    //  $cnt = 1;
                    ?>
                    <select name="package_id" id="" class="form-control" style="margin: 10px 0;">
                        <option selected disabled value="">Select Package</option>
                        <?php foreach ($result_package as $package) : ?>
                            <option
                            <?php 
                                $check_pack = $dbh->query("SELECT * FROM booking WHERE package_id = '$package->PackageId' AND status = 'booked' ");
                                if ($check_pack->rowCount() > 0) {
                                    echo "disabled";
                                }
                            ?>
                            value="<?php echo $package->PackageId; ?>"><?php echo htmlentities($package->PackageName); ?> - &#8369 <?= number_format($package->PackagePrice, 2) ?> : <?= $package->PackageFetures ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-danger"> <?= $error_package ?? '' ?></p>
                </div>

                <div class="col-lg-12">
                    <label for="arrival_date" style="margin: 10px 0;">Arrival Date and Time <span class="text-danger">*</span></label>
                </div>

                <div class="col-lg-6">
                    <label for="arriv_date" style="font-weight: 100;">Date</label>
                    <input type="date" min="<?= date("Y-m-d") ?>" id="arriv_date" name="arriv_date" class="form-control" style="margin: 10px 0;" required>
                    <p class="text-danger"> <?= $error_arriv_date ?? '' ?></p>
                </div>

                <div class="col-lg-6">
                    <label for="arriv_time" style="font-weight: 100;">Time</label>
                    <input type="time" min="<?= date("h:i") ?>" id="arriv_time" name="arriv_time" class="form-control" style="margin: 10px 0;" required>
                    <p class="text-danger"> <?= $error_arriv_time ?? '' ?></p>
                </div>

                <div class="col-lg-12">
                    <label for="arrival_date" style="margin: 10px 0;">Departure Date and Time <span class="text-danger">*</span></label>
                </div>

                <div class="col-lg-6">
                    <label for="dept_date" style="font-weight: 100;">Date</label>
                    <input type="date" min="<?= date("Y-m-d") ?>" id="dept_date" name="dept_date" class="form-control" style="margin: 10px 0;" required>
                    <p class="text-danger"> <?= $error_dept_date ?? '' ?></p>
                </div>

                <div class="col-lg-6">
                    <label for="dept_time" style="font-weight: 100;">Time</label>
                    <input type="time" min="<?= date("h:i") ?>" id="dept_time" name="dept_time" class="form-control" style="margin: 10px 0;" required>
                    <p class="text-danger"> <?= $error_dept_time ?? '' ?></p>
                </div>

            </div>

            <div>
                <input type="checkbox" required class="form-check-input" style="margin-bottom: 20px;" name="agreement">
                I agree to the privacy and policy. <a href="http://localhost/santafe/page.php?type=privacy" style="color: blue !important ; text-decoration: underline;" target="_blank">Privacy and Policy</a>
            </div>

            <div>
                <button type="submit" name="submit" class="btn btn-primary ">Book</button>
            </div>
        </form>
        <br>
        <!-- <a href="cart.php" class="btn btn-secondary">Go to Cart</a> -->
    </div>
</body>

</html>