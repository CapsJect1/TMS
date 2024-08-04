<style>
	.copy-right {
		background-color: #3F84B1 !important;
	}
</style>
<footer class="footer-07 dont-print">
	<div class="">
		<div class="row justify-content-center">
			<div class="copy-right">
				<div class="container">

					<div class="footer-social-icons wow fadeInDown animated animated" data-wow-delay=".5s"
						style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInDown;">
						<ul>
							<li><a class="facebook" href="#"><span>Facebook</span></a></li>
							<li><a class="twitter" href="#"><span>Twitter</span></a></li>
							<li><a class="flickr" href="#"><span>Flickr</span></a></li>
							<li><a class="googleplus" href="#"><span>Google+</span></a></li>
							<li><a class="dribbble" href="#"><span>Dribbble</span></a></li>
						</ul>
					</div>
					<p class="wow zoomIn animated animated" data-wow-delay=".5s"
						style="visibility: visible; animation-delay: 0.5s; animation-name: zoomIn;"> &copy; SFP - Develop by : Johnrey R . Alo And Percedie B. Buhayan, Contact us : 09368233590/09693457783</p>
				</div>
			</div>
		</div>
	</div>
</footer>


<div class="modal" id="show-books">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
			<h5 class="modal-title mb-0">Notifications</h5>
			</div>

			<div class="modal-body">
				<div class="row">

				
				<?php 
				if ($get_books_payment->rowCount() > 0) {
				foreach ($get_books_payment as $payments_book) {
					?>
					<div class="col-md-12">
					<p class="mb-1">#: <span id="myInput"><?= $payments_book['reference_num'] ?></span></p>
					<p class="mb-1">Name: <?= ucfirst($payments_book['fname']) . ' ' . ucfirst($payments_book['lname']) ?></p>
					<div style="display: flex; align-items: center; justify-content: space-between;">
					<span class="badge">Proceed to payment</span>
					<a href="issuetickets.php?pay=<?= $payments_book['id'] ?>" style="color: blue !important; text-decoration: underline;">Proceed</a>
					</div>
					</div>
					<?php
				} 
				}
			?>	
			</div>
			</div>
		</div>
	</div>
</div>



<!--- /footer-top ---->
<!---copy-right ---->
