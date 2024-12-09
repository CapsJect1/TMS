<!-- HTML Form for Login -->
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
                                <h4><a href="forgot-password.php">Forgot password</a></h4>

                                <!-- Google reCAPTCHA widget -->
                                <div class="g-recaptcha" data-sitekey="your_recaptcha_site_key"></div>

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

<script>
    // Check if there are stored attempts and reset date
    const storedAttempts = localStorage.getItem('login_attempts');
    const storedDate = localStorage.getItem('login_date');
    const currentDate = new Date().toISOString().split('T')[0]; // Get current date in YYYY-MM-DD format

    let attempts = storedAttempts ? parseInt(storedAttempts) : 0;
    let lastLoginDate = storedDate || currentDate;

    // Reset attempts if the date has changed
    if (currentDate !== lastLoginDate) {
        attempts = 0;
        localStorage.setItem('login_date', currentDate);
    }

    // Show SweetAlert based on attempts
    function showAttemptsMessage() {
        if (attempts >= 3) {
            Swal.fire({
                title: 'Error!',
                text: 'Your attempts are used up. Please try again tomorrow.',
                icon: 'error',
                showConfirmButton: true
            });
            return false; // Prevent form submission if attempts are exhausted
        } else {
            Swal.fire({
                title: 'Login Attempt',
                text: `You have ${3 - attempts} attempts left.`,
                icon: 'info',
                showConfirmButton: true
            });
            return true; // Allow form submission
        }
    }

    // Event listener for form submission
    document.forms['login'].addEventListener('submit', function (event) {
        if (!showAttemptsMessage()) {
            event.preventDefault(); // Prevent form submission if attempts are exhausted
        }
    });

    // Handle login failure
    function handleLoginFailure() {
        attempts += 1;
        localStorage.setItem('login_attempts', attempts);
        showAttemptsMessage();
    }

    // Handle login success (reset attempts)
    function handleLoginSuccess() {
        localStorage.setItem('login_attempts', 0); // Reset attempts on successful login
        window.location.href = 'package-list.php'; // Redirect to another page
    }
</script>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
