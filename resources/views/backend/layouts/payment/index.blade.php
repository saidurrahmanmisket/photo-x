@extends('backend.app')
@section('title', 'Payments')
@section('header_title', 'Payment Management')

@section('content')
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="bg-white p-5">
                        <div class="table-wrapper table-responsive mt-5">
                            <table id="data-table" class="table table-bordered mt-5">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Kiosk</th>
                                        <th>Photo</th>
                                        <th>Amount (BDT)</th>
                                        <th>Status</th>
                                        <th>Gateway</th>
                                        <th>Transaction ID</th>
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

    @push('script')
        <script type="text/javascript">
            $(function() {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    }
                });

                if (!$.fn.DataTable.isDataTable('#data-table')) {
                    let dTable = $('#data-table').DataTable({
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
                            url: "{{ route('admin.payments.index') }}",
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
                                name: 'user',
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: 'kiosk',
                                name: 'kiosk',
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: 'photo',
                                name: 'photo',
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: 'amount',
                                name: 'amount',
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: 'status',
                                name: 'status',
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: 'payment_gateway',
                                name: 'payment_gateway',
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: 'transaction_id',
                                name: 'transaction_id',
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: 'created_at',
                                name: 'created_at',
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false
                            },
                        ],
                    });
                }
            });
        </script>
    @endpush
@endsection

