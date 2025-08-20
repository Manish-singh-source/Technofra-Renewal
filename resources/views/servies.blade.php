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

		<!--breadcrumb-->
		<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
			<div class="ps-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0">
						<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">Servies</li>
					</ol>
				</nav>
			</div>
			<div class="ms-auto">
				<div class="btn-group">
					<button type="button" class="btn btn-primary">Settings</button>
					<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
					</button>
					<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> <a class="dropdown-item" href="javascript:;">Action</a>
						<a class="dropdown-item" href="javascript:;">Another action</a>
						<a class="dropdown-item" href="javascript:;">Something else here</a>
						<div class="dropdown-divider"></div> <a class="dropdown-item" href="javascript:;">Separated link</a>
					</div>
				</div>
			</div>
		</div>
		<!--end breadcrumb-->

		<div class="card">
			<div class="card-body">
				<div class="d-lg-flex align-items-center mb-4 gap-3">
					<div class="position-relative">
						<input type="text" class="form-control ps-5 radius-30" placeholder="Search Order"> <span class="position-absolute top-50 product-show translate-middle-y"><i class="bx bx-search"></i></span>
					</div>
					<div class="ms-auto"><a href="{{route('add-servies')}}" class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Add New Servies</a></div>
				</div>
				<div class="table-responsive">
					<table class="table mb-0" id="example">
						<thead class="table-light">
							<tr>
								<th><input class="form-check-input me-3" type="checkbox" value="" aria-label="..."></th>
								<th>Client Name</th>
								<th>vendor Name</th>
								<th>Service Type</th>
								<th>Service Detiles</th>
								<th>PurchaseDate</th>
								<th>Renewal Date</th>
								<th>Expiry Date</th>
								<th>Renewal Cost</th>
								<th>Payment Status</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><input class="form-check-input me-3" type="checkbox" value="" aria-label="..."></td>

								</td>
								<td>Rahul Sharma</td>
								<td>Godaddy</td>
								<td>Domain</td>
								<td>www.technofra.com</td>
								<td>2025-05-10</td>
								<td>2026-05-10</td>
								<td class="text-danger">2026-06-10</td>
								<td>₹500</td>
								<td>Paid</td>
								<td>
									<div class="d-flex order-actions">
										<a href="javascript:;" class=""><i class='bx bxs-edit'></i></a>
										<a href="javascript:;" class="ms-3"><i class='bx bxs-trash'></i></a>
									</div>
								</td>
							</tr>

							<tr>
								<td><input class="form-check-input me-3" type="checkbox" value="" aria-label="..."></td>

								<td>Pooja Mehta</td>
								<td>Hosting Spell</td>
								<td>Cloud Hosting</td>
								<td>AWS Hosting Package</td>
								<td>2025-02-01</td>
								<td>2026-02-01</td>
								<td class="text-danger">2026-03-01</td>
								<td>₹1200</td>
								<td>Pending</td>
								<td>
									<div class="d-flex order-actions">
										<a href="javascript:;" class=""><i class='bx bxs-edit'></i></a>
										<a href="javascript:;" class="ms-3"><i class='bx bxs-trash'></i></a>
									</div>
								</td>
							</tr>

							<tr>
								<td><input class="form-check-input me-3" type="checkbox" value="" aria-label="..."></td>

								<td>Ramesh Patil</td>
								<td>Shivami</td>
								<td>Email</td>
								<td>manish@technofra.com</td>
								<td>2025-01-15</td>
								<td>2026-01-15</td>
								<td class="text-danger">2026-02-15</td>
								<td>₹250</td>
								<td>Paid</td>
								<td>
									<div class="d-flex order-actions">
										<a href="javascript:;" class=""><i class='bx bxs-edit'></i></a>
										<a href="javascript:;" class="ms-3"><i class='bx bxs-trash'></i></a>
									</div>
								</td>
							</tr>


						</tbody>
					</table>
				</div>
			</div>
		</div>


	</div>
</div>
<!--end page wrapper -->
<!--start overlay-->
<div class="overlay toggle-icon"></div>
<!--end overlay-->
<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
@endsection