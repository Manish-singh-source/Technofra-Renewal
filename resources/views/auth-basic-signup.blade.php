<!doctype html>
<html lang="en">


<!-- Mirrored from codervent.com/rocker/demo/vertical/auth-basic-signup.php by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 14 Aug 2025 07:34:51 GMT -->
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
	<title>Technofra Renewal Master</title>
</head>

<body class="">
	<!--wrapper-->
	<div class="wrapper">
		<div class="d-flex align-items-center justify-content-center my-5">
			<div class="container-fluid">
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
										<p class="mb-0">Please fill the below details to create your account</p>
									</div>
									<div class="form-body">
										@if(session('success'))
											<div class="alert alert-success alert-dismissible fade show" role="alert">
												{{ session('success') }}
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

										<form class="row g-3" method="POST" action="{{ route('register') }}">
											@csrf
											<div class="col-12">
												<label for="inputUsername" class="form-label">Name</label>
												<input type="text" class="form-control @error('name') is-invalid @enderror"
													   id="inputUsername" name="name" value="{{ old('name') }}"
													   placeholder="Enter your full name" required>
												@error('name')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
											</div>
											<div class="col-12">
												<label for="inputEmailAddress" class="form-label">Email Address</label>
												<input type="email" class="form-control @error('email') is-invalid @enderror"
													   id="inputEmailAddress" name="email" value="{{ old('email') }}"
													   placeholder="example@user.com" required>
												@error('email')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
											</div>
											<div class="col-12">
												<label for="inputChoosePassword" class="form-label">Password</label>
												<div class="input-group" id="show_hide_password">
													<input type="password" class="form-control border-end-0 @error('password') is-invalid @enderror"
														   id="inputChoosePassword" name="password" placeholder="Enter Password" required>
													<a href="javascript:;" class="input-group-text bg-transparent">
														<i class='bx bx-hide'></i>
													</a>
												</div>
												@error('password')
													<div class="invalid-feedback d-block">{{ $message }}</div>
												@enderror
											</div>
											<div class="col-12">
												<label for="inputConfirmPassword" class="form-label">Confirm Password</label>
												<input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
													   id="inputConfirmPassword" name="password_confirmation"
													   placeholder="Confirm Password" required>
												@error('password_confirmation')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
											</div>
											<div class="col-12">
												<div class="form-check form-switch">
													<input class="form-check-input @error('terms') is-invalid @enderror"
														   type="checkbox" id="flexSwitchCheckChecked" name="terms" value="1"
														   {{ old('terms') ? 'checked' : '' }} required>
													<label class="form-check-label" for="flexSwitchCheckChecked">
														I read and agree to Terms & Conditions
													</label>
													@error('terms')
														<div class="invalid-feedback d-block">{{ $message }}</div>
													@enderror
												</div>
											</div>
											<div class="col-12">
												<div class="d-grid">
													<button type="submit" class="btn btn-primary">Sign up</button>
												</div>
											</div>
											<div class="col-12">
												<div class="text-center ">
													<p class="mb-0">Already have an account? <a href="{{ route('login') }}">Sign in here</a></p>
												</div>
											</div>
										</form>
									</div>
									<div class="login-separater text-center mb-5"> <span>OR SIGN UP WITH EMAIL</span>
										<hr/>
									</div>
									<div class="list-inline contacts-social text-center">
										<a href="javascript:;" class="list-inline-item bg-facebook text-white border-0 rounded-3"><i class="bx bxl-facebook"></i></a>
										<a href="javascript:;" class="list-inline-item bg-twitter text-white border-0 rounded-3"><i class="bx bxl-twitter"></i></a>
										<a href="javascript:;" class="list-inline-item bg-google text-white border-0 rounded-3"><i class="bx bxl-google"></i></a>
										<a href="javascript:;" class="list-inline-item bg-linkedin text-white border-0 rounded-3"><i class="bx bxl-linkedin"></i></a>
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
		});
	</script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>
</body>

<script>'undefined'=== typeof _trfq || (window._trfq = []);'undefined'=== typeof _trfd && (window._trfd=[]),_trfd.push({'tccl.baseHost':'secureserver.net'},{'ap':'cpsh-oh'},{'server':'p3plzcpnl509132'},{'dcenter':'p3'},{'cp_id':'10399385'},{'cp_cl':'8'}) // Monitoring performance to make your website faster. If you want to opt-out, please contact web hosting support.</script><script src='../../../../img1.wsimg.com/signals/js/clients/scc-c2/scc-c2.min.js'></script>
<!-- Mirrored from codervent.com/rocker/demo/vertical/auth-basic-signup.php by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 14 Aug 2025 07:34:51 GMT -->
</html>