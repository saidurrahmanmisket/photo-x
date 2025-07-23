@extends('backend.app')
@section('title', 'Themes')
@section('header_title', 'Theme Management')

@section('content')
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="bg-white p-5">
                        <div class="d-flex justify-content-end mb-5">
                            <a href="{{ route('admin.themes.create') }}" class="btn btn-primary">Add Theme</a>
                        </div>
                        <div class="table-wrapper table-responsive mt-5">
                            <table id="data-table" class="table table-bordered mt-5">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Image</th>
                                        <th>Price</th>
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
                            url: "{{ route('admin.themes.index') }}",
                            type: "get",
                        },
                        columns: [
                            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                            { data: 'name', name: 'name' },
                            { data: 'image', name: 'image', orderable: false, searchable: false },
                            { data: 'price', name: 'price' },
                            { data: 'action', name: 'action', orderable: false, searchable: false },
                        ]
                    });
                }
            });
        </script>
    @endpush
@endsection 