<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
	<!--plugins-->
	<link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />
	<script src="assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
	<title>Reset Password - Technofra Renewal Master</title>
	<style>
		.password-requirements {
			font-size: 0.875rem;
			color: #6c757d;
			margin-top: 0.25rem;
		}
		.password-requirements ul {
			margin: 0;
			padding-left: 1rem;
		}
		.password-requirements li {
			margin-bottom: 0.25rem;
		}
		.form-control:focus {
			border-color: #0d6efd;
			box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
		}
		.btn-primary {
			background-color: #0d6efd;
			border-color: #0d6efd;
		}
		.btn-primary:hover {
			background-color: #0b5ed7;
			border-color: #0a58ca;
		}
	</style>
</head>

<body class="">
	<!--wrapper-->
	<div class="wrapper">
		<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
			<div class="container">
				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
					<div class="col mx-auto">
						<div class="card mb-0">
							<div class="card-body">
								<div class="p-4">
									<div class="mb-3 text-center">
										<img src="assets/images/logo-icon.png" width="60" alt="" />
									</div>
									<div class="text-center mb-4">
										<h5 class="">Technofra Admin</h5>
										<p class="mb-0">Create your new password</p>
										<small class="text-muted">Password must be at least 6 characters long</small>
									</div>
									<div class="form-body">
										@if(session('success'))
											<div class="alert alert-success alert-dismissible fade show" role="alert">
												{{ session('success') }}
												<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
											</div>
										@endif

										@if(session('error'))
											<div class="alert alert-danger alert-dismissible fade show" role="alert">
												{{ session('error') }}
												<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
											</div>
										@endif

										@if($errors->any())
											<div class="alert alert-danger alert-dismissible fade show" role="alert">
												<ul class="mb-0">
													@foreach($errors->all() as $error)
														<li>{{ $error }}</li>
													@endforeach
												</ul>
												<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
											</div>
										@endif

										<form class="row g-3" method="POST" action="{{ route('password.update') }}">
											@csrf
											<input type="hidden" name="token" value="{{ $token }}">
											<input type="hidden" name="email" value="{{ $email }}">

											<div class="col-12">
												<label for="inputEmailAddress" class="form-label">Email Address</label>
												<input type="email" class="form-control" id="inputEmailAddress"
													   value="{{ $email }}" readonly style="background-color: #f8f9fa; cursor: not-allowed;">
											</div>
											<div class="col-12">
												<label for="inputNewPassword" class="form-label">New Password</label>
												<div class="input-group" id="show_hide_password">
													<input type="password" class="form-control border-end-0 @error('password') is-invalid @enderror"
														   id="inputNewPassword" name="password" placeholder="Enter New Password" required>
													<a href="javascript:;" class="input-group-text bg-transparent">
														<i class='bx bx-hide'></i>
													</a>
												</div>
												<div class="password-requirements">
													<small>Password must be at least 6 characters long</small>
												</div>
												@error('password')
													<div class="invalid-feedback d-block">{{ $message }}</div>
												@enderror
											</div>
											<div class="col-12">
												<label for="inputConfirmPassword" class="form-label">Confirm New Password</label>
												<div class="input-group" id="show_hide_password_confirm">
													<input type="password" class="form-control border-end-0 @error('password_confirmation') is-invalid @enderror"
														   id="inputConfirmPassword" name="password_confirmation" placeholder="Confirm New Password" required>
													<a href="javascript:;" class="input-group-text bg-transparent">
														<i class='bx bx-hide'></i>
													</a>
												</div>
												@error('password_confirmation')
													<div class="invalid-feedback d-block">{{ $message }}</div>
												@enderror
											</div>
											<div class="col-12">
												<div class="d-grid">
													<button type="submit" class="btn btn-primary">Reset Password</button>
												</div>
											</div>
											<div class="col-12">
												<div class="text-center">
													<p class="mb-0">Remember your password? <a href="{{ route('login') }}">Sign in here</a></p>
												</div>
											</div>
										</form>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
			</div>
		</div>
	</div>
	<!--end wrapper-->
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
			</div>
		</div>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<!--Password show & hide js -->
	<script>
		$(document).ready(function () {
			// Password toggle for new password field
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});

			// Password toggle for confirm password field
			$("#show_hide_password_confirm a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password_confirm input').attr("type") == "text") {
					$('#show_hide_password_confirm input').attr('type', 'password');
					$('#show_hide_password_confirm i').addClass("bx-hide");
					$('#show_hide_password_confirm i').removeClass("bx-show");
				} else if ($('#show_hide_password_confirm input').attr("type") == "password") {
					$('#show_hide_password_confirm input').attr('type', 'text');
					$('#show_hide_password_confirm i').removeClass("bx-hide");
					$('#show_hide_password_confirm i').addClass("bx-show");
				}
			});

			// Form validation
			$('form').on('submit', function(e) {
				var password = $('#inputNewPassword').val();
				var confirmPassword = $('#inputConfirmPassword').val();

				if (password.length < 6) {
					e.preventDefault();
					alert('Password must be at least 6 characters long.');
					return false;
				}

				if (password !== confirmPassword) {
					e.preventDefault();
					alert('Passwords do not match.');
					return false;
				}
			});
		});
	</script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>
</body>

<script>'undefined'=== typeof _trfq || (window._trfq = []);'undefined'=== typeof _trfd && (window._trfd=[]),_trfd.push({'tccl.baseHost':'secureserver.net'},{'ap':'cpsh-oh'},{'server':'p3plzcpnl509132'},{'dcenter':'p3'},{'cp_id':'10399385'},{'cp_cl':'8'}) // Monitoring performance to make your website faster. If you want to opt-out, please contact web hosting support.</script><script src='../../../../img1.wsimg.com/signals/js/clients/scc-c2/scc-c2.min.js'></script>

</html>
