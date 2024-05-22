<x-app-layout>
    @section('title', 'Boards Edit')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Boards User</h1>
                <x-breadcrumbs />
            </div>
        </div>
    </div>
    <!-- END Hero -->
    <!-- Page Content -->
    <div class="content">
        <!-- Dynamic Table Responsive -->
        <div class="block block-bordered block-rounded">


            <form action="{{ route('boards.update', $board->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="row push p-sm-2 p-lg-4">
                    <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="country_id">Country</label>
                            <select name="country_id[]" id="country_id" class="form-control form-control-sm select2 js-example-basic-multiple" multiple="multiple">
                                <option value="">Select</option>
                                @forelse ($countries as $country)
                                    <option value="{{ $country->id }}" {{ in_array($country->id, old('country_id', $board->countries->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @empty
                                    <option value="">No Country found</option>
                                @endforelse
                            </select>
                            <x-input-error :messages="$errors->get('country_id')" class="mt-2" />
                        </div>
                    </div>
                    <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="profile-edit-name">Board Name</label>
                            <input type="text" name="name" value="{{ old('name', $board->name) }}" autocomplete="name" class="form-control form-control-sm" id="profile-edit-name" placeholder="Enter Name">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <button type="submit" class="btn btn-alt-primary">
                        <i class="fa fa-check-circle opacity-50 me-1"></i> Update
                    </button>
                </div>
            </form>


        </div>
        <!-- Dynamic Table Responsive -->
    </div>
    <!-- END Page Content -->
</x-app-layout>
<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
</script>
