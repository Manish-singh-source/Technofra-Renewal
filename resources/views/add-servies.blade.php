@extends('/layout/master')
@section('content')
<!--start page wrapper -->
<div class="page-wrapper">
	<div class="page-content">


		<!--start stepper one-->

		<h6 class="text-uppercase">Servies Form</h6>
		<hr>
		<div id="stepper1" class="bs-stepper">
			<div class="card mb-4">
    <div class="card-body p-4">
        <h5 class="mb-4">Add Services</h5>
        <form id="serviceForm" class="row g-3">
            <div class="col-md-6">
                <label for="clientName" class="form-label">Client Name</label>
                <select class="form-select" id="clientName">
                    <option value="">Select Client</option>
                    <option>Manish</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="vendorName" class="form-label">Vendor Name</label>
                <select class="form-select" id="vendorName">
                    <option value="">Select Vendor</option>
                    <option>Manish</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="serviceType" class="form-label">Service Type</label>
                <select class="form-select" id="serviceType">
                    <option value="">Select Service</option>
                    <option>Domain</option>
                    <option>Email</option>
                    <option>Hosting</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="serviceDetails" class="form-label">Service Details</label>
                <input type="text" class="form-control" id="id="serviceDetails"" placeholder="Service Details">
            </div>

            <div class="col-md-6">
                <label for="purchaseDate" class="form-label">Purchase Date</label>
                <input type="date" class="form-control" id="purchaseDate">
            </div>

            <div class="col-md-6">
                <label for="renewalDate" class="form-label">Renewal Date</label>
                <input type="date" class="form-control" id="renewalDate">
            </div>

            <div class="col-md-6">
                <label for="expiryDate" class="form-label">Expiry Date</label>
                <input type="date" class="form-control" id="expiryDate">
            </div>

            <div class="col-md-6">
                <label for="renewalCost" class="form-label">Renewal Cost</label>
                <input type="number" class="form-control" id="renewalCost" placeholder="Renewal Cost">
            </div>

            <div class="col-md-6">
                <label for="paymentStatus" class="form-label">Payment Status</label>
                <select class="form-select" id="paymentStatus">
                    <option selected disabled>Choose...</option>
                    <option value="Paid">Paid</option>
                    <option value="Pending">Pending</option>
                    <option value="Overdue">Overdue</option>
                </select>
            </div>

            <div class="col-md-12">
                <div class="justify-content-end d-md-flex d-grid align-items-center gap-3">
                    <button type="submit" class="btn btn-primary px-4">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- New Card to show table -->
<div class="card">
    <div class="card-body p-4">
        <h5 class="mb-4">Services Table</h5>
        <div class="table-responsive">
            <table class="table table-bordered" id="servicesTable">
                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Vendor Name</th>
                        <th>Service Type</th>
                        <th>Service Details</th>
                        <th>Purchase Date</th>
                        <th>Renewal Date</th>
                        <th>Expiry Date</th>
                        <th>Renewal Cost</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be appended here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const form = document.getElementById('serviceForm');
    const tableBody = document.querySelector('#servicesTable tbody');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const clientName = document.getElementById('clientName').value;
        const vendorName = document.getElementById('vendorName').value;
        const serviceType = document.getElementById('serviceType').value;
        const serviceDetails = document.getElementById('serviceDetails').value;
        const purchaseDate = document.getElementById('purchaseDate').value;
        const renewalDate = document.getElementById('renewalDate').value;
        const expiryDate = document.getElementById('expiryDate').value;
        const renewalCost = document.getElementById('renewalCost').value;
        const paymentStatus = document.getElementById('paymentStatus').value;

        const newRow = `
            <tr>
                <td>${clientName}</td>
                <td>${vendorName}</td>
                <td>${serviceType}</td>
                <td>${serviceDetails}</td>
                <td>${purchaseDate}</td>
                <td>${renewalDate}</td>
                <td>${expiryDate}</td>
                <td>${renewalCost}</td>
                <td>${paymentStatus}</td>
            </tr>
        `;

        tableBody.insertAdjacentHTML('beforeend', newRow);

        form.reset(); // Clear form after adding
    });
</script>

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