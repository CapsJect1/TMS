<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {
	require './includes/layout-head.php';
?>

	<div class="card">
	<div class="card-body">
		<h4>Update Package</h4>
		<hr>
		<?php if (isset($error)) { ?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } else if (isset($msg)) { ?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>
		<div class="tab-content">
			<div class="tab-pane active" id="horizontal-form">

				<?php
				$pid = (int)$_GET['pid'];
				$sql = "SELECT * from tbltourpackages where PackageId=:pid";
				$query = $dbh->prepare($sql);
				$query->bindParam(':pid', $pid);
				$query->execute();
				$results = $query->fetchAll(PDO::FETCH_OBJ);
				$cnt = 1;
				if ($query->rowCount() > 0) {
					foreach ($results as $result) {	?>

						<form class="form-horizontal" name="package" method="post" enctype="multipart/form-data">
							<div class="row my-3">
								<label for="focusedinput" class="col-sm-2 control-label">Package Name</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="packagename" id="packagename" placeholder="Create Package" value="<?php echo htmlentities($result->PackageName); ?>" required>
								</div>
							</div>
							<div class="row my-3">
								<label for="focusedinput" class="col-sm-2 control-label">Package Type</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="packagetype" id="packagetype" placeholder=" Package Type eg- Family Package / Couple Package" value="<?php echo htmlentities($result->PackageType); ?>" required>
								</div>
							</div>

							<div class="row my-3">
								<label for="focusedinput" class="col-sm-2 control-label">Package Location</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="packagelocation" id="packagelocation" placeholder=" Package Location" value="<?php echo htmlentities($result->PackageLocation); ?>" required>
								</div>
							</div>

							<div class="row my-3">
								<label for="focusedinput" class="col-sm-2 control-label">Package Price in USD</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="packageprice" id="packageprice" placeholder=" Package Price is USD" value="<?php echo htmlentities($result->PackagePrice); ?>" required>
								</div>
							</div>

							<div class="row my-3">
								<label for="focusedinput" class="col-sm-2 control-label">Package Features</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="packagefeatures" id="packagefeatures" placeholder="Package Features Eg-free Pickup-drop facility" value="<?php echo htmlentities($result->PackageFetures); ?>" required>
								</div>
							</div>


							<div class="row my-3">
								<label for="focusedinput" class="col-sm-2 control-label">Package Details</label>
								<div class="col-sm-8">
									<textarea class="form-control" rows="5" cols="50" name="packagedetails" id="packagedetails" placeholder="Package Details" required><?php echo htmlentities($result->PackageDetails); ?></textarea>
								</div>
							</div>
							<div class="row my-3">
								<label for="focusedinput" class="col-sm-2 control-label">Package Image</label>
								<div class="col-sm-8">
									<img src="pacakgeimages/<?php echo htmlentities($result->PackageImage); ?>" width="200">&nbsp;&nbsp;&nbsp;<a href="change-image.php?imgid=<?php echo htmlentities($result->PackageId); ?>" class="text-primary text-decoration-none">Change Image</a>
								</div>
							</div>

							<div class="row my-3">
								<label for="focusedinput" class="col-sm-2 control-label">Last Updation Date</label>
								<div class="col-sm-8">
									<?php echo htmlentities($result->UpdationDate); ?>
								</div>
							</div>
					<?php }
				} ?>

					<div class="row">
						<div class="col-lg-2"></div>
						<div class="col-sm-8 col-sm-offset-2">
							<button type="submit" name="submit" class="btn-primary btn rounded-0">Update</button>
						</div>
					</div>





			</div>

			</form>





			<div class="panel-footer">

			</div>
			</form>
		</div>
	</div>
	</div>

	<?php
	$pid = intval($_GET['pid']);
	if (isset($_POST['submit'])) {
		$pname = $_POST['packagename'];
		$ptype = $_POST['packagetype'];
		$plocation = $_POST['packagelocation'];
		$pprice = $_POST['packageprice'];
		$pfeatures = $_POST['packagefeatures'];
		$pdetails = $_POST['packagedetails'];
		// $pimage = $_FILES["packageimage"]["name"];
		$sql = "UPDATE TblTourPackages SET PackageName=:pname,PackageType=:ptype,PackageLocation=:plocation,PackagePrice=:pprice,PackageFetures=:pfeatures,PackageDetails=:pdetails where PackageId=:pid";
		$query = $dbh->prepare($sql);
		$query->bindParam(':pname', $pname, PDO::PARAM_STR);
		$query->bindParam(':ptype', $ptype, PDO::PARAM_STR);
		$query->bindParam(':plocation', $plocation, PDO::PARAM_STR);
		$query->bindParam(':pprice', $pprice, PDO::PARAM_STR);
		$query->bindParam(':pfeatures', $pfeatures, PDO::PARAM_STR);
		$query->bindParam(':pdetails', $pdetails, PDO::PARAM_STR);
		$query->bindParam(':pid', $pid, PDO::PARAM_STR);
		$query->execute();
		// $msg="Package Updated Successfully";
	?>
		<script>
			document.addEventListener("DOMContentLoaded", function(){
				Swal.fire({
				position: 'top-end',
				icon: 'success',
				title: "Package updated succesfully",
				showConfirmButton: false,
				timer: 1500
			}).then(() => {
				window.location.href = "update-package.php?pid=<?= $pid ?>"
			})
			})
		</script>
	<?php
	}

	?>

<?php
	require 'includes/footer.php';
	require 'includes/layout-foot.php';
} ?>