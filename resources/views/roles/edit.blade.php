<x-app-layout>
    @section('title', 'Edit Role')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Edit Role</h1>
            <x-breadcrumbs />
        </div>
        </div>
    </div>
    <!-- END Hero -->
    <!-- Page Content -->
    <div class="content">
        <!-- Dynamic Table Responsive -->
        <div class="block block-rounded">
            <form method="POST" action="{{ route('roles.update', $role->id) }}">
                @csrf
                @method('PUT')
            <div class="row push p-sm-2 p-lg-4 mb-0">
                <div class="col-6">
                    <div class="">
                        <label class="form-label" for="role-name">Name</label>
                        <input type="text" name="name" value="{{ $role->name ?? old('name') }}"
                            autocomplete="name" class="form-control"
                            placeholder="Role Name">
                        <x-input-error-field class="" :messages="$errors->get('name')" />
                    </div>
                </div>
            </div>
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-alt-primary">
                            <i class="fa fa-check-circle opacity-50 me-1"></i> Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Dynamic Table Responsive -->
    </div>
    <!-- END Page Content -->
</x-app-layout>
