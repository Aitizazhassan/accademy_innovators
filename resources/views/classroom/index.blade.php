<x-app-layout>
    @section('title', 'Users')

    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Class
                    @can('user.create')
                        <a href="{{ route('classroom.create') }}" class="btn btn-primary ms-3"><i class="fa fa-plus"></i> Create
                            Class </a>
                    @endcan
                </h1>
                <x-breadcrumbs />
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->

    <div class="container-fluid">
        <!-- DataTales Example -->
        <div class="content">

            <!-- Dynamic Table Responsive -->
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <table class="table table-bordered table-striped table-vcenter table-sm" id="users-table">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 80px;">{{ __('#') }}</th>
                                <th>{{ __('Countries') }}</th>
                                <th>{{ __('Boards') }}</th>
                                <th style="width:10%">{{ __('Class Name') }}</th>
                                <th style="width:15%">{{ __('Date') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Dynamic Table Responsive -->
        </div>

    </div>

    {{-- delete modal --}}

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="" id="deleteModalForm">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" id="deleteModalFormMsg"></div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- END Page Content -->

    <script>
        $(document).ready(function() {
            var usersDataTable = $('#users-table').DataTable({
                processing: true,
                serverSide: false,
                pagingType: "full_numbers",
                autoWidth: !1,
                responsive: !0,
                ajax: "{{ route('classroom.index') }}",
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'class_countries',
                        name: 'class_countries'
                    },
                    {
                        data: 'class_boards',
                        name: 'class_boards'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'dateAdded',
                        name: 'dateAdded'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ],
            });

            // Initialize Bootstrap tooltips after DataTable is loaded
            usersDataTable.on('draw', function() {
                $('[data-bs-toggle="tooltip"]').tooltip();
            });

            // Delete user
            $('#users-table').on('click', '.delete-user', function(e) {
                e.preventDefault(); // Prevent the default link behavior

                var userId = $(this).data('id');
                Swal.fire({
                    text: 'Are you sure you want to delete this classroom?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-2'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: "{{ route('classroom.destroy', ':id') }}".replace(':id', userId),
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                // Reload the DataTable after successful deletion
                                usersDataTable.ajax.reload();

                                // Optionally, show a success message
                                Dashmix.helpers('jq-notify', {
                                    type: 'success',
                                    icon: 'fa fa-check me-1',
                                    message: response.message
                                });
                            },
                            error: function(xhr, status, error) {
                                // Handle error
                                Dashmix.helpers('jq-notify', {
                                    type: 'danger',
                                    icon: '',
                                    message: 'Error Deleting Role!'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
</x-app-layout>
