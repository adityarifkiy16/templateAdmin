@extends('layouts.app')

@section('title', 'Role Page')

@section('header-title')
<ion-icon name="lock-open-outline"></ion-icon> Role
@endsection

@section('breadcrumb-title', 'Role')

@section('breadcrumb-sub', 'Master Role')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Role Data</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table id="example2" class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 0.5rem;">No</th>
                            <th>Role</th>
                            <th style="text-align: end;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(function() {
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "order": [],
            serverSide: true,
            language: {
                searchPlaceholder: 'search...',
                'search': '',
                paginate: {
                    next: '<i class="fas fa-arrow-right"></i>',
                    previous: '<i class="fas fa-arrow-left"></i>'
                }
            },
            ajax: {
                url: "{{url('/roles')}}",
                type: "GET",
                dataSrc: function(response) {
                    return response.data;
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: true
                },
                {
                    data: 'name'
                },
                {
                    data: null,
                    orderable: false,
                    searchable: true,
                    render: function(data) {
                        return `
                        <div class="d-flex flex-row justify-content-end align-items-end">
                            <a href="#"><button type="button" class="btn btn-primary mx-2"><i class="fas fa-pencil-alt" title="Edit"></i></button></a>
                            <a href="{{url('/roles/${data.id}')}}"><button type="button" class="btn btn-warning mx-2 text-light"><i class="fas fa-eye" title="Detail"></i></button></a>
                            <a href="#"><button type="button" class="btn btn-danger mx-2"><i class="fas fa-trash-alt" title="Delete"></i></button></a>
                        </div>`;
                    }
                }
            ],
            "dom": '<"d-flex justify-content-between align-items-center mb-2"<"d-flex align-items-center"B><f>>tip',
            "buttons": [{
                    text: 'Tambah <i class="fas fa-plus-square"></i>',
                    className: 'btn btn-outline-primary mr-2',
                    action: function() {
                        window.location.href = "{{ route('roles.create') }}";
                    }

                },
                {
                    text: 'Bulk Delete <i class="fas fa-minus-square"></i>',
                    className: 'btn btn-outline-danger',
                    action: function() {
                        // Add bulk delete functionality here
                    }
                }
            ]
        });
    });
    // removing btn-secondary
    $.fn.dataTable.Buttons.defaults.dom.button.className = 'btn';

    $(document).ready(function() {
        $('.dt-buttons').removeClass("dt-buttons");
        $('.btn-group').removeClass("btn-group");
    });
</script>
@endpush