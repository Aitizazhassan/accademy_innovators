<x-app-layout>
    @section('title', 'Users')
  <style>
    div#modalContent img {
        width: 100%;
    }
  </style>
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ $pageHead }}
                    @can('user.create')
                        <a href="{{ route('mcqs.create') }}" class="btn btn-primary ms-3"><i class="fa fa-plus"></i> Create
                            {{ $pageHead }}</a>
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

                    <!-- Filter Select Fields -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="filterSolution" class="form-label">Filter by Solution Links</label>
                            <select class="form-select form-select-sm" id="filterSolution">
                                <option value="">All</option>
                                <option value="english">Has English Solution</option>
                                <option value="urdu">Has Urdu Solution</option>
                                <option value="both">Has Both Solutions</option>
                                <option value="no_english">No English Solutions</option>
                                <option value="no_urdu">No Urdu Solutions</option>
                                <option value="none">No Solutions</option>
                            </select>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-vcenter table-sm " id="mcqs-table">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 8%">{{ __('#') }}</th>
                                <th style="width: 8%">{{ __('Country') }}</th>
                                <th style="width: 8%">{{ __('Boards') }}</th>
                                <th style="width: 8%">{{ __('Class') }}</th>
                                <th style="width: 8%">{{ __('Subjects') }}</th>
                                <th style="width: 8%">{{ __('Chapters') }}</th>
                                <th style="width: 8%">{{ __('Topic') }}</th>
                                <th style="width: 9%">{{ __('Statement') }}</th>
                                <th style="width: 9%">{{ __('Option A') }}</th>
                                <th style="width: 9%">{{ __('Option B') }}</th>
                                <th style="width: 9%">{{ __('Option C') }}</th>
                                <th style="width: 9%">{{ __('Option D') }}</th>
                                <th>{{ __('Solution Link (English)') }}</th>
                                <th>{{ __('Solution Link (Urdu)') }}</th>
                                <th style="width: 15%">{{ __('Date') }}</th>
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
    <!-- Modal -->

    <div class="modal fade bd-example-modal-lg" id="dataModal" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dataModalLabel">Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modalContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!-- END Page Content -->

    <script>
        $(document).ready(function() {
            var mcqsDataTable = $('#mcqs-table').DataTable({
                processing: true,
                serverSide: true,
                pagingType: "full_numbers",
                autoWidth: false,
                responsive: true,
                ajax: {
                    url: "{{ route('mcqs.index') }}",
                    data: function (d) {
                        d.solutionFilter = $('#filterSolution').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'country_name',
                        name: 'country_name'
                    },
                    {
                        data: 'board_name',
                        name: 'board_name'
                    },
                    {
                        data: 'class_name',
                        name: 'class_name'
                    },
                    {
                        data: 'subject_name',
                        name: 'subject_name'
                    },
                    {
                        data: 'chapter_name',
                        name: 'chapter_name'
                    },
                    {
                        data: 'topic_name',
                        name: 'topic_name'
                    },
                    {
                        data: 'statement',
                        name: 'statement',
                        render: function(data, type, row) {
                            return `<button class="btn btn-sm btn-info-alt view-statement" data-id="${row.id}" data-statement="${data}"  data-bs-toggle="tooltip" title="View"><i class="fa fa-eye"></button>`;
                        }
                    },
                    {
                        data: 'optionA',
                        name: 'optionA',
                        render: function(data, type, row) {
                            return `<button class="btn btn-sm btn-info-alt view-option" data-id="${row.id}" data-option="A" data-content="${data}" data-bs-toggle="tooltip" title="View"><i class="fa fa-eye"></button>`;
                        }
                    },
                    {
                        data: 'optionB',
                        name: 'optionB',
                        render: function(data, type, row) {
                            return `<button class="btn btn-sm btn-info-alt view-option" data-id="${row.id}" data-option="B" data-content="${data}" data-bs-toggle="tooltip" title="View"><i class="fa fa-eye"></button>`;
                        }
                    },
                    {
                        data: 'optionC',
                        name: 'optionC',
                        render: function(data, type, row) {
                            return `<button class="btn btn-sm btn-info-alt view-option" data-id="${row.id}" data-option="C" data-content="${data}" data-bs-toggle="tooltip" title="View"><i class="fa fa-eye"></button>`;
                        }
                    },
                    {
                        data: 'optionD',
                        name: 'optionD',
                        render: function(data, type, row) {
                            return `<button class="btn btn-sm btn-info-alt view-option" data-id="${row.id}" data-option="D" data-content="${data}" data-bs-toggle="tooltip" title="View"><i class="fa fa-eye"></button>`;
                        }
                    },
                    {
                        data: 'solution_link_english',
                        name: 'solution_link_english',
                        render: function(data, type, row) {
                            if (row.solution_link_english) {
                                return `<button class="btn btn-sm btn-info-alt view-qr-code" data-id="${row.id}" data-qr-code='${data}' data-solution="English"  data-bs-toggle="tooltip" title="View"><i class="fa fa-eye"></button>`;
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: 'solution_link_urdu',
                        name: 'solution_link_urdu',
                        render: function(data, type, row) {
                            if (row.solution_link_urdu) {
                                return `<button class="btn btn-sm btn-info-alt view-qr-code" data-id="${row.id}" data-qr-code='${data}'  data-solution="Urdu"  data-bs-toggle="tooltip" title="View"><i class="fa fa-eye"></button>`;
                            } else {
                                return '';
                            }
                        }
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
                    }
                ],
            });

            // Initialize Bootstrap tooltips after DataTable is loaded
            mcqsDataTable.on('draw', function() {
                $('[data-bs-toggle="tooltip"]').tooltip();
            });

            $(document).on('change', '#filterSolution', function(e){
                e.preventDefault();
                mcqsDataTable.ajax.reload();
            })

            // Handle view statement button click
            $('#mcqs-table').on('click', '.view-statement', function() {
                var statement = $(this).data('statement');
                $('#modalContent').html(statement);
                $('#dataModalLabel').text('Statement');
                $('#dataModal').modal('show');
            });

            $('#mcqs-table').on('click', '.view-qr-code', function() {
                var qrCode = $(this).data('qr-code');
                var solution = $(this).data('solution');
                $('#modalContent').html(qrCode); 
                $('#dataModalLabel').text('QR Scan Code ('+ solution + ')');
                $('#dataModal').modal('show');
            });

            // Handle view option button click
            $('#mcqs-table').on('click', '.view-option', function() {
                var content = $(this).data('content');
                var option = $(this).data('option');
                $('#modalContent').html(content);
                $('#dataModalLabel').text('Option ' + option);
                $('#dataModal').modal('show');
            });

            // Delete user
            $('#mcqs-table').on('click', '.delete-user', function(e) {
                e.preventDefault(); // Prevent the default link behavior

                var userId = $(this).data('id');
                Swal.fire({
                    text: 'Are you sure you want to delete this Topic?',
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
                            url: "{{ route('mcqs.destroy', ':id') }}".replace(':id',
                                userId),
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                // Reload the DataTable after successful deletion
                                mcqsDataTable.ajax.reload();

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
