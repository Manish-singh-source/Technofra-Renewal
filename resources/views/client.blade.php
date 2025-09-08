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

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
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
                            <li class="breadcrumb-item active" aria-current="page">Client</li>
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

                        <div class="ms-auto d-flex gap-2">
                            <div class="btn-group">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-upload"></i> Bulk Upload
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                                        <i class="bx bx-upload"></i> Upload Excel File
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('client.download-template') }}">
                                        <i class="bx bx-download"></i> Download Template
                                    </a></li>
                                </ul>
                            </div>
                            <a href="{{ route('add-client') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0">
                                <i class="bx bxs-plus-square"></i>Add New Client
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th> <input class="form-check-input" type="checkbox" id="select-all"></th>
                                    <th>ID</th>
                                    <th>Client Name</th>
                                    <th>Company Name</th>
                                    <th>Email ID</th>
                                    <th>Contact No</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clients as $client)
                                    <tr>
                                        <td> <input class="form-check-input row-checkbox" type="checkbox" name="ids[]"
                                                    value="{{ $client->id }}"></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <h6 class="mb-0 font-14">{{ $client->id }}</h6>
                                            </div>
                                        </td>
                                        <td>{{ $client->cname }}</td>
                                        <td>{{ $client->coname }}</td>
                                        <td>{{ $client->email }}</td>
                                        <td>{{ $client->phone }}</td>
                                        <td>
                                            <div class="form-switch form-check-success">
                                                <input class="form-check-input status-switch43" type="checkbox"
                                                    role="switch" data-client-id="{{ $client->id }}"
                                                    {{ $client->status == 1 ? 'checked' : '' }}>
                                            </div>

                                        </td>
                                        <td>
                                            <div class="d-flex order-actions">
                                                <a href="{{ route('client.view', $client->id) }}"><i
                                                        class='bx bxs-show'></i></a>
                                                <a href="{{ route('client.edit', $client->id) }}" class="ms-2"><i
                                                        class='bx bxs-edit'></i></a>
                                                <form action="{{ route('client.delete', $client->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure?')" class="ms-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a>
                                                        <button type="submit" style="border: none;"><i
                                                                class='bx bxs-trash'></i></button>
                                                    </a>
                                                </form>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach


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
                    form.action = '{{ route('delete.selected.client') }}';
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

    <!-- Bulk Upload Modal -->
    <div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-labelledby="bulkUploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkUploadModalLabel">Bulk Upload Clients</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('client.bulk-upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">Choose Excel File</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                            <div class="form-text">
                                Supported formats: .xlsx, .xls, .csv (Max size: 2MB)
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <h6><i class="bx bx-info-circle"></i> Instructions:</h6>
                            <ul class="mb-0">
                                <li>Download the template file first</li>
                                <li>Fill in your client data following the template format</li>
                                <li>Required columns: client_name, company_name, email, phone</li>
                                <li>Optional columns: address, status (1 for active, 0 for inactive)</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <a href="{{ route('client.download-template') }}" class="btn btn-info">
                            <i class="bx bx-download"></i> Download Template
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bx bx-upload"></i> Upload File
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--end page wrapper -->
    <!--start overlay-->
    <div class="overlay toggle-icon"></div>
    <!--end overlay-->
    <!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
@endsection
