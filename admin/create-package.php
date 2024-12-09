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
                        <!-- Other fields -->
                        <div class="form-group">
                            <label for="packageimage">Package Image</label>
                            <input type="file" name="packageimage" class="form-control my-2" id="packageimage" required>
                            <div class="error text-danger" id="packageimageError"></div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" name="submit" class="btn btn-primary" id="submitButton">Create</button>
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
        $imageType = mime_content_type($_FILES["packageimage"]["tmp_name"]);

        // Validate image type
        $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        if (!in_array($imageType, $allowedImageTypes)) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('packageimageError').innerText = 'Invalid file type. Please upload a valid image (JPEG, PNG, GIF).';
                });
            </script>";
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
                        position: 'top-end',
                        icon: 'success',
                        title: 'Package added successfully',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = 'create-package.php';
                    });
                });
            </script>";
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        position: 'top-end',
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

<script>
    document.getElementById('packageForm').addEventListener('input', function (e) {
        const id = e.target.id;
        const value = e.target.value;
        const error = document.getElementById(`${id}Error`);

        if (id === 'packageprice' && !/^\d*$/.test(value)) {
            error.innerText = 'Only numbers are allowed.';
            document.getElementById('submitButton').disabled = true;
        } else if (['packagename', 'packagetype', 'packagelocation', 'packagefeatures'].includes(id) && !/^[a-zA-Z\s]*$/.test(value)) {
            error.innerText = 'Only letters and spaces are allowed.';
            document.getElementById('submitButton').disabled = true;
        } else if (id === 'packageimage') {
            const file = e.target.files[0];
            if (file) {
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                if (!allowedTypes.includes(file.type)) {
                    error.innerText = 'Invalid file type. Please upload a valid image (JPEG, PNG, GIF).';
                    e.target.value = '';
                    document.getElementById('submitButton').disabled = true;
                } else {
                    error.innerText = '';
                    document.getElementById('submitButton').disabled = false;
                }
            }
        } else {
            error.innerText = '';
            document.getElementById('submitButton').disabled = false;
        }
    });
</script>
