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
                        <!-- Room or Resort -->
                        <div class="form-group">
                            <label for="packagename">Room or Resort</label>
                            <input type="text" class="form-control my-2" name="packagename" id="packagename"
                                placeholder="Create room or resort" required>
                            <div class="error text-danger" id="packagenameError"></div>
                        </div>

                        <!-- Package Type -->
                        <div class="form-group">
                            <label for="packagetype">Package Type</label>
                            <input type="text" class="form-control my-2" name="packagetype" id="packagetype"
                                placeholder="Package Type (e.g., Family Package / Couple Package)" required>
                            <div class="error text-danger" id="packagetypeError"></div>
                        </div>

                        <!-- Package Location -->
                        <div class="form-group">
                            <label for="packagelocation">Package Location</label>
                            <input type="text" class="form-control my-2" name="packagelocation" id="packagelocation"
                                placeholder="Package Location" required>
                            <div class="error text-danger" id="packagelocationError"></div>
                        </div>

                        <!-- Package Price -->
                        <div class="form-group">
                            <label for="packageprice">Package Price in PHP</label>
                            <input type="text" class="form-control my-2" name="packageprice" id="packageprice"
                                placeholder="Package Price in PHP" required>
                            <div class="error text-danger" id="packagepriceError"></div>
                        </div>

                        <!-- Package Features -->
                        <div class="form-group">
                            <label for="packagefeatures">Package Features</label>
                            <input type="text" class="form-control my-2" name="packagefeatures" id="packagefeatures"
                                placeholder="Package Features (e.g., Free Pickup-Drop Facility)" required>
                            <div class="error text-danger" id="packagefeaturesError"></div>
                        </div>

                        <!-- Package Details -->
                        <div class="form-group">
                            <label for="packagedetails">Package Details</label>
                            <textarea class="form-control my-2" rows="5" cols="50" name="packagedetails" id="packagedetails"
                                placeholder="Package Details" required></textarea>
                            <div class="error text-danger" id="packagedetailsError"></div>
                        </div>

                        <!-- Package Image -->
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Select input fields
            const form = document.getElementById('packageForm');
            const fields = {
                packagename: /^[a-zA-Z\s]+$/,
                packagetype: /^[a-zA-Z\s]+$/,
                packagelocation: /^[a-zA-Z\s]+$/,
                packageprice: /^\d+$/,
                packagefeatures: /^[a-zA-Z\s]+$/
            };

            // Attach input event listeners for real-time validation
            Object.keys(fields).forEach(field => {
                const input = document.getElementById(field);
                const errorDiv = document.getElementById(`${field}Error`);
                input.addEventListener('input', function () {
                    if (!fields[field].test(input.value)) {
                        errorDiv.innerText = `Invalid input for ${field.replace('package', '').replace(/([A-Z])/g, ' $1')}`;
                    } else {
                        errorDiv.innerText = ''; // Clear error
                    }
                });
            });

            // Image validation
            const imageInput = document.getElementById('packageimage');
            const imageError = document.getElementById('packageimageError');
            const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            imageInput.addEventListener('change', function () {
                const fileName = imageInput.value.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(fileName)) {
                    imageError.innerText = 'Only image files (JPG, PNG, GIF) are allowed.';
                } else {
                    imageError.innerText = ''; // Clear error
                }
            });

            // Prevent form submission if errors exist
            form.addEventListener('submit', function (e) {
                let hasError = false;

                Object.keys(fields).forEach(field => {
                    const input = document.getElementById(field);
                    const errorDiv = document.getElementById(`${field}Error`);
                    if (!fields[field].test(input.value)) {
                        errorDiv.innerText = `Invalid input for ${field.replace('package', '').replace(/([A-Z])/g, ' $1')}`;
                        hasError = true;
                    }
                });

                const fileName = imageInput.value.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(fileName)) {
                    imageError.innerText = 'Only image files (JPG, PNG, GIF) are allowed.';
                    hasError = true;
                }

                if (hasError) {
                    e.preventDefault(); // Prevent form submission
                }
            });
        });
    </script>

    <?php
    require 'includes/footer.php';

    if (isset($_POST['submit'])) {
        // PHP server-side validation and insertion logic here...
    }

    require 'includes/layout-foot.php';
}
?>
