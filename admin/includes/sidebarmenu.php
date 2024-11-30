<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<div class="sidebar-menu dont-print">
	<header class="logo1">
		<a href="#" class="sidebar-icon"> <span class="fa fa-bars"></span> </a>
	</header>
	<div style="border-top:1px ridge rgba(255, 255, 255, 0.15)"></div>
	<div class="menu">
		<ul id="menu">
			<li><a href="dashboard.php"><i class="fa fa-tachometer"></i> <span>Dashboard</span>
					<div class="clearfix"></div>
				</a></li>
				<li id="menu-academico"><a href="#"><i class="fa fa-list-ul" aria-hidden="true"></i><span> Packages
					</span> <span class="fa fa-angle-right" style="float: right"></span>
					<div class="clearfix"></div>
				</a>
				<ul id="menu-academico-sub">
					<li id="menu-academico-avaliacoes"><a href="create-package.php">Create</a></li>
					<li id="menu-academico-avaliacoes"><a href="manage-packages.php">Manage</a></li>
				</ul>
			</li>

			<li id="menu-academico"><a href="manage-users.php"><i class="fa fa-users"
						aria-hidden="true"></i><span>Manage Tourist</span>
					<div class="clearfix"></div>
				</a></li>

			<li><a href="manage-bookings.php"><i class="fa fa-list" aria-hidden="true"></i> <span>Manage Booking</span>
					<div class="clearfix"></div>
				</a></li>
		

			<li><a href="manageissues.php"><i class="fa fa-table"></i> <span>Manage Issues</span>
					<div class="clearfix"></div>
				</a></li>
			<li><a href="manage-enquires.php"><i class="fa fa-file-text-o" aria-hidden="true"></i> <span>Manage
						Enquiries</span>
					<div class="clearfix"></div>
				</a></li>
			<li><a href="manage-pages.php"><i class="fa fa-file-text-o" aria-hidden="true"></i> <span>Manage
						Pages</span>
					<div class="clearfix"></div>
				</a></li>
				<li><a href="book-report.php"><i class="fa fa-signal" aria-hidden="true"></i> <span>Book Report</span>
					<div class="clearfix"></div>
				</a></li>


		</ul>
	</div>
	</div> -->

<?php 
	$active_li = "bg-light";
	$active_a = "text-dark";
	// $url = implode(explode("/santafe-edit", $_SERVER['REQUEST_URI']));
	$url = implode(explode("/santafeport.com", $_SERVER['REQUEST_URI']));
	
