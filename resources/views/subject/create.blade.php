<x-app-layout>
    @section('title', 'Create Class Rooms')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ $pageHead }}</h1>
                <x-breadcrumbs />
            </div>
        </div>
    </div>
    <!-- END Hero -->
    <!-- Page Content -->
    <div class="content">
        <!-- Dynamic Table Responsive -->
        <div class="block block-bordered block-rounded">


            <form method="POST" action="{{ route('subject.store') }}">
                @csrf
                <div class="row push p-sm-2 p-lg-4">
                    <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="profile-edit-name">Class Name</label>
                            <select name="classroom_id[]" id="classroom_id"
                                class="form-control form-contol-sm select2 js-example-basic-multiple"
                                multiple="multiple">
                                <option disabled="">Select Class</option>
                                @forelse ($classrooms as $row)
                                    <option value="{{ $row->id }}"
                                        {{ in_array($row->id, (array) old('classroom_id', [])) ? 'selected' : '' }}>
                                        {{ $row->name }}
                                    </option>
                                @empty
                                    <option value="">No session found</option>
                                @endforelse
                            </select>

                            <x-input-error :messages="$errors->get('borad_id')" class="mt-2" />
                        </div>
                    </div>
                    <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="subject-names">Subject Names</label>
                            <select name="name[]" id="subject-names" class="form-control select2-multiple"
                                multiple="multiple"></select>
                            <x-input-error :messages="$errors->get('name.*')" class="mt-2" />
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
        <!-- Dynamic Table Responsive -->
    </div>
    <!-- END Page Content -->



</x-app-layout>
<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
    $(document).ready(function() {
        $('#subject-select').select2();

        $('#subject-names').select2({
            tags: true,
            tokenSeparators: [',', ' '],
            placeholder: "Enter Subject Name",
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
