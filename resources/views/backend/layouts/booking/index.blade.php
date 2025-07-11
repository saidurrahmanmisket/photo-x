@extends('backend.app')
@section('title', 'Bookings')
@section('header_title', 'Booking Management')

@section('content')
<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="bg-white p-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Booking Management</h4>
                        <div>
                            <a href="{{ route('admin.bookings.settings') }}" class="btn btn-info me-2">
                                <i class="bi bi-gear"></i> Settings
                            </a>
                            <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus"></i> Create Booking
                            </a>
                        </div>
                    </div>
                    <div class="table-wrapper table-responsive mt-5">
                        <table id="data-table" class="table table-bordered mt-5">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Kiosk</th>
                                    <th>Payment Type</th>
                                    <th>Amount</th>
                                    <th>Print Info</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Dynamic Data --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this booking? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script type="text/javascript">
        $(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                }
            });

            let dTable;
            if (!$.fn.DataTable.isDataTable('#data-table')) {
                dTable = $('#data-table').DataTable({
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
                        url: "{{ route('admin.bookings.index') }}",
                        type: "get",
                    },

                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'user',
                            name: 'user'
                        },
                        {
                            data: 'kiosk',
                            name: 'kiosk'
                        },
                        {
                            data: 'payment_type',
                            name: 'payment_type'
                        },
                        {
                            data: 'amount',
                            name: 'amount'
                        },
                        {
                            data: 'print_info',
                            name: 'print_info'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
            } else {
                dTable = $('#data-table').DataTable();
            }

            // Delete confirmation
            window.showDeleteConfirm = function(id) {
                $('#deleteModal').modal('show');
                $('#confirmDelete').off('click').on('click', function() {
                    $.ajax({
                        url: "{{ url('admin/bookings/destroy') }}/" + id,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#deleteModal').modal('hide');
                                dTable.ajax.reload();
                                toastr.success(response.message);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            toastr.error('An error occurred while deleting the booking.');
                        }
                    });
                });
            };
        });
    </script>
@endpush 