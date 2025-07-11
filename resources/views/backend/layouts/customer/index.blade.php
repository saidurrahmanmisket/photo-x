@extends('backend.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Customer Management</h4>
                        <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Add New Customer
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="customersTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Avatar</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert will be used for confirmations -->
@endsection

@push('script')
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        }
    });

    if (!$.fn.DataTable.isDataTable('#customersTable')) {
        var table = $('#customersTable').DataTable({
            order: [],
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            processing: true,
            responsive: true,
            serverSide: true,
            language: {
                processing: `<div class="text-center">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                    </div>`
            },
            scroller: {
                loadingIndicator: false
            },
            pagingType: "full_numbers",
            dom: "<'row justify-content-between table-topbar'<'col-md-2 col-sm-4 px-0'l><'col-md-2 col-sm-4 px-0'f>>tipr",
            ajax: {
                url: "{{ route('admin.customers.index') }}",
                type: "get",
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'avatar', name: 'avatar', orderable: false, searchable: false},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'status', name: 'status', orderable: false, searchable: false},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    }

    // Delete confirmation
    window.showDeleteConfirm = function(id) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure you want to delete this customer?',
            text: 'If you delete this, it will be gone forever.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
            if (result.isConfirmed) {
                deleteItem(id);
            }
        });
    };

    // Delete Button
    function deleteItem(id) {
        let url = '{{ route('admin.customers.destroy', ':id') }}';
        let csrfToken = '{{ csrf_token() }}';
        $.ajax({
            type: "POST",
            url: url.replace(':id', id),
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(resp) {
                // Reload DataTable
                $('#customersTable').DataTable().ajax.reload();
                if (resp.success === true) {
                    // show toast message
                    toastr.success(resp.message);
                } else if (resp.errors) {
                    toastr.error(resp.errors[0]);
                } else {
                    toastr.error(resp.message);
                }
            },
            error: function(error) {
                // Handle error
                toastr.error('An error occurred while deleting the customer.');
            }
        });
    }

    // Status Change Confirm Alert
    window.showStatusChangeAlert = function(id) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: 'You want to update the status?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
        }).then((result) => {
            if (result.isConfirmed) {
                statusChange(id);
            }
        });
    };

    // Status Change
    function statusChange(id) {
        let url = '{{ route('admin.customers.status', ':id') }}';
        $.ajax({
            type: "POST",
            url: url.replace(':id', id),
            success: function(resp) {
                // Reload DataTable
                $('#customersTable').DataTable().ajax.reload();
                if (resp.success === true) {
                    // show toast message
                    toastr.success(resp.message);
                } else if (resp.errors) {
                    toastr.error(resp.errors[0]);
                } else {
                    toastr.error(resp.message);
                }
            },
            error: function(error) {
                // Handle error
                toastr.error('An error occurred while changing the status.');
            }
        });
    };
});
</script>
@endpush
