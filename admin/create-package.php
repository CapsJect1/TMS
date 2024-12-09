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
                            <div class="feedback" id="packagenameFeedback"></div>
                        </div>

                        <div class="form-group">
                            <label for="packagetype">Package Type</label>
                            <input type="text" class="form-control my-2" name="packagetype" id="packagetype"
                                placeholder="Package Type (e.g., Family Package / Couple Package)" required>
                            <div class="feedback" id="packagetypeFeedback"></div>
                        </div>

                        <div class="form-group">
                            <label for="packagelocation">Package Location</label>
                            <input type="text" class="form-control my-2" name="packagelocation" id="packagelocation"
                                placeholder="Package Location" required>
                            <div class="feedback" id="packagelocationFeedback"></div>
                        </div>

                        <div class="form-group">
                            <label for="packageprice">Package Price in PHP</label>
                            <input type="text" class="form-control my-2" name="packageprice" id="packageprice"
                                placeholder="Package Price in PHP" required>
                            <div class="feedback" id="packagepriceFeedback"></div>
                        </div>

                        <div class="form-group">
                            <label for="packagefeatures">Package Features</label>
                            <input type="text" class="form-control my-2" name="packagefeatures" id="packagefeatures"
                                placeholder="Package Features (e.g., Free Pickup-Drop Facility)" required>
                            <div class="feedback" id="packagefeaturesFeedback"></div>
                        </div>

                        <div class="form-group">
                            <label for="packagedetails">Package Details</label>
                            <textarea class="form-control my-2" rows="5" cols="50" name="packagedetails" id="packagedetails"
                                placeholder="Package Details" required></textarea>
                            <div class="feedback" id="packagedetailsFeedback"></div>
                        </div>

                        <div class="form-group">
                            <label for="packageimage">Package Image</label>
                            <input type="file" name="packageimage" class="form-control my-2" id="packageimage" required>
                            <div class="feedback" id="packageimageFeedback"></div>
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

    <script>
        // Validate inputs dynamically
        const validationRules = {
            textOnly: /^[a-zA-Z\s]+$/,
            numericOnly: /^\d+$/,
            imageOnly: /\.(jpe?g|png|gif)$/i
        };

        const inputs = {
            packagename: { rule: 'textOnly', feedbackId: 'packagenameFeedback' },
            packagetype: { rule: 'textOnly', feedbackId: 'packagetypeFeedback' },
            packagelocation: { rule: 'textOnly', feedbackId: 'packagelocationFeedback' },
            packageprice: { rule: 'numericOnly', feedbackId: 'packagepriceFeedback' },
            packagefeatures: { rule: 'textOnly', feedbackId: 'packagefeaturesFeedback' },
            packageimage: { rule: 'imageOnly', feedbackId: 'packageimageFeedback', isFile: true }
        };

        Object.keys(inputs).forEach(key => {
            const input = document.getElementById(key);
            const feedbackElement = document.getElementById(inputs[key].feedbackId);

            input.addEventListener('input', function () {
                const value = inputs[key].isFile ? input.files[0]?.name : input.value;

                if (!validationRules[inputs[key].rule].test(value)) {
                    feedbackElement.textContent = "Invalid input!";
                    feedbackElement.style.color = "red";
                } else {
                    feedbackElement.textContent = "Valid input!";
                    feedbackElement.style.color = "green";
                }
            });
        });

        // Prevent form submission if there are invalid inputs
        const form = document.getElementById('packageForm');
        form.addEventListener('submit', function (e) {
            let isValid = true;

            Object.keys(inputs).forEach(key => {
                const input = document.getElementById(key);
                const value = inputs[key].isFile ? input.files[0]?.name : input.value;
                const feedbackElement = document.getElementById(inputs[key].feedbackId);

                if (!validationRules[inputs[key].rule].test(value)) {
                    feedbackElement.textContent = "Invalid input!";
                    feedbackElement.style.color = "red";
                    isValid = false;
                }
            });

            if (!isValid) e.preventDefault();
        });
    </script>

    <?php
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
            echo "<script>alert('Only image files (JPG, PNG, GIF) are allowed.');</script>";
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

        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Package created successfully!',
                showConfirmButton: false,
                timer: 1500
            });
        </script>";
    }
    require 'includes/layout-foot.php';
}
?>
