@extends('/layout/master')
@section('content')
<!--start page wrapper -->
<div class="page-wrapper">
	<div class="page-content">
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

		<!--start stepper one-->

		<h6 class="text-uppercase">Vendor Form</h6>
		<hr>
		<div id="stepper1" class="bs-stepper">
			<div class="card">

				<div class="card-body p-4">
					<h5 class="mb-4">{{ isset($vendor) ? 'Edit Vendor' : 'Add Vendor' }}</h5>
					<form class="row g-3" method="POST" action="{{ isset($vendor) ? route('vendors.update', $vendor->id) : route('vendors.store') }}">
						@csrf
						@if(isset($vendor))
							@method('PUT')
						@endif
						<div class="col-md-6">
							<label for="name" class="form-label">Vendor Name <span class="text-danger">*</span></label>
							<input type="text" class="form-control @error('name') is-invalid @enderror"
								   id="name" name="name" value="{{ old('name', isset($vendor) ? $vendor->name : '') }}"
								   placeholder="Enter vendor name" required>
							@error('name')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>
						<div class="col-md-6">
							<label for="email" class="form-label">Email ID <span class="text-danger">*</span></label>
							<input type="email" class="form-control @error('email') is-invalid @enderror"
								   id="email" name="email" value="{{ old('email', isset($vendor) ? $vendor->email : '') }}"
								   placeholder="Enter email address" required>
							@error('email')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>
						<div class="col-md-6">
							<label for="phone" class="form-label">Mobile Number <span class="text-danger">*</span></label>
							<input type="text" class="form-control @error('phone') is-invalid @enderror"
								   id="phone" name="phone" value="{{ old('phone', isset($vendor) ? $vendor->phone : '') }}"
								   placeholder="Enter mobile number" required>
							@error('phone')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>
						<div class="col-md-6">
							<label for="address" class="form-label">Address</label>
							<textarea class="form-control @error('address') is-invalid @enderror"
									  id="address" name="address" placeholder="Enter address (optional)" rows="3">{{ old('address', isset($vendor) ? $vendor->address : '') }}</textarea>
							@error('address')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>
						<div class="col-md-12">
							<div class="d-md-flex d-grid align-items-center gap-3">
								<button type="submit" class="btn btn-primary px-4">
									{{ isset($vendor) ? 'Update Vendor' : 'Add Vendor' }}
								</button>
								<a href="{{ route('vendors.index') }}" class="btn btn-light px-4">Cancel</a>
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