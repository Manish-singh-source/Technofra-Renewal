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

		@auth
			<div class="row mb-3">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<h5 class="card-title">Welcome, {{ Auth::user()->name }}!</h5>
							<p class="card-text">You are successfully logged in to the Technofra Renewal Master dashboard.</p>
						</div>
					</div>
				</div>
			</div>
		@endauth

		<!-- Renewal Statistics Cards -->
		<div class="row">
			<div class="col-lg-4">
				<div class="card radius-10 border-start border-0 border-4 border-info">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div>
								<p class="mb-0 text-secondary">Total Servies</p>
								<h4 class="my-1 text-info">{{ $totalRenewals ?? 0 }}</h4>
								<p class="mb-0 font-13">All services in system</p>
							</div>
							<div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto">
								<i class='bx bx-list-ul'></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="card radius-10 border-start border-0 border-4 border-warning">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div>
								<p class="mb-0 text-secondary">Renewals Due This Week</p>
								<h4 class="my-1 text-warning">{{ $renewalsDueThisWeek ?? 0 }}</h4>
								<p class="mb-0 font-13">Expiring within 7 days</p>
							</div>
							<div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto">
								<i class='bx bx-time-five'></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="card radius-10 border-start border-0 border-4 border-danger">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div>
								<p class="mb-0 text-secondary">Overdue Renewals</p>
								<h4 class="my-1 text-danger">{{ $overdueRenewals ?? 0 }}</h4>
								<p class="mb-0 font-13">Already expired</p>
							</div>
							<div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
								<i class='bx bx-error'></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--end row-->



		<!-- Critical Renewals Table (Overdue + Upcoming) -->
		<div class="card radius-10 mt-4">
			<div class="card-header">
				<div class="d-flex align-items-center">
					<div>
						<h6 class="mb-0">Upcoming Renewals</h6>
						<p class="mb-0 text-muted font-13">Overdue services and services expiring within the next 5 days</p>
					</div>
					<div class="ms-auto d-flex align-items-center gap-2">
						<span class="badge bg-danger"><p class="mb-0 p-2">{{ $overdueRenewals ?? 0 }} Overdue</p></span>
						<a href="{{ route('services.index') }}" class="btn btn-primary btn-sm">
							<i class="bx bx-list-ul"></i> View All Services
						</a>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table id="example" class="table table-striped table-bordered" style="width:100%">
						<thead class="table-light">
							<tr>
								<th>Priority</th>
								<th>Service ID</th>
								<th>Client Name</th>
								<th>Vendor Name</th>
								<th>Service Name</th>
								<th>Start Date</th>
								<th>Expiry Date</th>
								<th>Status</th>
								<th>Billing Date</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							@forelse($criticalRenewals as $service)
								@php
									$today = \Carbon\Carbon::today();
									$daysLeft = $today->diffInDays($service->end_date, false);
									$isOverdue = $service->end_date < $today;

									if ($isOverdue) {
										$urgencyClass = 'text-danger';
										$priorityBadge = 'bg-danger';
										$priorityText = 'OVERDUE';
										$statusText = abs($daysLeft) . ' days overdue';
									} else {
										$urgencyClass = $daysLeft <= 1 ? 'text-danger' : ($daysLeft <= 3 ? 'text-warning' : 'text-info');
										$priorityBadge = $daysLeft <= 1 ? 'bg-danger' : ($daysLeft <= 3 ? 'bg-warning' : 'bg-info');
										$priorityText = $daysLeft <= 1 ? 'URGENT' : ($daysLeft <= 3 ? 'HIGH' : 'MEDIUM');

										if ($daysLeft == 0) {
											$statusText = 'Today';
										} elseif ($daysLeft == 1) {
											$statusText = 'Tomorrow';
										} else {
											$statusText = $daysLeft . ' days left';
										}
									}
								@endphp
								<tr class="{{ $isOverdue ? 'table-danger' : '' }}">
									<td>
										<span class="badge {{ $priorityBadge }} font-11">
											{{ $priorityText }}
										</span>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<h6 class="mb-0 font-14">#{{ $service->id }}</h6>
										</div>
									</td>
									<td>{{ $service->client->cname ?? 'N/A' }}</td>
									<td>{{ $service->vendor->name ?? 'N/A' }}</td>
									<td>{{ $service->service_name }}</td>
									<td>{{ $service->start_date->format('d M Y') }}</td>
									<td class="{{ $urgencyClass }}">
										<strong>{{ $service->end_date->format('d M Y') }}</strong>
										<br>
										<small class="{{ $urgencyClass }}">{{ $statusText }}</small>
									</td>
									<td>
										@if($isOverdue)
											<span class="badge bg-danger">Expired</span>
										@else
											<span class="badge bg-{{ $service->status_badge }}">
												{{ ucfirst($service->status) }}
											</span>
										@endif
									</td>
									<td>{{ $service->billing_date->format('d M Y') }}</td>
									<td>
										<div class="d-flex order-actions">
											<a href="{{ route('services.show', $service->id) }}" title="View">
												<i class='bx bxs-show'></i>
											</a>
											<a href="{{ route('services.edit', $service->id) }}" class="ms-2" title="Edit">
												<i class='bx bxs-edit'></i>
											</a>
											<a href="{{ route('send-mail', $service->id) }}" class="ms-2 text-primary" title="Send Renewal Email">
												<i class='bx bx-mail-send'></i>
											</a>
											@if($isOverdue)
												<a href="{{ route('services.edit', $service->id) }}" class="ms-2 text-success" title="Renew Service">
													<i class='bx bx-refresh'></i>
												</a>
											@endif
										</div>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="10" class="text-center py-4">
										<div class="d-flex flex-column align-items-center">
											<i class='bx bx-calendar-check' style="font-size: 48px; color: #28a745;"></i>
											<h6 class="mt-2 text-success">Excellent! No critical renewals</h6>
											<p class="text-muted">No overdue services and no renewals due in the next 5 days</p>
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
	<!--Start Back To Top Button-->
	<a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
	<!--End Back To Top Button-->
	@endsection