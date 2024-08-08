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


            <form method="POST" action="{{ route('chapter.store') }}">
                @csrf
                <div class="row push p-sm-2 p-lg-4">
                     <!-- Class Name Selection -->
                     <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="class_id">Class </label>
                            <select name="class_id[]" id="class_id" class="form-control form-control-sm  select2-multiple" multiple="multiple" required>
                                <option value="">Select Class</option>
                                @forelse ($classes as $row)
                                    <option value="{{ $row->id }}" {{ old('class_id') == $row->id ? 'selected' : '' }}>{{ $row->name }}</option>
                                @empty
                                    <option value="">No class found</option>
                                @endforelse
                            </select>
                            <x-input-error :messages="$errors->get('class_id')" class="mt-2" />
                        </div>
                    </div>
                    <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="profile-edit-name">Subject Name</label>
                           <select name="subject_id" id="subject_id" class="form-control form-contol-sm select2-single" required>
                                <option disabled value="">Select Subject</option>
                                {{-- @forelse ($subjects as $row)
                                    <option value="{{ $row->id }}" {{ old('subject_id') == $row->id?'selected':'' }}>{{ $row->name }}</option>
                                @empty
                                    <option value="">No subject found</option>
                                @endforelse --}}
                            </select>
                            <x-input-error-field :messages="$errors->get('subject_id')" class="mt-2" />
                        </div>
                    </div>
                    <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="chapter-names">Chapter Name</label>
                            <select name="name[]" id="chapter-names" class="form-control select2-multiple" multiple="multiple" required></select>
                            <x-input-error-field :messages="$errors->get('name')" class="mt-2" />
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
        $('.select2-single, .select2-multiple').select2();
    });

    $(document).ready(function() {
        var getSubjectsUrl = "{{ route('getSubjects', ':class_id') }}";
        var getChaptersUrl = "{{ route('getChapters', ':subject_id') }}";
        $('#chapter-select').select2();

        $('#chapter-names').select2({
            tags: true,
            tokenSeparators: [','],
            placeholder: "Enter Chapter Names",
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

        $('#class_id').change(function() {
            var classId = $(this).val();
            if (classId) {
                $.ajax({
                    url: getSubjectsUrl.replace(':class_id', classId),
                    type: 'GET',
                    success: function(data) {
                        $('#subject_id').empty().append(
                            '<option value="">Select Subject</option>');
                        $.each(data, function(key, value) {
                            $('#subject_id').append('<option value="' + value.id +
                                '">' + value.name + '</option>');
                        });
                        $('#chapter_id').empty().append(
                            '<option value="">Select Chapter</option>');
                        $('.select2-single').select2();
                    },
                    error: function(error){
                        $('#subject_id').empty().append('<option value="">Select Subject</option>');
                        $('#chapter_id').empty().append('<option value="">Select Chapter</option>');
                    }
                });
            } else {
                $('#subject_id').empty().append('<option value="">Select Subject</option>');
                $('#chapter_id').empty().append('<option value="">Select Chapter</option>');
            }
        });
    });
</script>
