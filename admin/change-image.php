<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {
	$imgid = intval($_GET['imgid']);
	if (isset($_POST['submit'])) {

		$pimage = $_FILES["packageimage"]["name"];
		move_uploaded_file($_FILES["packageimage"]["tmp_name"], "pacakgeimages/" . $_FILES["packageimage"]["name"]);
		$sql = "update TblTourPackages set PackageImage=:pimage where PackageId=:imgid";
		$query = $dbh->prepare($sql);

		$query->bindParam(':imgid', $imgid, PDO::PARAM_STR);
		$query->bindParam(':pimage', $pimage, PDO::PARAM_STR);
		$query->execute();
		$msg = "Package Created Successfully";
	}
	require './includes/layout-head.php';
?>
		<div class="card">
		<div class="card-body">
						<h4>Update Package Image </h4>
						<hr>
						<?php if ($error) { ?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>
						<div class="tab-content">
							<div class="tab-pane active" id="horizontal-form">
								<form class="form-horizontal" name="package" method="post" enctype="multipart/form-data">
									<?php
									$imgid = intval($_GET['imgid']);
									$sql = "SELECT PackageImage from TblTourPackages where PackageId=:imgid";
									$query = $dbh->prepare($sql);
									$query->bindParam(':imgid', $imgid, PDO::PARAM_STR);
									$query->execute();
									$results = $query->fetchAll(PDO::FETCH_OBJ);
									$cnt = 1;
									if ($query->rowCount() > 0) {
										foreach ($results as $result) {	?>
											<div class="row my-3">
												<label for="focusedinput" class="col-lg-2 control-label"> Package Image </label>
												<div class="col-lg-10">
													<img src="pacakgeimages/<?php echo htmlentities($result->PackageImage); ?>" width="200">
												</div>
											</div>

											<div class="row my-3">
												<label for="focusedinput" class="col-lg-2 control-label">New Image</label>
												<div class="col-lg-10">
													<input type="file" name="packageimage" id="packageimage" required>
												</div>
											</div>
									<?php }
									} ?>

									<div class="row">
										<div class="col-lg-2"></div>
										<div class="col-sm-8 col-sm-offset-2">
											<button type="submit" name="submit" class="btn-primary btn">Update</button>

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
				require 'includes/footer.php';
				require 'includes/layout-foot.php';
			} ?>