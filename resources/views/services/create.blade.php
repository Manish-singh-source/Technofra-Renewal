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
		<h6 class="text-uppercase">Service Form</h6>
		<hr>
		<div id="stepper1" class="bs-stepper">
			<div class="card">
				<div class="card-body p-4">
					<h5 class="mb-4">Add Services</h5>
					<form class="row g-3" method="POST" action="{{ route('services.store') }}" id="serviceForm">
						@csrf
						
						<!-- Client Selection -->
						<div class="col-md-12">
							<label for="client_id" class="form-label">Select Client <span class="text-danger">*</span></label>
							<select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>
								<option value="">Choose a client...</option>
								@foreach($clients as $client)
									<option value="{{ $client->id }}" {{ (old('client_id', $selectedClientId) == $client->id) ? 'selected' : '' }}>
										{{ $client->cname }}
									</option>
								@endforeach
							</select>
							@error('client_id')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>

						<!-- Services Container -->
						<div class="col-md-12">
							<div class="d-flex justify-content-between align-items-center mb-3">
								<h6 class="mb-0">Services</h6>
								<button type="button" class="btn btn-success btn-sm" id="addService">
									<i class="bx bx-plus"></i> Add Another Service
								</button>
							</div>
							
							<div id="servicesContainer">
								<!-- First service row -->
								<div class="service-row border rounded p-3 mb-3">
									<div class="row g-3">
										<div class="col-md-6">
											<label class="form-label">Vendor <span class="text-danger">*</span></label>
											<select class="form-select" name="services[0][vendor_id]" required>
												<option value="">Choose a vendor...</option>
												@foreach($vendors as $vendor)
													<option value="{{ $vendor->id }}" {{ (old('vendor_id', $selectedVendorId) == $vendor->id) ? 'selected' : '' }}>
														{{ $vendor->name }}
													</option>
												@endforeach
											</select>
										</div>
										<div class="col-md-6">
											<label class="form-label">Service Name <span class="text-danger">*</span></label>
											<input type="text" class="form-control" name="services[0][service_name]"
												   placeholder="Enter service name" required>
										</div>
										<div class="col-md-12">
											<label class="form-label">Service Details</label>
											<textarea class="form-control ckeditor" name="services[0][service_details]"
													  id="service_details_0" placeholder="Enter detailed description of the service..." rows="4"></textarea>
										</div>
										<div class="col-md-3">
											<label class="form-label">Start Date <span class="text-danger">*</span></label>
											<input type="date" class="form-control" name="services[0][start_date]" required>
										</div>
										<div class="col-md-3">
											<label class="form-label">End Date <span class="text-danger">*</span></label>
											<input type="date" class="form-control" name="services[0][end_date]" required>
										</div>
										<div class="col-md-3">
											<label class="form-label">Billing Date <span class="text-danger">*</span></label>
											<input type="date" class="form-control" name="services[0][billing_date]" required>
										</div>
										<div class="col-md-3">
											<label class="form-label">Status <span class="text-danger">*</span></label>
											<select class="form-select" name="services[0][status]" required>
												<option value="active">Active</option>
												<option value="inactive">Inactive</option>
												<option value="pending">Pending</option>
												<option value="expired">Expired</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-12">
							<div class="d-md-flex d-grid align-items-center gap-3">
								<button type="submit" class="btn btn-primary px-4">Save Services</button>
								<a href="{{ route('services.index') }}" class="btn btn-light px-4">Cancel</a>
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
<!--Start Back To Top Button-->
<a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
<!--End Back To Top Button-->

<!-- CKEditor CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let serviceIndex = 1;
    let editorInstances = {};

    // Initialize CKEditor for existing editors
    initializeCKEditor();

    // Get vendor options for dynamic rows
    const vendorOptions = `
        @foreach($vendors as $vendor)
            <option value="{{ $vendor->id }}" {{ (old('vendor_id', $selectedVendorId) == $vendor->id) ? 'selected' : '' }}>{{ $vendor->name }}</option>
        @endforeach
    `;

    // Function to initialize CKEditor
    function initializeCKEditor() {
        const textarea = document.getElementById('service_details_0');
        if (textarea) {
            ClassicEditor
                .create(textarea, {
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'link', '|',
                        'bulletedList', 'numberedList', '|',
                        'outdent', 'indent', '|',
                        'blockQuote', 'insertTable', '|',
                        'undo', 'redo'
                    ]
                })
                .then(editor => {
                    editorInstances['service_details_0'] = editor;
                })
                .catch(error => {
                    console.error('Error initializing CKEditor:', error);
                });
        }
    }

    document.getElementById('addService').addEventListener('click', function() {
        const container = document.getElementById('servicesContainer');
        const newServiceRow = document.createElement('div');
        newServiceRow.className = 'service-row border rounded p-3 mb-3';
        newServiceRow.innerHTML = `
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Vendor <span class="text-danger">*</span></label>
                    <select class="form-select" name="services[${serviceIndex}][vendor_id]" required>
                        <option value="">Choose a vendor...</option>
                        ${vendorOptions}
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Service Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="services[${serviceIndex}][service_name]"
                           placeholder="Enter service name" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Service Details</label>
                    <textarea class="form-control ckeditor" name="services[${serviceIndex}][service_details]"
                              id="service_details_${serviceIndex}" placeholder="Enter detailed description of the service..." rows="4"></textarea>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="services[${serviceIndex}][start_date]" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="services[${serviceIndex}][end_date]" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Billing Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="services[${serviceIndex}][billing_date]" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select" name="services[${serviceIndex}][status]" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="pending">Pending</option>
                        <option value="expired">Expired</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-danger btn-sm remove-service">
                        <i class="bx bx-trash"></i> Remove Service
                    </button>
                </div>
            </div>
        `;
        
        container.appendChild(newServiceRow);

        // Initialize CKEditor for the new textarea
        const newTextarea = newServiceRow.querySelector('.ckeditor');
        if (newTextarea) {
            const editorId = `service_details_${serviceIndex}`;
            ClassicEditor
                .create(newTextarea, {
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'link', '|',
                        'bulletedList', 'numberedList', '|',
                        'outdent', 'indent', '|',
                        'blockQuote', 'insertTable', '|',
                        'undo', 'redo'
                    ]
                })
                .then(editor => {
                    editorInstances[editorId] = editor;
                })
                .catch(error => {
                    console.error('Error initializing CKEditor:', error);
                });
        }

        serviceIndex++;

        // Add remove functionality
        newServiceRow.querySelector('.remove-service').addEventListener('click', function() {
            // Remove CKEditor instance before removing the element
            const textarea = newServiceRow.querySelector('.ckeditor');
            if (textarea && editorInstances[textarea.id]) {
                editorInstances[textarea.id].destroy();
                delete editorInstances[textarea.id];
            }
            newServiceRow.remove();
        });
    });
});
</script>
@endsection
