<x-app-layout>
    @section('title', 'Edit Permissions')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Edit Permissions</h1>
                <x-breadcrumbs />
            </div>
        </div>
    </div>
    <!-- END Hero -->
    <!-- Page Content -->
    <div class="content">
        <!-- Dynamic Table Responsive -->
        <div class="block block-rounded">
            <form method="POST" action="{{ route('roles.update.permissions', $role->id) }}">
                @csrf
                @method('PUT')
                <div class="block-content block-content-full">
                    <table class="table table-bordered table-striped table-vcenter" id="roles-table">
                        <thead class="table-dark">
                            <tr>
                                <th>Modules</th>
                                <th>Create</th>
                                <th>Delete</th>
                                <th>Edit</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($permissionsByModule as $module => $permissions)
                                <tr>
                                    <td class="text-nowrap fw-bolder">{{ ucfirst($module) }}</td>
                                    @foreach ($permissions as $key => $permission)
                                        <td>
                                            <div class="form-check form-switch form-check-primary">
                                                <input name="permissions[]" type="checkbox"
                                                    class="form-check-input permissionCheckbox"
                                                    id="customSwitch{{ $module }}{{ $key }}"
                                                    value="{{ $permission->name }}"
                                                    {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                                    id="customSwitch{{ $key }}">
                                                <label class="form-check-label"
                                                    for="customSwitch{{ $module }}{{ $key }}">
                                                </label>
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row mt-5">
                        <div class="col-6">
                            <button type="submit" class="btn btn-alt-primary">
                                <i class="fa fa-check-circle opacity-50 me-1"></i> Update
                            </button>
                            <button type="reset" class="btn btn-outline-secondary"><i
                                    class="fa fa-undo opacity-50 me-1"></i>Reset</button>
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <div class="form-check form-switch form-check-primary" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="" data-bs-original-title="Select All"
                                aria-label="Select All">
                                <input type="checkbox" class="form-check-input" id="checkAll">
                                <label class="form-check-label" for="checkAll">
                                </label>
                                <label class="form-check-label" for="customSwitch1">Select All</label>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <!-- Dynamic Table Responsive -->
    </div>
    <!-- END Page Content -->
    <script>
        $('#checkAll').change(function() {
            $('.permissionCheckbox').prop('checked', this.checked);
        });
    </script>
</x-app-layout>
