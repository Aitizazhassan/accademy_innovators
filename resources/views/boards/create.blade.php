<x-app-layout>
    @section('title', 'Create Boards')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Create Boards </h1>
                <x-breadcrumbs />
            </div>
        </div>
    </div>
    <!-- END Hero -->
    <!-- Page Content -->
    <div class="content">
        <!-- Dynamic Table Responsive -->
        <div class="block block-bordered block-rounded">

            <form method="POST" action="{{ route('boards.store') }}">
                @csrf
                <div class="row push p-sm-2 p-lg-4">
                    <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="country_id">Country</label>
                            <select name="country_id[]" id="country_id"
                                class="form-control form-control-sm select2 js-example-basic-multiple"
                                multiple="multiple">
                                <option value="">Select</option>
                                @forelse ($countries as $country)
                                    <!-- Corrected variable name -->
                                    <option  value="{{ $country->id }}"
                                        {{ collect(old('country_id'))->contains($country->id) ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @empty
                                    <option value="">No Country found</option>
                                @endforelse
                            </select>
                            <x-input-error :messages="$errors->get('country_id')" class="mt-2" />
                        </div>
                    </div>
                    {{-- <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="profile-edit-name">Board Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" autocomplete="name"
                                class="form-control form-control-sm" id="profile-edit-name" placeholder="Enter Name">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" /> <!-- Corrected component name -->
                        </div>
                    </div> --}}
                    <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="board-name">Board Name</label>
                            <select name="name[]" id="board-name" class="form-control select2-multiple" multiple="multiple"></select>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />

                        </div>
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="btn btn-alt-primary">
                            <i class="fa fa-check-circle opacity-50 me-1"></i> Create
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
    </div>
    <!-- Dynamic Table Responsive -->
    </div>
    <!-- END Page Content -->
</x-app-layout>
<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
    $(document).ready(function() {
        $('#chapter-select').select2();

        $('#board-name').select2({
            tags: true,
            tokenSeparators: [',', ' '],
            placeholder: "Enter Board Name",
            createTag: function(params) {
                var term = $.trim(params.term);
                if (term === '') {
                    return null;
                }
                return {
                    id: term,
                    text: term,
                    newTag: true // add additional parameters
                };
            }
        });
    });
</script>
