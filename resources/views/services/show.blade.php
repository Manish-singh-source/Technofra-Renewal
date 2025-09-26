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
						<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
						<li class="breadcrumb-item"><a href="{{ route('services.index') }}">Services</a></li>
						<li class="breadcrumb-item active" aria-current="page">Service Details</li>
					</ol>
				</nav>
			</div>
		</div>
		<!--end breadcrumb-->

		<div class="container">
			<div class="main-body">
				<div class="row">
					<div class="col-lg-12">
						<div class="card">
							<div class="card-header d-flex justify-content-between align-items-center">
								<h5 class="mb-0">Service Details</h5>
								<div>
									<a href="{{ route('services.edit', $service->id) }}" class="btn btn-primary btn-sm">
										<i class="bx bx-edit"></i> Edit
									</a>
									<a href="{{ route('services.index') }}" class="btn btn-secondary btn-sm">
										<i class="bx bx-arrow-back"></i> Back to List
									</a>
								</div>
							</div>
							<div class="card-body">
								<ul class="list-group">
									<li class="list-group-item d-flex justify-content-between">
										<b>Service ID:</b>
										<p class="mb-0">{{ $service->id }}</p>
									</li>
									<li class="list-group-item d-flex justify-content-between">
										<b>Client Name:</b>
										<p class="mb-0">{{ $service->client->cname ?? 'N/A' }}</p>
									</li>
									<li class="list-group-item d-flex justify-content-between">
										<b>Client Email:</b>
										<p class="mb-0">{{ $service->client->email ?? 'N/A' }}</p>
									</li>
									<li class="list-group-item d-flex justify-content-between">
										<b>Vendor Name:</b>
										<p class="mb-0">{{ $service->vendor->name ?? 'N/A' }}</p>
									</li>
									<li class="list-group-item d-flex justify-content-between">
										<b>Vendor Email:</b>
										<p class="mb-0">{{ $service->vendor->email ?? 'N/A' }}</p>
									</li>
									<li class="list-group-item d-flex justify-content-between">
										<b>Service Name:</b>
										<p class="mb-0">{{ $service->service_name }}</p>
									</li>
									@if($service->service_details)
									<li class="list-group-item">
										<b>Service Details:</b>
										<div class="mt-2">
											{!! $service->service_details !!}
										</div>
									</li>
									@endif
									<li class="list-group-item d-flex justify-content-between">
										<b>Start Date:</b>
										<p class="mb-0">{{ $service->start_date->format('d M Y') }}</p>
									</li>
									<li class="list-group-item d-flex justify-content-between">
										<b>End Date:</b>
										<p class="mb-0">{{ $service->end_date->format('d M Y') }}</p>
									</li>
									<li class="list-group-item d-flex justify-content-between">
										<b>Duration:</b>
										<p class="mb-0">{{ $service->start_date->diffInDays($service->end_date) + 1 }} days</p>
									</li>
									<li class="list-group-item d-flex justify-content-between">
										<b>Billing Date:</b>
										<p class="mb-0">{{ $service->billing_date->format('d M Y') }}</p>
									</li>
									<li class="list-group-item d-flex justify-content-between">
										<b>Status:</b>
										<p class="mb-0">
											<span class="badge bg-{{ $service->status_badge }}">
												{{ ucfirst($service->status) }}
											</span>
										</p>
									</li>
									<li class="list-group-item d-flex justify-content-between">
										<b>Created At:</b>
										<p class="mb-0">{{ $service->created_at->format('d M Y, h:i A') }}</p>
									</li>
									<li class="list-group-item d-flex justify-content-between">
										<b>Last Updated:</b>
										<p class="mb-0">{{ $service->updated_at->format('d M Y, h:i A') }}</p>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--end page wrapper -->
<!--start overlay-->
<div class="overlay toggle-icon"></div>
<!--end overlay-->
<!--Start Back To Top Button-->
<a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
<!--End Back To Top Button-->
@endsection
