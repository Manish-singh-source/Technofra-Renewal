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
                        <div class="position-relative">
                            <input type="text" class="form-control ps-5 radius-30" placeholder="Search Order"> <span
                                class="position-absolute top-50 product-show translate-middle-y"><i
                                    class="bx bx-search"></i></span>
                        </div>
                        <div class="ms-auto"><a href="{{ route('add-client') }}"
                                class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Add New
                                Client</a></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th> <input class="form-check-input" type="checkbox" id="select-all"></th>
                                    <th>Id</th>
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
    <!--end page wrapper -->
    <!--start overlay-->
    <div class="overlay toggle-icon"></div>
    <!--end overlay-->
    <!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
@endsection
