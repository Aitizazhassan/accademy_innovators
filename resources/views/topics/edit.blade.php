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

            <form action="{{ route('topic.update', $topic->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="row push p-sm-2 p-lg-4">
                    <div class="col-xl-6 order-xl-0">
                        <div class="form-group mb-4">
                            <label for="class">Select Class</label>
                            <select name="class_id[]" id="class_id" class="form-control form-contol-sm select2-multiple" multiple required>
                            <option disabled value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ in_array($class->id, $topic->classes->pluck('id')->toArray()) ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error-field :messages="$errors->get('class_id')" class="mt-2" />
                        </div>
                    </div>
                    <div class="col-xl-6 order-xl-0">
                        <div class="form-group mb-4">
                            <label for="Subject">Select Subject</label>
                            <select name="subject_id[]" id="subject_id" class="form-control form-contol-sm select2-multiple" multiple required>
                            <option disabled value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ in_array($subject->id, $topic->subjects->pluck('id')->toArray()) ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error-field :messages="$errors->get('subject_id')" class="mt-2" />
                        </div>
                    </div>
                    <div class="col-xl-6 order-xl-0">
                        <div class="form-group">
                            <label for="chapters">Select Chapter</label>
                            <select name="chapter_id[]" id="chapter_id" class="form-control form-contol-sm select2-multiple" multiple required>
                                <option disabled value="">Select Chapter</option>
                                    @foreach($chapters as $chapter)
                                        <option value="{{ $chapter->id }}" {{ in_array($chapter->id, $topic->chapters->pluck('id')->toArray()) ? 'selected' : '' }}>
                                            {{ $chapter->name }}
                                        </option>
                                    @endforeach
                            </select>
                            <x-input-error-field :messages="$errors->get('chapter_id')" class="mt-2" />
                        </div>
                    </div>
                    <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="profile-edit-name">Topic Name</label>
                            <input type="text" name="name" value="{{ old('name', $topic->name) }}" autocomplete="name" class="form-control form-contol-sm select2" id="profile-edit-name" placeholder="Enter Name">
                            <x-input-error-field :messages="$errors->get('name')" class="mt-2" />
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

        $('.select2-multiple').select2();
        var getSubjectsUrl = "{{ route('getSubjects', ':class_id') }}";
        var getChaptersUrl = "{{ route('getChapters', ':subject_id') }}";

        $('#class_id').change(function() {
            var classIds = $(this).val();
            if (classIds && classIds.length > 0) {
                $.ajax({
                    url: getSubjectsUrl.replace(':class_id', classIds.join(',')),
                    type: 'GET',
                    success: function(data) {
                        // Get existing selected subjects
                        var selectedSubjects = $('#subject_id').val() || [];

                        // Clear the subject dropdown
                        $('#subject_id').empty().append('<option value="">Select Subject</option>');

                        // Append new subjects to the dropdown and maintain valid selections
                        $.each(data, function(key, value) {
                            $('#subject_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                        // Keep only valid selected subjects
                        $('#subject_id').val(selectedSubjects.filter(id => data.some(subject => subject.id == id)));
                        $('.select2-multiple').select2(); // Re-initialize select2
                    }
                });
            } else {
                // If no class is selected, clear subjects
                $('#subject_id').empty().append('<option value="">Select Subject</option>');
                $('#chapter_id').empty().append('<option value="">Select Chapter</option>'); // Clear chapters
            }
        });

        $('#subject_id').change(function() {
            var subjectIds = $(this).val();
            if (subjectIds && subjectIds.length > 0) {
                $.ajax({
                    url: getChaptersUrl.replace(':subject_id', subjectIds.join(',')),
                    type: 'GET',
                    success: function(data) {
                        // Get existing selected chapters
                        var selectedChapters = $('#chapter_id').val() || [];

                        // Clear the chapter dropdown
                        $('#chapter_id').empty().append('<option value="">Select Chapter</option>');

                        // Append new chapters to the dropdown and maintain valid selections
                        $.each(data, function(key, value) {
                            $('#chapter_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                        // Keep only valid selected chapters
                        $('#chapter_id').val(selectedChapters.filter(id => data.some(chapter => chapter.id == id)));
                        $('.select2-multiple').select2(); // Re-initialize select2
                    }
                });
            } else {
                // If no subject is selected, clear chapters
                $('#chapter_id').empty().append('<option value="">Select Chapter</option>');
            }
        });
    });
</script>
