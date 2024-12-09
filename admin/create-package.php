<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    require 'includes/layout-head.php';
?>
    <div class="grid-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>Create Package</h4>
                    <hr>
                    <form class="form-horizontal" name="package" method="post" enctype="multipart/form-data">
                        <label for="packagename" class="col-sm-2 control-label">Room or Resort</label>
                        <input type="text" class="form-control my-2" name="packagename" id="packagename" placeholder="Create room or resort" required>

                        <label for="packagetype" class="col-sm-2 control-label">Package Type</label>
                        <input type="text" class="form-control my-2" name="packagetype" id="packagetype" placeholder="Package Type (e.g., Family Package / Couple Package)" required>

                        <label for="packagelocation" class="col-sm-2 control-label">Package Location</label>
                        <input type="text" class="form-control my-2" name="packagelocation" id="packagelocation" placeholder="Package Location" required>

                        <label for="packageprice" class="col-sm-2 control-label">Package Price in PHP</label>
                        <input type="text" class="form-control my-2" name="packageprice" id="packageprice" placeholder="Package Price in PHP" required>

                        <label for="packagefeatures" class="col-sm-2 control-label">Package Features</label>
                        <input type="text" class="form-control my-2" name="packagefeatures" id="packagefeatures" placeholder="Package Features (e.g., Free Pickup-drop facility)" required>

                        <label for="packagedetails" class="col-sm-2 control-label">Package Details</label>
                        <textarea class="form-control my-2" rows="5" cols="50" name="packagedetails" id="packagedetails" placeholder="Package Details" required></textarea>

                        <label for="packageimage" class="col-sm-2 control-label">Package Image</label>
                        <input type="file" name="packageimage" class="form-control my-2" id="packageimage" required>

                        <div class="mt-3">
                            <button type="submit" name="submit" class="btn btn-primary">Create</button>
                            <button type="reset" class="btn-secondary btn">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
    require 'includes/footer.php';
    if (isset($_POST['submit'])) {
        $pname = $_POST['packagename'];
        $ptype = $_POST['packagetype'];
        $plocation = $_POST['packagelocation'];
        $pprice = $_POST['packageprice'];
        $pfeatures = $_POST['packagefeatures'];
        $pdetails = $_POST['packagedetails'];
        $pimage = $_FILES["packageimage"]["name"];

        // Validation flags
        $isValid = true;
        $errorMessages = [];

        // Validate text fields
        if (!preg_match("/^[a-zA-Z\s]+$/", $pname)) {
            $isValid = false;
            $errorMessages[] = "Package name should contain text only.";
        }
        if (!preg_match("/^[a-zA-Z\s]+$/", $ptype)) {
            $isValid = false;
            $errorMessages[] = "Package type should contain text only.";
        }
        if (!preg_match("/^[a-zA-Z\s]+$/", $plocation)) {
            $isValid = false;
            $errorMessages[] = "Package location should contain text only.";
        }
        if (!preg_match("/^\d+$/", $pprice)) {
            $isValid = false;
            $errorMessages[] = "Package price should contain numbers only.";
        }

        // If validation fails
        if (!$isValid) {
            echo '<div class="alert alert-danger">';
            foreach ($errorMessages as $error) {
                echo "<p>$error</p>";
            }
            echo '</div>';
        } else {
            // Move uploaded file
            move_uploaded_file($_FILES["packageimage"]["tmp_name"], "packageimages/" . $pimage);

            // Insert into the database
            $sql = "INSERT INTO tbltourpackages(PackageName, PackageType, PackageLocation, PackagePrice, PackageFetures, PackageDetails, PackageImage) 
                    VALUES(:pname, :ptype, :plocation, :pprice, :pfeatures, :pdetails, :pimage)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':pname', $pname, PDO::PARAM_STR);
            $query->bindParam(':ptype', $ptype, PDO::PARAM_STR);
            $query->bindParam(':plocation', $plocation, PDO::PARAM_STR);
            $query->bindParam(':pprice', $pprice, PDO::PARAM_STR);
            $query->bindParam(':pfeatures', $pfeatures, PDO::PARAM_STR);
            $query->bindParam(':pdetails', $pdetails, PDO::PARAM_STR);
            $query->bindParam(':pimage', $pimage, PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();
            if ($lastInsertId) {
                echo '<script>
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Package added successfully",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = "create-package.php";
                    });
                </script>';
            } else {
                echo '<script>
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: "Something went wrong. Please try again",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = "create-package.php";
                    });
                </script>';
            }
        }
    }
    require 'includes/layout-foot.php';
}
?>