?>
<aside class="col-lg-2 col-10 p-0 d-lg-block d-none" style="height: 100vh; background: #3f8de0;">
	<ul class="nav w-100">
		<li class="nav-item w-100 d-flex align-items-center justify-content-between">
			<a href="dashboard.php" class="nav-link text-light fs-3 fw-bold">
			Santa Fe Port 
				<p class="fw-normal" style="font-size: 14px;">Tourist Biological Fee Staycation Management</p>
			</a>
			<button type="button"
				class="bg-transparent text-light border-0 p-0 fs-3 fw-bold pe-3 d-lg-none d-block"
				id="navbar-close">X</button>
		</li>
		<li class="nav-item w-100 <?= $url == "/admin/dashboard.php" ? $active_li : '' ?>">
			<a href="dashboard.php" class="nav-link <?= $url == "/admin/dashboard.php" ? $active_a : 'text-light' ?>"><i class="fa fa-tachometer"></i> DASHBOARD</a>
		</li>
		<li class="nav-item w-100 <?= $url == "/admin/create-package.php" || str_contains($url, "update-package.php") || str_contains($url, "change-image.php") || $url == "/admin/manage-packages.php" ? $active_li : '' ?>">
			<a href="#" class="nav-link <?= $url == "/admin/create-package.php" || str_contains($url, "update-package.php") || str_contains($url, "change-image.php") || $url == "/admin/manage-packages.php" ? $active_a : 'text-light' ?> d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#package-link"><span><i class="fa fa-list-ul"></i> Packages </span> <i class="fa fa-caret-down"></i></a>

			<div class="collapse px-3" id="package-link">
				<a href="create-package.php" class="text-decoration-none  <?= $url == "/admin/create-package.php" || str_contains($url, "update-package.php") || str_contains($url, "change-image.php") || $url == "/admin/manage-packages.php" ? $active_a : 'text-light' ?>"><i class="fa fa-caret-right"></i> Create</a>
				<br>
				<a href="manage-packages.php" class="text-decoration-none  <?= $url == "/admin/create-package.php" || str_contains($url, "update-package.php") || str_contains($url, "change-image.php") || $url == "/admin/manage-packages.php" ? $active_a : 'text-light' ?>"><i class="fa fa-caret-right"></i> Manage</a>
			</div>

		</li>
		<li class="nav-item w-100 <?= str_contains($url, "/admin/manage-users.php") || str_contains($url, "/admin/user-bookings.php") ? $active_li : '' ?>">
			<a href="manage-users.php" class="nav-link <?= str_contains($url, "/admin/manage-users.php") || str_contains($url, "/admin/user-bookings.php") ? $active_a : 'text-light' ?>"><i class="fa fa-users"></i> Manage Tourist</a>
		</li>
		<li class="nav-item w-100 <?= str_contains($url, "/admin/manage-bookings.php") ? $active_li : '' ?>">
			<a href="manage-bookings.php" class="nav-link <?= str_contains($url, "/admin/manage-bookings.php") ? $active_a : 'text-light' ?>"><i class="fa fa-book"></i> Manage Booking</a>
		</li>
		<li class="nav-item w-100 <?= $url == "/admin/manageissues.php" ? $active_li : '' ?>">
			<a href="manageissues.php" class="nav-link <?= $url == "/admin/manageissues.php" ? $active_a : 'text-light' ?>"><i class="fa fa-table"></i> Manage Issues</a>
		</li>
		<li class="nav-item w-100 <?= $url == "/admin/manage-enquires.php" ? $active_li : '' ?>">
			<a href="manage-enquires.php" class="nav-link  <?= $url == "/admin/manage-enquires.php" ? $active_a : 'text-light' ?>"><i class="fa fa-file-text-o"></i> Manage Enquiries</a>
		</li>
		<li class="nav-item w-100  <?= str_contains($url, "/admin/manage-pages.php") ? $active_li : '' ?>">
			<a href="manage-pages.php" class="nav-link <?= str_contains($url, "/admin/manage-pages.php") ? $active_a : 'text-light' ?>"><i class="fa fa-file-text-o"></i> Manage
			Pages</a>
		</li>
		<li class="nav-item w-100 <?= $url == "/admin/book-report.php" ? $active_li : '' ?>">
			<a href="book-report.php" class="nav-link <?= $url == "/admin/book-report.php" ? $active_a : 'text-light' ?>"><i class="fa fa-bar-chart"></i> Book Report</a>
		</li>
	</ul>
</aside>

<script>
        document.addEventListener("DOMContentLoaded", function () {
			
            let sidebar = document.querySelector("aside");
            let navbarShow = document.getElementById('navbar-show');
            let navbarClose = document.getElementById('navbar-close');

            navbarShow.onclick = () => {
                sidebar.classList.remove("d-none")
            }

            navbarClose.onclick = () => {
                sidebar.classList.add("d-none")
            }

            const xValues = [100, 200, 300, 400, 500, 600, 700, 800, 900, 1000];

            new Chart("myChart", {
                type: "line",
                data: {
                    labels: xValues,
                    datasets: [{
                        data: [860, 1140, 1060, 1060, 1070, 1110, 1330, 2210, 7830, 2478],
                        borderColor: "hsl(169, 100%, 35%)",
                        fill: false
                    }]
                },
                options: {
                    legend: { display: false }
                }
            });

        })
    </script>