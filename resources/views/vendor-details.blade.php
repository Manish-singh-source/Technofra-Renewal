@extends('/layout/master')
@section('content')
<!--start page wrapper -->
<div class="page-wrapper">
	<div class="page-content">
		<!--breadcrumb-->
		<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
			<div class="ps-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0">
						<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">Vendor details</li>
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
			<div class="card-header d-flex justify-content-between align-items-center">
				<h5 class="mb-0">Vendor Details</h5>
				<div>
					<a href="{{ route('vendors.edit', $vendor->id) }}" class="btn btn-primary btn-sm">
						<i class="bx bx-edit"></i> Edit
					</a>
					<a href="{{ route('vendors.index') }}" class="btn btn-secondary btn-sm">
						<i class="bx bx-arrow-back"></i> Back to List
					</a>
				</div>
			</div>
			<div class="card-body">
				<ul class="list-group">
					<li class="list-group-item d-flex justify-content-between">
						<b>Vendor ID:</b>
						<p class="mb-0">{{ $vendor->id }}</p>
					</li>
					<li class="list-group-item d-flex justify-content-between">
						<b>Vendor Name:</b>
						<p class="mb-0">{{ $vendor->name }}</p>
					</li>
					<li class="list-group-item d-flex justify-content-between">
						<b>Email ID:</b>
						<p class="mb-0">{{ $vendor->email }}</p>
					</li>
					<li class="list-group-item d-flex justify-content-between">
						<b>Contact No:</b>
						<p class="mb-0">{{ $vendor->phone }}</p>
					</li>
					@if($vendor->address)
						<li class="list-group-item d-flex justify-content-between">
							<b>Address:</b>
							<p class="mb-0">{{ $vendor->address }}</p>
						</li>
					@endif
					<li class="list-group-item d-flex justify-content-between">
						<b>Created At:</b>
						<p class="mb-0">{{ $vendor->created_at->format('d M Y, h:i A') }}</p>
					</li>
					<li class="list-group-item d-flex justify-content-between">
						<b>Last Updated:</b>
						<p class="mb-0">{{ $vendor->updated_at->format('d M Y, h:i A') }}</p>
					</li>
				</ul>
			</div>
		</div>

		<!-- Vendor Services Section -->
		<div class="card mt-4">
			<div class="card-header">
				<h5 class="mb-0">Vendor Services</h5>
			</div>
			<div class="card-body">
				<div class="d-lg-flex align-items-center mb-4 gap-3">
					<div class="position-relative">
						<input type="text" class="form-control ps-5 radius-30" placeholder="Search Services">
						<span class="position-absolute top-50 product-show translate-middle-y"><i class="bx bx-search"></i></span>
					</div>
					<div class="ms-auto">
						<a href="{{ route('services.create') }}?vendor_id={{ $vendor->id }}" class="btn btn-primary radius-30 mt-2 mt-lg-0">
							<i class="bx bxs-plus-square"></i>Add New Service
						</a>
					</div>
				</div>

				<div class="table-responsive">
					<table class="table mb-0">
						<thead class="table-light">
							<tr>
								<th><input class="form-check-input me-3" type="checkbox" value="" aria-label="..."></th>
								<th>Service ID</th>
								<th>Client Name</th>
								<th>Service Name</th>
								<th>Start Date</th>
								<th>End Date</th>
								<th>Billing Date</th>
								<th>Status</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							@forelse($vendor->services as $service)
								<tr>
									<td><input class="form-check-input me-3" type="checkbox" value="{{ $service->id }}" aria-label="..."></td>
									<td>
										<div class="d-flex align-items-center">
											<h6 class="mb-0 font-14">{{ $service->id }}</h6>
										</div>
									</td>
									<td>{{ $service->client->cname ?? 'N/A' }}</td>
									<td>{{ $service->service_name }}</td>
									<td>{{ $service->start_date->format('d M Y') }}</td>
									<td>{{ $service->end_date->format('d M Y') }}</td>
									<td>{{ $service->billing_date->format('d M Y') }}</td>
									<td>
										<span class="badge bg-{{ $service->status_badge }}">
											{{ ucfirst($service->status) }}
										</span>
									</td>
									<td>
										<div class="d-flex order-actions">
											<a href="{{ route('services.show', $service->id) }}" title="View"><i class='bx bxs-show'></i></a>
											<a href="{{ route('services.edit', $service->id) }}" class="ms-2" title="Edit"><i class='bx bxs-edit'></i></a>
											<form method="POST" action="{{ route('services.destroy', $service->id) }}" class="d-inline ms-2"
												  onsubmit="return confirm('Are you sure you want to delete this service?')">
												@csrf
												@method('DELETE')
												<button type="submit" class="btn btn-link p-0 text-danger" title="Delete">
													<i class='bx bxs-trash'></i>
												</button>
											</form>
										</div>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="9" class="text-center py-4">
										<div class="d-flex flex-column align-items-center">
											<i class='bx bx-folder-open' style="font-size: 48px; color: #ccc;"></i>
											<h6 class="mt-2 text-muted">No services found for this vendor</h6>
											<p class="text-muted">Start by adding the first service</p>
											<a href="{{ route('services.create') }}?vendor_id={{ $vendor->id }}" class="btn btn-primary btn-sm">
												<i class="bx bx-plus"></i> Add Service
											</a>
										</div>
									</td>
								</tr>
							@endforelse
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