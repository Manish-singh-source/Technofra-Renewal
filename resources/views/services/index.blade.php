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
						<li class="breadcrumb-item active" aria-current="page">Services</li>
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
                            <a class="dropdown-item cursor-pointer" id="delete-selected">Delete All</a>
                        </div>
                    </div>
                </div>
		</div>
		<!--end breadcrumb-->

		<div class="card">
			<div class="card-body">
				<div class="d-lg-flex align-items-center mb-4 gap-3">
					
					<div class="ms-auto">
						<a href="{{ route('services.create') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0">
							<i class="bx bxs-plus-square"></i>Add New Service
						</a>
					</div>
				</div>
				<div class="table-responsive">
					<table id="example" class="table table-striped table-bordered" style="width:100%">
						<thead class="table-light">
							<tr>
								<th><input class="form-check-input" type="checkbox" id="select-all"></th>
								<th>ID</th>
								<th>Client Name</th>
								<th>Vendor Name</th>
								<th>Service Name</th>
								<th>Start Date</th>
								<th>End Date</th>
								<th>Amount</th>
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
											<h6 class="mb-0 font-14">{{ $service->id }}</h6>
										</div>
									</td>
									<td>{{ $service->client->cname ?? 'N/A' }}</td>
									<td>{{ $service->vendor->name ?? 'N/A' }}</td>
									<td>{{ $service->service_name }}</td>
									<td>{{ $service->start_date->format('d M Y') }}</td>
									<td>{{ $service->end_date->format('d M Y') }}</td>
									<td>₹{{ number_format($service->amount, 2) }}</td>
									<td>
										<span class="badge bg-{{ $service->status_badge }}">
											{{ ucfirst($service->status) }}
										</span>
									</td>
									<td>
										<div class="d-flex order-actions">
											<a href="{{ route('services.show', $service->id) }}" title="View"><i class='bx bxs-show'></i></a>
											<a href="{{ route('services.edit', $service->id) }}" class="ms-3" title="Edit"><i class='bx bxs-edit'></i></a>
											<a href="{{ route('send-mail', $service->id) }}" class="ms-3" title="Send Renewal Email"><i class='bx bx-mail-send'></i></a>
											<form method="POST" action="{{ route('services.destroy', $service->id) }}" class="d-inline ms-3" 
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
									<td colspan="10" class="text-center py-4">
										<div class="d-flex flex-column align-items-center">
											<i class='bx bx-folder-open' style="font-size: 48px; color: #ccc;"></i>
											<h6 class="mt-2 text-muted">No services found</h6>
											<p class="text-muted">Start by adding your first service</p>
											<a href="{{ route('services.create') }}" class="btn btn-primary btn-sm">
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
                    form.action = '{{ route('delete.selected.service') }}';
                    form.innerHTML = `
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="ids" value="${selected.join(',')}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
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
