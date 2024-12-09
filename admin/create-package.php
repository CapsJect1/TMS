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
                    <form class="form-horizontal" name="package" method="post" enctype="multipart/form-data" id="packageForm">
                        <div class="form-group">
                            <label for="packagename">Room or Resort</label>
                            <input type="text" class="form-control my-2" name="packagename" id="packagename"
                                placeholder="Create room or resort" required>
                            <div class="error text-danger" id="packagenameError"></div>
                        </div>

                        <div class="form-group">
                            <label for="packagetype">Package Type</label>
                            <input type="text" class="form-control my-2" name="packagetype" id="packagetype"
                                placeholder="Package Type (e.g., Family Package / Couple Package)" required>
                            <div class="error text-danger" id="packagetypeError"></div>
                        </div>

                        <div class="form-group">
                            <label for="packagelocation">Package Location</label>
                            <input type="text" class="form-control my-2" name="packagelocation" id="packagelocation"
                                placeholder="Package Location" required>
                            <div class="error text-danger" id="packagelocationError"></div>
                        </div>

                        <div class="form-group">
                            <label for="packageprice">Package Price in PHP</label>
                            <input type="text" class="form-control my-2" name="packageprice" id="packageprice"
                                placeholder="Package Price in PHP" required>
                            <div class="error text-danger" id="packagepriceError"></div>
                        </div>

                        <div class="form-group">
                            <label for="packagefeatures">Package Features</label>
                            <input type="text" class="form-control my-2" name="packagefeatures" id="packagefeatures"
                                placeholder="Package Features (e.g., Free Pickup-Drop Facility)" required>
                            <div class="error text-danger" id="packagefeaturesError"></div>
                        </div>

                        <div class="form-group">
                            <label for="packagedetails">Package Details</label>
                            <textarea class="form-control my-2" rows="5" cols="50" name="packagedetails" id="packagedetails"
                                placeholder="Package Details" required></textarea>
                            <div class="error text-danger" id="packagedetailsError"></div>
                        </div>

                        <div class="form-group">
                            <label for="packageimage">Package Image</label>
                            <input type="file" name="packageimage" class="form-control my-2" id="packageimage" required>
                            <div class="error text-danger" id="packageimageError"></div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" name="submit" class="btn btn-primary">Create</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
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

        // Server-side validation for image
        $allowed_extensions = array("jpg", "jpeg", "png", "gif");
        $file_extension = pathinfo($pimage, PATHINFO_EXTENSION);

        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            echo "<script>document.getElementById('packageimageError').innerText = 'Only image files (JPG, PNG, GIF) are allowed.';</script>";
            exit;
        }

        // Validate text fields
        $textFields = [$pname, $ptype, $plocation, $pfeatures];
        foreach ($textFields as $key => $field) {
            if (!preg_match("/^[a-zA-Z\s]+$/", $field)) {
                $fieldErrors = ["packagenameError", "packagetypeError", "packagelocationError", "packagefeaturesError"];
                echo "<script>document.getElementById('{$fieldErrors[$key]}').innerText = 'Only letters and spaces allowed.';</script>";
                exit;
            }
        }

        // Validate package price
        if (!preg_match("/^\d+$/", $pprice)) {
            echo "<script>document.getElementById('packagepriceError').innerText = 'Package Price must be a valid number.';</script>";
            exit;
        }

        move_uploaded_file($_FILES["packageimage"]["tmp_name"], "packageimages/" . $_FILES["packageimage"]["name"]);
        $sql = "INSERT INTO tbltourpackages(PackageName, PackageType, PackageLocation, PackagePrice, PackageFetures, PackageDetails, PackageImage) 
                VALUES (:pname, :ptype, :plocation, :pprice, :pfeatures, :pdetails, :pimage)";
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
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Package added successfully!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                });
            </script>";
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong. Please try again.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                });
            </script>";
        }
    }
    require 'includes/layout-foot.php';
}
?>
