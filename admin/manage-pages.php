<?php
session_start();
error_reporting(0);
include ('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {
	if ($_POST['submit'] == "Update") {
		$pagetype = $_GET['type'];
		$pagedetails = $_POST['pgedetails'];
		$sql = "UPDATE tblpages SET detail=:pagedetails WHERE type=:pagetype";
		$query = $dbh->prepare($sql);
		$query->bindParam(':pagetype', $pagetype, PDO::PARAM_STR);
		$query->bindParam(':pagedetails', $pagedetails, PDO::PARAM_STR);
		$query->execute();
		$msg = "Page data updated  successfully";

	}

	require './includes/layout-head.php';
	?>
		<script
			type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
		<!-- <link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' /> -->
		<!-- <link href="css/style.css" rel='stylesheet' type='text/css' /> -->
		<!-- <link rel="stylesheet" href="css/morris.css" type="text/css" /> -->
		<!-- <link href="css/font-awesome.css" rel="stylesheet"> -->
		<script src="js/jquery-2.1.4.min.js"></script>
		<link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet'
			type='text/css' />
		<link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
		<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
		<style>
			.errorWrap {
				padding: 10px;
				margin: 0 0 20px 0;
				background: #fff;
				border-left: 4px solid #dd3d36;
				-webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
				box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
			}

			.succWrap {
				padding: 10px;
				margin: 0 0 20px 0;
				background: #fff;
				border-left: 4px solid #5cb85c;
				-webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
				box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
			}
		</style>

		<script type="text/JavaScript">
	<!--
	function MM_findObj(n, d) { //v4.01
	  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
		d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
	  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
	  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
	  if(!x && d.getElementById) x=d.getElementById(n); return x;
	}

	function MM_validateForm() { //v4.0
	  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
	  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
		if (val) { nm=val.name; if ((val=val.value)!="") {
		  if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
			if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
		  } else if (test!='R') { num = parseFloat(val);
			if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
			if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
			  min=test.substring(8,p); max=test.substring(p+1);
			  if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
		} } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
	  } if (errors) alert('The following error(s) occurred:\n'+errors);
	  document.MM_returnValue = (errors == '');
	}

	function MM_jumpMenu(targ,selObj,restore){ //v3.0
	  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	  if (restore) selObj.selectedIndex=0;
	}
	//-->
	</script>
		<script type="text/javascript" src="nicEdit.js"></script>
		<script type="text/javascript">
			bkLib.onDomLoaded(function () { nicEditors.allTextAreas() });
		</script>

		<div class="card">
			<div class="card-body">
				<h4>Update Page</h4>
				<hr>
				<div class="grid-form1">
					
						<?php if ($error) { ?>
							<div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?>
								<div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>
						<div class="tab-content">
							<div class="tab-pane active" id="horizontal-form">
								<form class="form-horizontal" name="package" method="post" enctype="multipart/form-data">
									<div class="d-flex gap-2 my-3 align-items-center">
										<label for="focusedinput">Select page</label>
										<div>
											<select name="menu1" onChange="MM_jumpMenu('parent',this,0)" class="form-select">
												<option value="" selected="selected" class="form-control">***Select One***
												</option>
												<option value="manage-pages.php?type=terms">terms and condition</option>
												<option value="manage-pages.php?type=privacy">privacy and policy</option>
												<option value="manage-pages.php?type=aboutus">aboutus</option>
												<option value="manage-pages.php?type=contact">Contact us</option>
											</select>
										</div>
									</div>
									<div class="d-flex gap-2 my-3 align-items-center ">
										<label for="focusedinput">Selected Page : </label>
										<div>
											<?php

											switch ($_GET['type']) {
												case "terms":
													echo "Terms and Conditions";
													break;

												case "privacy":
													echo "Privacy And Policy";
													break;

												case "aboutus":
													echo "About US";
													break;
												case "software":
													echo "Offers";
													break;
												case "aspnet":
													echo "Vission And MISSION";
													break;
												case "objectives":
													echo "Objectives";
													break;
												case "disclaimer":
													echo "Disclaimer";
													break;
												case "vbnet":
													echo "Partner With Us";
													break;
												case "candc":
													echo "Super Brand";
													break;
												case "contact":
													echo "Contact Us";
													break;




												default:
													echo "";
													break;

											}





											?>
										</div>
									</div>






									<div class="row g-1">
										<label for="focusedinput" class="col-lg-2 control-label">Page Details</label>
										<div class="col-lg-10">


											<textarea class="form-control" rows="5" cols="50" name="pgedetails"
												id="pgedetails" placeholder="Package Details" required>
											<?php
											$pagetype = $_GET['type'];
											$sql = "SELECT detail from tblpages where type=:pagetype";
											$query = $dbh->prepare($sql);
											$query->bindParam(':pagetype', $pagetype, PDO::PARAM_STR);
											$query->execute();
											$results = $query->fetchAll(PDO::FETCH_OBJ);
											$cnt = 1;
											if ($query->rowCount() > 0) {
												foreach ($results as $result) {
													echo htmlentities($result->detail);
												}
											}
											?>

											</textarea>
										</div>
									</div>


									<div class="row">
										<div class="col-lg-2"></div>
										<div class="col-lg-10 col-sm-offset-2">
											<button type="submit" name="submit" value="Update" id="submit"
												class="btn-primary btn mt-3">Update</button>


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
		</div>
		<script src="js/scripts.js"></script>
<?php 
	require 'includes/footer.php';
	require 'includes/layout-foot.php';

} ?>