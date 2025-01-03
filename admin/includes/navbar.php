<?php 
  $get_books = $dbh->query("SELECT * FROM `booking` WHERE status = 'payment' OR status = 'pending' OR status ='paid'");

  $get_books_paid = $dbh->query("SELECT * FROM `booking` WHERE status ='paid'");
	// $result_books = $get_books->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
  @media print{
    .dont-print{
      display: none !important;
    }
  }
</style>

<nav class="navbar navbar-expand navbar-light bg-light dont-print">
  <div class="container-fluid">
  <!-- <h1 class="navbar-brand"><a href="dashboard.php" class="text-decoration-none text-dark"></a></h1> -->
  <button type="button" class="btn btn-primary fs-5 px-3 rounded-0 d-lg-none d-block" id="navbar-show"><i class="fa fa-bars"></i></button>

  <ul class="navbar-nav ms-auto">
    <li class="nav-item dropdown">
    <a href="javascript:void(0);" class="dropdown-toggle nav-link" data-bs-toggle="dropdown"><i class="fa fa-bell"></i> <?= $get_books_paid->rowCount() ?></a>
        <ul class="dropdown-menu p-3 dropdown-menu-end ">
         <?php 
            if ($get_books_paid->rowCount() > 0) {
              foreach ($get_books_paid as $paid_book) {
                ?>
                <li class=" border-bottom py-2">
                  <p class="mb-1">#: <span id="myInput"><?= $paid_book['reference_num'] ?></span></p>
                  <p class="mb-1">Name: <?= ucfirst($paid_book['fname']) . ' ' . ucfirst($paid_book['lname']) ?></p>
                  <p class="mb-1">Date/Time: <?= date('F d,Y : h:i A', strtotime($paid_book['date_created'])) ?></p>
                </li>
                <?php
              } 
            }
         ?>
        </ul>
    </li>

    <li class="nav-item dropdown">
      <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown" style="cursor: pointer;"><i class="fa fa-user"></i> <?= $_SESSION['email'] ?></a>

        <ul class="dropdown-menu dropdown-menu-end ">
          <li class="dropdown-item"> 
            <a href="https://santafeport.com/admin/profile.php" class="text-decoration-none text-dark"><i class="fa fa-user"></i> Profile</a> 
          </li>
          <li class="dropdown-item"> 
            <a href="https://santafeport.com/admin/change-password.php" class="text-decoration-none text-dark"><i class="fa fa-gear"></i> Setting</a>
          </li>
          <li class="dropdown-item"> 
            <a href="https://santafeport.com/admin/logout.php" class="text-decoration-none text-dark"><i class="fa fa-sign-out"></i> Logout</a> 
          </li>
        </ul>
    </li>

  </ul>

  </div>
</nav>


<!-- <div class="nav-top dont-print"> -->
  <!-- Load an icon library to show a hamburger menu (bars) on small screens -->
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->

  <!-- <div class="topnav" id="myTopnav">
    <a href="dashboard.php">Santa Fe Port Tourist Environment Fee Staycation Management System</a>

    <div style="display: flex; align-items:center; gap: 20px;">
      <div class="dropdown">
        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <?= $get_books_paid->rowCount() ?></a>
        <ul class="dropdown-menu p-3 dropdown-menu-end">
         <?php 
            if ($get_books_paid->rowCount() > 0) {
              foreach ($get_books_paid as $paid_book) {
                ?>
                <li class=" border-bottom py-2">
                  <p class="mb-1">#: <span id="myInput"><?= $paid_book['reference_num'] ?></span></p>
                  <p class="mb-1">Name: <?= ucfirst($paid_book['fname']) . ' ' . ucfirst($paid_book['lname']) ?></p>
                  <p class="mb-1">Date/Time: <?= date('F d,Y : h:i A', strtotime($paid_book['date_created'])) ?></p>
                </li>
                <?php
              } 
            }
         ?>
        </ul>
      </div>
      <h6>|</h6>
      <div class="dropdown">
        <h6 class="dropdown-toggle" data-toggle="dropdown" style="cursor: pointer;"><i class="fa fa-user"></i> <?= $_SESSION['email'] ?></h6>
        <ul class="dropdown-menu dropdown-menu-right">
          <li> <a href="profile.php"><i class="fa fa-user"></i> Profile</a> </li>
          <li> <a href="change-password.php"><i class="fa fa-gear"></i> Setting</a> </li>
          <li> <a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a> </li>
        </ul>
      </div>
    </div>

  </div>
</div> -->

<script>
  function myFunction() {
    var copyText = document.getElementById("myInput");
    copyText.select();
    copyText.setSelectionRange(0, 99999);

    navigator.clipboard.writeText(copyText.value);

    alert("Copied the text: " + copyText.value);
  }
</script>
<!-- 
<script>
document.addEventListener("DOMContentLoaded", function(){
  const newUrl = '/';

// Change the URL without refreshing the page
history.pushState(null, '', newUrl);
})
</script> -->
