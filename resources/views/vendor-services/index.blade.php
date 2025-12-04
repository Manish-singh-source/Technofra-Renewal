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
						<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
						<li class="breadcrumb-item active" aria-current="page">Vendor Renewal Services</li>
					</ol>
				</nav>
			</div>
			 <div class="ms-auto">
                     <div class="btn-group">
                         <button type="button" class="btn btn-primary">Settings</button>
                         <button type="button"
                             class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split"
                             data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
                         </button>
                         <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                             <!-- Dropdown items can be added here if needed -->
                         </div>
                     </div>
                 </div>
		</div>
		<!--end breadcrumb-->

		<div class="card">
			<div class="card-body">
				<!-- Filter Form -->
				<div class="row mb-4">
					<div class="col-12">
						<form method="GET" action="{{ route('vendor-services.index') }}" class="row g-3 align-items-end" id="dateFilterForm">
							<div class="col-md-3">
								<label for="from_date" class="form-label">From Date</label>
								<input type="date" class="form-control" id="from_date" name="from_date"
									   value="{{ request('from_date') }}">
							</div>
							<div class="col-md-3">
								<label for="to_date" class="form-label">To Date</label>
								<input type="date" class="form-control" id="to_date" name="to_date"
									   value="{{ request('to_date') }}">
							</div>
							<div class="col-md-3">
								<button type="submit" class="btn btn-primary">
									<i class="bx bx-search"></i> Filter
								</button>
								<a href="{{ route('vendor-services.index') }}" class="btn btn-outline-secondary ms-2">
									<i class="bx bx-refresh"></i> Clear
								</a>
							</div>
							<div class="col-md-3 text-end">
								<a href="{{ route('vendor-services.create') }}" class="btn btn-primary radius-30">
									<i class="bx bxs-plus-square"></i>Add New Vendor Service
								</a>
							</div>
						</form>
					</div>
				</div>

				<!-- Results Count -->
				<div class="row mb-3">
					<div class="col-12">
						<div class="d-flex justify-content-between align-items-center">
							<div>
								<h6 class="mb-0">
									Showing {{ $services->count() }} vendor service(s)
									@if(request('from_date') || request('to_date'))
										<small class="text-muted">
											(filtered
											@if(request('from_date'))
												from {{ \Carbon\Carbon::parse(request('from_date'))->format('d M Y') }}
											@endif
											@if(request('to_date'))
												to {{ \Carbon\Carbon::parse(request('to_date'))->format('d M Y') }}
											@endif
											)
										</small>
									@endif
								</h6>
							</div>
							<div>
								<button type="button" class="btn btn-danger btn-sm" id="delete-selected">
									<i class="bx bx-trash"></i> Delete Selected
								</button>
							</div>
						</div>
					</div>
				</div>

				<div class="table-responsive">
					<table id="example" class="table table-striped table-bordered" style="width:100%">
						<thead class="table-light">
							<tr>
								<th><input class="form-check-input" type="checkbox" id="select-all"></th>
								<th>ID</th>
								<th>Vendor Name</th>
								<th>Service Name</th>
								<th>Plan Type</th>
								<th>Start Date</th>
								<th>End Date</th>
								<th>Billing Date</th>
								<th>Status</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							@forelse($services as $service)
								<tr>
									<td><input class="form-check-input row-checkbox" type="checkbox" name="ids[]"
                                                     value="{{ $service->id }}"></td>
									<td>
										<div class="d-flex align-items-center">
											<h6 class="mb-0 font-14">{{ $loop->iteration }}</h6>
										</div>
									</td>
									<td>{{ $service->vendor->name ?? 'N/A' }}</td>
									<td>{{ $service->service_name }}</td>
									<td>{{ ucfirst($service->plan_type) }}</td>
									<td>{{ $service->start_date->format('d M Y') }}</td>
									<td>
										<div>
											{{ $service->end_date->format('d M Y') }}
										</div>
										@php
											$daysLeft = \Carbon\Carbon::today()->diffInDays($service->end_date, false);
											$urgencyClass = $daysLeft <= 1 ? 'text-danger' : ($daysLeft <= 3 ? 'text-warning' : 'text-info');
										@endphp
										<small class="{{ $urgencyClass }}">
											<strong>
												@if($daysLeft < 0)
													{{ abs($daysLeft) }} days overdue
												@elseif($daysLeft == 0)
													Expires today
												@elseif($daysLeft == 1)
													Expires tomorrow
												@else
													{{ $daysLeft }} days left
												@endif
											</strong>
										</small>
									</td>
									<td>{{ $service->billing_date ? $service->billing_date->format('d M Y') : 'N/A' }}</td>
									<td>
										<span class="badge bg-{{ $service->status_badge }}">
											{{ ucfirst($service->status) }}
										</span>
									</td>
									<td>
										<div class="d-flex order-actions">
											<a href="{{ route('vendor-services.show', $service->id) }}" title="View"><i class='bx bxs-show'></i></a>
											<a href="{{ route('vendor-services.edit', $service->id) }}" class="ms-3" title="Edit"><i class='bx bxs-edit'></i></a>
											<a href="{{ route('send-mail', $service->id) }}" class="ms-3" title="Send Renewal Email"><i class='bx bx-mail-send'></i></a>
											<form method="POST" action="{{ route('vendor-services.destroy', $service->id) }}" class="d-inline ms-3"
												  onsubmit="return confirm('Are you sure you want to delete this vendor service?')">
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
									<td colspan="10" class="text-center py-4">
										<div class="d-flex flex-column align-items-center">
											<i class='bx bx-folder-open' style="font-size: 48px; color: #ccc;"></i>
											<h6 class="mt-2 text-muted">No vendor services found</h6>
											<p class="text-muted">Start by adding your first vendor service</p>
											<a href="{{ route('vendor-services.create') }}" class="btn btn-primary btn-sm">
												<i class="bx bx-plus"></i> Add Vendor Service
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
<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select All functionality
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.row-checkbox');
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
            });

            // Delete Selected functionality
            document.getElementById('delete-selected').addEventListener('click', function() {
                let selected = [];
                document.querySelectorAll('.row-checkbox:checked').forEach(cb => {
                    selected.push(cb.value);
                });
                if (selected.length === 0) {
                    alert('Please select at least one record.');
                    return;
                }
                if (confirm('Are you sure you want to delete selected records?')) {
                    // Create a form and submit
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('delete.selected.vendor-service') }}';
                    form.innerHTML = `
                        @csrf
                        <input type="hidden" name="ids" value="${selected.join(',')}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });

            // Dynamic Date Range Filtering
            const fromDateInput = document.getElementById('from_date');
            const toDateInput = document.getElementById('to_date');
            const dateFilterForm = document.getElementById('dateFilterForm');

            // Add event listeners for dynamic filtering
            fromDateInput.addEventListener('change', function() {
                dateFilterForm.submit();
            });

            toDateInput.addEventListener('change', function() {
                dateFilterForm.submit();
            });
        });
    </script>
<!--end page wrapper -->
<!--start overlay-->
<div class="overlay toggle-icon"></div>
<!--end overlay-->
<!--Start Back To Top Button-->
<a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
@endsection