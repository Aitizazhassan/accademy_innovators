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
            <form method="POST" action="{{ route('mcqs.store') }}">
                @csrf
                <div class="row push p-sm-2 p-lg-4">
                    <!-- Board Name Selection -->
                    <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="board_id">Board Name</label>
                            <select name="board_id" id="board_id" class="form-control form-control-sm select2-single">
                                <option value="">Select Board</option>
                                @forelse ($board as $row)
                                    <option value="{{ $row->id }}" {{ old('board_id') == $row->id ? 'selected' : '' }}>
                                        {{ $row->name }}
                                    </option>
                                @empty
                                    <option value="">No Board found</option>
                                @endforelse
                            </select>
                            <x-input-error :messages="$errors->get('board_id')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Class Name Selection -->
                    <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="class_id">Class Name</label>
                            <select name="class_id" id="class_id" class="form-control form-control-sm select2-single">
                                <option value="">Select Class</option>
                            </select>
                            <x-input-error :messages="$errors->get('class_id')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Subject Name Selection -->
                    <div class="col-xl-4 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="subject_id">Subject Name</label>
                            <select name="subject_id" id="subject_id" class="form-control form-control-sm select2-single">
                                <option value="">Select Subject</option>
                            </select>
                            <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Chapter Name Selection -->
                    <div class="col-xl-4 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="chapter_id">Chapter Name</label>
                            <select name="chapter_id" id="chapter_id" class="form-control form-control-sm select2-single">
                                <option value="">Select Chapter</option>
                            </select>
                            <x-input-error :messages="$errors->get('chapter_id')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Topic Name Selection -->
                    <div class="col-xl-4 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="topic_id">Topic Name</label>
                            <select name="topic_id" id="topic_id" class="form-control form-control-sm select2-single">
                                <option value="">Select Topic</option>
                            </select>
                            <x-input-error :messages="$errors->get('topic_id')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Statement CKEditor -->
                    <div class="col-xl-12 order-xl-0">
                        <div class="mb-4">
                            <div class="block block-rounded">
                                <div class="block-header block-header-default">
                                    <h3 class="block-title">Statement</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option">
                                            <i class="si si-settings"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <div class="mb-4">
                                        <textarea id="js-ckeditor-statement" name="statement"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Option A CKEditor -->
                    <div class="col-xl-12 order-xl-0">
                        <div class="mb-4">
                            <div class="block block-rounded">
                                <div class="block-header block-header-default">
                                    <h3 class="block-title">Option A</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option">
                                            <i class="si si-settings"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <div class="mb-4">
                                        <textarea id="js-ckeditor-option-a" name="optionA"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Option B CKEditor -->
                    <div class="col-xl-12 order-xl-0">
                        <div class="mb-4">
                            <div class="block block-rounded">
                                <div class="block-header block-header-default">
                                    <h3 class="block-title">Option B</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option">
                                            <i class="si si-settings"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <div class="mb-4">
                                        <textarea id="js-ckeditor-option-b" name="optionB"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Option C CKEditor -->
                    <div class="col-xl-12 order-xl-0">
                        <div class="mb-4">
                            <div class="block block-rounded">
                                <div class="block-header block-header-default">
                                    <h3 class="block-title">Option C</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option">
                                            <i class="si si-settings"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <div class="mb-4">
                                        <textarea id="js-ckeditor-option-c" name="optionC"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Option D CKEditor -->
                    <div class="col-xl-12 order-xl-0">
                        <div class="mb-4">
                            <div class="block block-rounded">
                                <div class="block-header block-header-default">
                                    <h3 class="block-title">Option D</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option">
                                            <i class="si si-settings"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <div class="mb-4">
                                        <textarea id="js-ckeditor-option-d" name="optionD"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Solution Links -->
                    <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="solution_link_english">Solution Link (English)</label>
                            <input type="text" name="solution_link_english" id="solution_link_english" class="form-control form-control-sm">
                            <x-input-error :messages="$errors->get('solution_link_english')" class="mt-2" />
                        </div>
                    </div>

                    <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="solution_link_urdu">Solution Link (Urdu)</label>
                            <input type="text" name="solution_link_urdu" class="form-control form-control-sm">
                            <x-input-error :messages="$errors->get('solution_link_urdu')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Submit Button -->
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

