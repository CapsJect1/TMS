<?php 
  $get_books = $dbh->query("SELECT * FROM `booking` WHERE status = 'payment' OR status = 'pending' OR status ='paid'");
	// $result_books = $get_books->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
  .left-content {
    position: relative;
  }

  a{
    text-decoration: none;
  }

  .nav-top {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    width: 100%;

  }

  /* Add a black background color to the top navigation */
  .topnav {
    background-color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 20px;
  }
</style>

<div class="nav-top dont-print">
  <!-- Load an icon library to show a hamburger menu (bars) on small screens -->
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->

  <div class="topnav" id="myTopnav">
    <a href="dashboard.php">Santa Fe Port Tourist Biological Fee Staycation Management System</a>

    <div style="display: flex; align-items:center; gap: 20px;">
      <!-- <h6><i class="fa fa-bell"></i> <?= $get_books->rowCount() ?></h6> -->
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
</div>