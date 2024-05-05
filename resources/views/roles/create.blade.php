<x-app-layout>
    @section('title', 'Create Role')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Create Role</h1>
            <x-breadcrumbs />
        </div>
        </div>
    </div>
    <!-- END Hero -->
    <!-- Page Content -->
    <div class="content">
        <!-- Dynamic Table Responsive -->
        <div class="block block-rounded">
            <form method="POST" action="{{ route('roles.store') }}">
                @csrf
            <div class="row push p-sm-2 p-lg-4 mb-0">
                <div class="col-6">
                    <label class="form-label" for="role-name">Role Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        autocomplete="name" class="form-control"
                        placeholder="Name">
                    <x-input-error-field class="" :messages="$errors->get('name')" />
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <div class="form-check form-switch form-check-primary"  data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Select All" aria-label="Select All">
                        <input type="checkbox" class="form-check-input" id="checkAll">
                        <label class="form-check-label" for="checkAll">
                            <span>Select All</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="block-content block-content-full">
                <h1 class="flex-grow-1 fs-3 fw-semibold mb-2 mb-sm-3">Permissions</h1>
                <table class="table table-bordered table-striped table-vcenter" id="roles-table">
                    <thead class="table-dark">
                        <tr>
                            <th>Modules</th>
                            <th>Create</th>
                            <th>Read</th>
                            <th>Write</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($permissionsByModule as $module => $permissions)
                        <tr>
                            <td><h6>{{ ucfirst($module) }}</h6></td>
                            @foreach($permissions as $key => $permission)
                            <td>
                                <div class="form-check form-switch form-check-primary">
                                    <input name="permissions[]" type="checkbox" class="form-check-input permissionCheckbox" id="customSwitch{{$module}}{{$key}}" value="{{ $permission->name }}">
                                </div>
                            </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-12 mt-5">
                        <button type="submit" class="btn btn-alt-primary">
                            <i class="fa fa-check-circle opacity-50 me-1"></i> Submit
                        </button>
                        <button type="reset" class="btn btn-outline-secondary"><i class="fa fa-undo opacity-50 me-1"></i>Reset</button>
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
