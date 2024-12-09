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
					<form class="form-horizontal" name="package" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">

						<label for="focusedinput" class="col-sm-2 control-label">Room or resort</label>
						<input type="text" class="form-control my-2" name="packagename" id="packagename"
							placeholder="Create room or resort" required>

						<label for="focusedinput" class="col-sm-2 control-label">Package Type</label>
						<input type="text" class="form-control my-2" name="packagetype" id="packagetype"
							placeholder="Package Type eg- Family Package / Couple Package" required>

						<label for="focusedinput" class="col-sm-2 control-label">Package Location</label>
						<input type="text" class="form-control my-2" name="packagelocation" id="packagelocation" placeholder="Package Location" required>

						<label for="focusedinput" class="col-sm-2 control-label">Package Price in PHP</label>
						<input type="text" class="form-control my-2" name="packageprice" id="packageprice" placeholder="Package Price in PHP" required>

						<label for="focusedinput" class="col-sm-2 control-label">Package Features</label>
						<input type="text" class="form-control my-2" name="packagefeatures" id="packagefeatures"
							placeholder="Package Features Eg- Free Pickup-drop facility" required>

						<label for="focusedinput" class="col-sm-2 control-label">Package Details</label>
						<textarea class="form-control my-2" rows="5" cols="50" name="packagedetails" id="packagedetails" placeholder="Package Details" required></textarea>

						<label for="focusedinput" class="col-sm-2 control-label">Package Image</label>
						<input type="file" name="packageimage" class="form-control my-2" id="packageimage" accept="image/*" required>

						<div class="mt-3">
							<button type="submit" name="submit" class="btn btn-primary">Create</button>
							<button type="reset" class="btn-secondary btn">Reset</button>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>

	<script>
		function validateForm() {
			// Validate text fields
			const textFields = ['packagename', 'packagetype', 'packagelocation', 'packagefeatures'];
			for (const fieldId of textFields) {
				const field = document.getElementById(fieldId).value;
				const regex = /^[a-zA-Z\s]+$/;
				if (!regex.test(field)) {
					alert("Please enter valid text for " + fieldId.replace('package', '').toUpperCase());
					return false;
				}
			}

			// Validate numeric field
			const packagePrice = document.getElementById("packageprice").value;
			if (isNaN(packagePrice) || packagePrice <= 0) {
				alert("Please enter a valid numeric value for Package Price.");
				return false;
			}

			// Validate image file
			const packageImage = document.getElementById("packageimage").value;
			const validExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
			if (!validExtensions.test(packageImage)) {
				alert("Please upload a valid image file (jpg, jpeg, png, gif).");
				return false;
			}

			return true;
		}
	</script>

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

		// Upload image
		move_uploaded_file($_FILES["packageimage"]["tmp_name"], "packageimages/" . $pimage);

		$sql = "INSERT INTO tbltourpackages(PackageName,PackageType,PackageLocation,PackagePrice,PackageFetures,PackageDetails,PackageImage) 
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
	?>
			<script>
				document.addEventListener("DOMContentLoaded", function() {
					Swal.fire({
						position: 'top-end',
						icon: 'success',
						title: "Package added successfully",
						showConfirmButton: false,
						timer: 1500
					}).then(() => {
						window.location.href = "create-package.php";
					});
				});
			</script>
		<?php
		} else {
		?>
			<script>
				document.addEventListener("DOMContentLoaded", function() {
					Swal.fire({
						position: 'top-end',
						icon: 'error',
						title: "Something went wrong. Please try again",
						showConfirmButton: false,
						timer: 1500
					}).then(() => {
						window.location.href = "create-package.php";
					});
				});
			</script>
	<?php
		}
	}

	require 'includes/layout-foot.php';
} ?>
