<x-app-layout>
    @section('title', 'Subjact Edit')
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

            <form action="{{ route('subject.update', $subject->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="row push p-sm-2 p-lg-4">
                    <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="profile-edit-name">Class</label>

                            <select name="classroom_id[]" id="classroom_id" class="form-control form-contol-sm select2 js-example-basic-multiple" multiple="multiple" required>
                                <option disabled value="">Select</option>

                                @forelse ($classrooms as $row)
                                    <option value="{{ $row->id }}" {{ in_array($row->id, old('classroom_id', $subject->classrooms->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $row->name }}</option>
                                @empty
                                    <option value="">No class found</option>
                                @endforelse
                            </select>

                            <x-input-error-field :messages="$errors->get('borad_id')" class="mt-2" />
                        </div>
                    </div>
                    <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="profile-edit-name">Subject Name</label>
                            <input type="text" name="name" value="{{ old('name', $subject->name) }}" autocomplete="name"
                                class="form-control form-contol-sm select2" id="profile-edit-name" placeholder="Enter Name">
                                <x-input-error-field  :messages="$errors->get('name')" class="mt-2" />
                        </div>
                    </div>
                    <div class="mb-4">
                        <button type="submit" class="btn btn-alt-primary">
                            <i class="fa fa-check-circle opacity-50 me-1"></i> Update
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
</script>
