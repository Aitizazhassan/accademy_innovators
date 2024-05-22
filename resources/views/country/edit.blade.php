<x-app-layout>
    @section('title', 'Country Edit')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Country User</h1>
            <x-breadcrumbs />
        </div>
        </div>
    </div>
    <!-- END Hero -->
    <!-- Page Content -->
    <div class="content">
        <!-- Dynamic Table Responsive -->
        <div class="block block-bordered block-rounded">
            <div class="row push p-sm-2 p-lg-4">
                <div class="col-xl-6 order-xl-0">
                      <form action="{{ route('country.update', $country->id) }}" method="post">
                            @csrf
                            @method('PUT')
                        <div class="mb-4">
                            <label class="form-label" for="profile-edit-name">Country Name</label>
                            <input type="text" name="name"
                                autocomplete="name" class="form-control form-control-sm" id="profile-edit-name"
                                placeholder="Enter  Name" value="{{$country->name}}">
                            <x-input-error-field :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-4">

                            <button type="submit" class="btn btn-alt-primary">
                                <i class="fa fa-check-circle opacity-50 me-1"></i> Update
                            </button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Dynamic Table Responsive -->
    </div>
    <!-- END Page Content -->
</x-app-layout>
