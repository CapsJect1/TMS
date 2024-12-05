<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-info">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body modal-spa">
				<div class="login-grids">
					<div class="login">
						<div class="login-right">
							<form method="post" name="login">
								<h3>Sign in with your account</h3>
								<input type="text" name="email" id="email" placeholder="Enter your Email" required="">

								<div style="position: relative;">
									<input type="password" name="password" id="password" placeholder="Password" value=""
										required="">
									<i class="fa fa-eye" id="show-pass2" style="position: absolute; top: 0; right: 0; margin: 35px 10px 0 0;"></i>
								</div>

								<!-- reCAPTCHA -->
								<div class="g-recaptcha" data-sitekey="your-site-key-here"></div>

								<h4><a href="forgot-password.php">Forgot password</a></h4>
								<input type="submit" name="signin" value="SIGN IN">
							</form>
						</div>
						<div class="clearfix"></div>
					</div>
					<p>By logging in you agree to our <a href="page.php?type=terms">Terms and Conditions</a> and <a
							href="page.php?type=privacy">Privacy Policy</a></p>
				</div>
			</div>
		</div>
	</div>
</div>
