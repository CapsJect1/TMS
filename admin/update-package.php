<?php
session_start();
// error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
	{	
header('location:index.php');
}
else{

	?>
<!DOCTYPE HTML>
<html>
<head>
<title>TMS | Admin Package Creation</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Pooled Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link rel="stylesheet" href="css/morris.css" type="text/css"/>
<link href="css/font-awesome.css" rel="stylesheet"> 
<script src="js/jquery-2.1.4.min.js"></script>
<script src="js/sweet_alert.js"></script>
<link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'/>
<link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
  <style>
		.errorWrap {
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #dd3d36;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #5cb85c;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
		</style>

</head> 
<body>
   <div class="page-container">
   <!--/content-inner-->

<?php 
	$pid=intval($_GET['pid']);	
	if(isset($_POST['submit']))
	{
	$pname=$_POST['packagename'];
	$ptype=$_POST['packagetype'];	
	$plocation=$_POST['packagelocation'];
	$pprice=$_POST['packageprice'];	
	$pfeatures=$_POST['packagefeatures'];
	$pdetails=$_POST['packagedetails'];	
	$pimage=$_FILES["packageimage"]["name"];
	$sql="UPDATE TblTourPackages SET PackageName=:pname,PackageType=:ptype,PackageLocation=:plocation,PackagePrice=:pprice,PackageFetures=:pfeatures,PackageDetails=:pdetails where PackageId=:pid";
	$query = $dbh->prepare($sql);
	$query->bindParam(':pname',$pname,PDO::PARAM_STR);
	$query->bindParam(':ptype',$ptype,PDO::PARAM_STR);
	$query->bindParam(':plocation',$plocation,PDO::PARAM_STR);
	$query->bindParam(':pprice',$pprice,PDO::PARAM_STR);
	$query->bindParam(':pfeatures',$pfeatures,PDO::PARAM_STR);
	$query->bindParam(':pdetails',$pdetails,PDO::PARAM_STR);
	$query->bindParam(':pid',$pid,PDO::PARAM_STR);
	$query->execute();
	// $msg="Package Updated Successfully";
	?>
	<script>
	   Swal.fire({
		   position: 'top-end',
		   icon: 'success',
		   title: "Package updated succesfully",
		   showConfirmButton: false,
		   timer: 1500
	   }).then(() => {
		   window.location.href = "update-package.php?pid=<?= $pid ?>"
	   })
	</script>
	<?php 
	}
	
?>
  <!--//content-inner-->
		<!--/sidebar-menu-->
					<?php include('includes/sidebarmenu.php');?>
							  <div class="clearfix"></div>		
							</div>
							<script>
							var toggle = true;
										
							$(".sidebar-icon").click(function() {                
							  if (toggle)
							  {
								$(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
								$("#menu span").css({"position":"absolute"});
							  }
							  else
							  {
								$(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
								setTimeout(function() {
								  $("#menu span").css({"position":"relative"});
								}, 400);
							  }
											
											toggle = !toggle;
										});
							</script>
<!--js -->
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
<!-- Bootstrap Core JavaScript -->
   <script src="js/bootstrap.min.js"></script>
   <!-- /Bootstrap Core JavaScript -->	   

</body>
</html>
<?php } ?>