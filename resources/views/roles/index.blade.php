<x-app-layout>
    @section('title', 'Roles')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Roles
                @can('role.create')
                    <a href="{{ route('roles.create') }}" class="btn btn-primary ms-3"><i class="fa fa-plus"></i> Create Role</a>
                @endcan
            </h1>
                <x-breadcrumbs />
        </div>
        </div>
    </div>
    <!-- END Hero -->
    <!-- Page Content -->
    <div class="content">
        <!-- Dynamic Table Responsive -->
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <table class="table table-bordered table-striped table-vcenter" id="roles-table">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 80px;">#</th>
                            <th>Name</th>
                            <th  style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Dynamic Table Responsive -->
    </div>
    <!-- END Page Content -->
    <script>
            $(document).ready(function () {
                var rolesDataTable = $('#roles-table').DataTable({
                    processing: true,
                    serverSide: true,
                    pagingType: "full_numbers",
                    autoWidth: !1,
                    responsive: !0,
                    ajax: "{{ route('roles.index') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false,searchable: false
                        },
                        { data: 'name', name: 'name' },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false },
                    ],
                    order: [[1, 'asc']]
                });

                // Initialize Bootstrap tooltips after DataTable is loaded
                rolesDataTable.on('draw', function () {
                    $('[data-bs-toggle="tooltip"]').tooltip();
                });

                // delete role
                $(document).on('click', '#deleteRole', function() {
                    var id = $(this).attr('data-id');
                    Swal.fire({
                        text: 'Are you sure you want to delete this role?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        customClass: {
                            confirmButton: 'btn btn-primary',
                            cancelButton: 'btn btn-outline-danger ms-2'
                        },
                        buttonsStyling: false
                    }).then(function (result) {
                        if (result.value) {
                            var csrfToken = $('meta[name="csrf-token"]').attr('content');
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            });
                            $.ajax({
                                url: "{{ route('roles.destroy', ':id') }}".replace(':id', id),
                                type: 'DELETE',
                                success: function (response) {
                                    Dashmix.helpers('jq-notify', {type: 'success', icon: 'fa fa-check me-1', message: response.message});
                                    rolesDataTable.draw();
                                },
                                error: function (error) {
                                    Dashmix.helpers('jq-notify', {type: 'danger', icon: '', message: 'Error Deleting Role!'});
                                }
                            });
                        }
                    });
                })
            });
    </script>
    </x-app-layout>
