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
                            <small class="form-text text-muted">Only letters and spaces are allowed.</small>
                            <input type="text" class="form-control my-2" name="packagename" id="packagename"
                                placeholder="Create room or resort" required>
                            <div class="feedback" id="packagenameFeedback"></div>
                        </div>

                        <div class="form-group">
                            <label for="packagetype">Package Type</label>
                            <small class="form-text text-muted">Only letters and spaces are allowed.</small>
                            <input type="text" class="form-control my-2" name="packagetype" id="packagetype"
                                placeholder="Package Type (e.g., Family Package / Couple Package)" required>
                            <div class="feedback" id="packagetypeFeedback"></div>
                        </div>

                        <div class="form-group">
                            <label for="packagelocation">Package Location</label>
                            <small class="form-text text-muted">Only letters and spaces are allowed.</small>
                            <input type="text" class="form-control my-2" name="packagelocation" id="packagelocation"
                                placeholder="Package Location" required>
                            <div class="feedback" id="packagelocationFeedback"></div>
                        </div>

                        <div class="form-group">
                            <label for="packageprice">Package Price in PHP</label>
                            <small class="form-text text-muted">Only numeric values are allowed.</small>
                            <input type="text" class="form-control my-2" name="packageprice" id="packageprice"
                                placeholder="Package Price in PHP" required>
                            <div class="feedback" id="packagepriceFeedback"></div>
                        </div>

                        <div class="form-group">
                            <label for="packagefeatures">Package Features</label>
                            <small class="form-text text-muted">Only letters and spaces are allowed.</small>
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
                            <small class="form-text text-muted">Only image files (JPG, PNG, GIF) are allowed.</small>
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
        // Validation rules
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

        // Real-time validation
        Object.keys(inputs).forEach(key => {
            const input = document.getElementById(key);
            const feedbackElement = document.getElementById(inputs[key].feedbackId);

            input.addEventListener('input', function () {
                const value = inputs[key].isFile ? input.files[0]?.name : input.value;

                if (!validationRules[inputs[key].rule].test(value)) {
                    feedbackElement.textContent = "Invalid input! Please follow the instructions.";
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
                    feedbackElement.textContent = "Invalid input! Please follow the instructions.";
                    feedbackElement.style.color = "red";
                    isValid = false;
                }
            });

            if (!isValid) e.preventDefault();
        });
    </script>

    <?php
    if (isset($_POST['submit'])) {
        // Server-side code omitted for brevity
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
