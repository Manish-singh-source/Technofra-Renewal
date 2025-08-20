@extends('/layout/master')
@section('content')
<!--start page wrapper -->
<div class="page-wrapper">
	<div class="page-content">


		<!--start stepper one-->

		<h6 class="text-uppercase">Client Form</h6>
		<hr>
		<div id="stepper1" class="bs-stepper">
			<div class="card">

				<div class="card-body p-4">
					<h5 class="mb-4">Add Client</h5>
					<form action="{{ route('store-client')}}" method="POST" class="row g-3">
						@csrf
						<div class="col-md-6">
							<label for="input1" class="form-label">Client Name
							</label>
							<input type="text" name="cname" class="form-control" placeholder="Client Name">
						</div>
						<div class="col-md-6">
							<label for="input2" class="form-label">Company Name
							</label>
							<input type="text" name="coname" class="form-control" placeholder="Company Name">
						</div>
						<div class="col-md-6">
							<label for="input3" class="form-label">Mobile Number
							</label>
							<input type="text" name="phone" class="form-control" placeholder="Mobile Number">
						</div>
						<div class="col-md-6"> 
							<label for="input4" class="form-label">Email ID</label>
							<input type="email" name="email" class="form-control" placeholder="Email ID">
						</div>
						
						<div class="col-md-12">
							<label for="input11" class="form-label">Address</label>
							<textarea class="form-control" name="address" id="input11" placeholder="Address ..." rows="3"></textarea>
						</div>
						
						<div class="col-md-12">
							<div class="d-md-flex d-grid align-items-center gap-3">
								<button  type="submit" class="btn btn-primary px-4">Submit</button>
								<!-- <button type="button" class="btn btn-light px-4">Reset</button> -->
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!--end stepper one-->






	</div>
</div>
<!--end page wrapper -->
<!--start overlay-->
<div class="overlay toggle-icon"></div>
<!--end overlay-->
<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
<!--End Back To Top Button-->
@endsection