<script>Dashmix.helpersOnLoad(['js-ckeditor', 'js-simplemde']);</script>

<script>
        document.addEventListener("DOMContentLoaded", function() {
        CKEDITOR.replace('js-ckeditor-statement');
        CKEDITOR.replace('js-ckeditor-option-a');
        CKEDITOR.replace('js-ckeditor-option-b');
        CKEDITOR.replace('js-ckeditor-option-c');
        CKEDITOR.replace('js-ckeditor-option-d');
    });
    $(document).ready(function() {
        $('.select2-single').select2();

        var getClassUrl = "{{ route('getClass', ':board_id') }}";
        var getSubjectsUrl = "{{ route('getSubjects', ':class_id') }}";
        var getChaptersUrl = "{{ route('getChapters', ':subject_id') }}";
        var getTopicsUrl = "{{ route('getTopics', ':chapter_id') }}";

        $('#board_id').change(function() {
            var boardId = $(this).val();
            if (boardId) {
                $.ajax({
                    url: getClassUrl.replace(':board_id', boardId),
                    type: 'GET',
                    success: function(data) {
                        $('#class_id').empty().append(
                            '<option value="">Select Class</option>');
                        $.each(data, function(key, value) {
                            $('#class_id').append('<option value="' + value.id +
                                '">' + value.name + '</option>');
                        });
                        $('#subject_id').empty().append(
                            '<option value="">Select Subject</option>');
                        $('#chapter_id').empty().append(
                            '<option value="">Select Chapter</option>');
                        $('#topic_id').empty().append(
                            '<option value="">Select Topic</option>');
                    }
                });
            } else {
                $('#class_id').empty().append('<option value="">Select Class</option>');
                $('#subject_id').empty().append('<option value="">Select Subject</option>');
                $('#chapter_id').empty().append('<option value="">Select Chapter</option>');
                $('#topic_id').empty().append('<option value="">Select Topic</option>');
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
                        $('#topic_id').empty().append(
                            '<option value="">Select Topic</option>');
                    }
                });
            } else {
                $('#subject_id').empty().append('<option value="">Select Subject</option>');
                $('#chapter_id').empty().append('<option value="">Select Chapter</option>');
                $('#topic_id').empty().append('<option value="">Select Topic</option>');
            }
        });

        $('#subject_id').change(function() {
            var subjectId = $(this).val();
            if (subjectId) {
                $.ajax({
                    url: getChaptersUrl.replace(':subject_id', subjectId),
                    type: 'GET',
                    success: function(data) {
                        $('#chapter_id').empty().append(
                            '<option value="">Select Chapter</option>');
                        $.each(data, function(key, value) {
                            $('#chapter_id').append('<option value="' + value.id +
                                '">' + value.name + '</option>');
                        });
                        $('#topic_id').empty().append(
                            '<option value="">Select Topic</option>');
                    }
                });
            } else {
                $('#chapter_id').empty().append('<option value="">Select Chapter</option>');
                $('#topic_id').empty().append('<option value="">Select Topic</option>');
            }
        });

        $('#chapter_id').change(function() {
            var chapterId = $(this).val();
            if (chapterId) {
                $.ajax({
                    url: getTopicsUrl.replace(':chapter_id', chapterId),
                    type: 'GET',
                    success: function(data) {
                        $('#topic_id').empty().append(
                            '<option value="">Select Topic</option>');
                        $.each(data, function(key, value) {
                            $('#topic_id').append('<option value="' + value.id +
                                '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#topic_id').empty().append('<option value="">Select Topic</option>');
            }
        });
    });
</script>
