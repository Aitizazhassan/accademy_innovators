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
            <form method="POST" action="{{ route('mcqs.update', $mcq->id) }}">
                @csrf
                @method('PUT')
                <div class="row push p-sm-2 p-lg-4">
                    <!-- country Name Selection -->
                    <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="country_id">Country Name</label>
                            <select name="country_id[]" id="country_id" class="form-control form-control-sm select2-single" multiple="multiple">
                                <option value="">Select Country</option>
                                @forelse ($countries as $row)
                                    <option value="{{ $row->id }}" {{ in_array($row->id, old('country_id', $mcq->countries->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $row->name }}
                                    </option>
                                @empty
                                    <option value="">No Board found</option>
                                @endforelse
                            </select>
                            <x-input-error :messages="$errors->get('country_id')" class="mt-2" />
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-4">
                            <label class="form-label" for="board_id">Board Name</label>
                            <select name="board_id[]" id="board_id" class="form-control form-control-sm select2-single" multiple="multiple">
                                <option value="">Select Board</option>
                                @foreach ($boards as $board)
                                    <option value="{{ $board->id }}"
                                        {{ in_array($board->id, old('board_id', $mcq->boards->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $board->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-4">
                            <label class="form-label" for="class_id">Class Name</label>
                            <select name="class_id[]" id="class_id" class="form-control form-control-sm select2-single" multiple="multiple">
                                <option value="">Select Class</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}" 
                                        {{ in_array($class->id, old('class_id', $mcq->classes->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-4">
                            <label class="form-label" for="subject_id">Subject Name</label>
                            <select name="subject_id[]" id="subject_id"
                                class="form-control form-control-sm select2-single" multiple="multiple">
                                <option value="">Select Subject</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}"
                                        {{ in_array($subject->id, old('subject_id', $mcq->subjects->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-4">
                            <label class="form-label" for="chapter_id">Chapter Name</label>
                            <select name="chapter_id[]" id="chapter_id"
                                class="form-control form-control-sm select2-single" multiple="multiple">
                                <option value="">Select Chapter</option>
                                @foreach ($chapters as $chapter)
                                    <option value="{{ $chapter->id }}"
                                        {{ in_array($chapter->id, old('chapter_id', $mcq->chapters->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $chapter->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-4">
                            <label class="form-label" for="topic_id">Topic Name</label>
                            <select name="topic_id[]" id="topic_id" class="form-control form-control-sm select2-single" multiple="multiple">
                                <option value="">Select Topic</option>
                                @foreach ($topics as $topic)
                                    <option value="{{ $topic->id }}"
                                        {{ in_array($topic->id, old('topic_id', $mcq->topics->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $topic->name }}
                                    </option>
                                @endforeach
                            </select>
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
                                        <textarea id="js-ckeditor-statement" name="statement">{{ $mcq->statement }}</textarea>
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
                                        <textarea id="js-ckeditor-option-a" name="optionA">{{ $mcq->optionA }}</textarea>
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
                                        <textarea id="js-ckeditor-option-b" name="optionB">{{ $mcq->optionB }}</textarea>
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
                                        <textarea id="js-ckeditor-option-c" name="optionC">{{ $mcq->optionC }}</textarea>
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
                                        <textarea id="js-ckeditor-option-d" name="optionD">{{ $mcq->optionD }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-4">
                            <label class="form-label" for="solution_link_english">Solution Link (English)</label>
                            <input type="text" name="solution_link_english" id="solution_link_english"
                                class="form-control form-control-sm" value="{{ $mcq->solution_link_english }}">
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-4">
                            <label class="form-label" for="solution_link_urdu">Solution Link (Urdu)</label>
                            <input type="text" name="solution_link_urdu" id="solution_link_urdu"
                                class="form-control form-control-sm" value="{{ $mcq->solution_link_urdu }}">
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <div class="mb-4">
                            <button type="submit" class="btn btn-alt-primary">
                                <i class="fa fa-check-circle opacity-50 me-1"></i> Update
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- Dynamic Table Responsive -->
    </div>
    <!-- END Page Content -->



</x-app-layout>
<script>
    document.addEventListener("DOMContentLoaded", function() {
    CKEDITOR.replace('js-ckeditor-statement', { 
        extraPlugins: 'notification,notificationaggregator,mathjax',
        mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.7/MathJax.js?config=TeX-MML-AM_CHTML',
    });
    CKEDITOR.replace('js-ckeditor-option-a', { 
        extraPlugins: 'notification,notificationaggregator,mathjax',
        mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.7/MathJax.js?config=TeX-MML-AM_CHTML',
    });
    CKEDITOR.replace('js-ckeditor-option-b', { 
        extraPlugins: 'notification,notificationaggregator,mathjax',
        mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.7/MathJax.js?config=TeX-MML-AM_CHTML',
    });
    CKEDITOR.replace('js-ckeditor-option-c', { 
        extraPlugins: 'notification,notificationaggregator,mathjax',
        mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.7/MathJax.js?config=TeX-MML-AM_CHTML',
    });
    CKEDITOR.replace('js-ckeditor-option-d', { 
        extraPlugins: 'notification,notificationaggregator,mathjax',
        mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.7/MathJax.js?config=TeX-MML-AM_CHTML',
    });
});
$(document).ready(function() {
        $('.select2-single').select2();

        var getBoardUrl = "{{ route('getBoards', ':country_id') }}";
        var getClassUrl = "{{ route('getClass', ':board_id') }}";
        var getSubjectsUrl = "{{ route('getSubjects', ':class_id') }}";
        var getChaptersUrl = "{{ route('getChapters', ':subject_id') }}";
        var getTopicsUrl = "{{ route('getTopics', ':chapter_id') }}";

        $('#country_id').change(function() {
            var countryId = $(this).val();
            if (countryId.length > 0) {
                $.ajax({
                    url: getBoardUrl.replace(':country_id', countryId),
                    type: 'GET',
                    success: function(data) {
                        $('#board_id').empty().append(
                            '<option value="">Select Board</option>');
                        $.each(data, function(key, value) {
                            $('#board_id').append('<option value="' + value.id +
                                '">' + value.name + '</option>');
                        });
                        $('#class_id').empty().append(
                            '<option value="">Select Subject</option>');
                        $('#subject_id').empty().append(
                            '<option value="">Select Subject</option>');
                        $('#chapter_id').empty().append(
                            '<option value="">Select Chapter</option>');
                        $('#topic_id').empty().append(
                            '<option value="">Select Topic</option>');
                    }
                });
            } else {
                $('#board_id').empty().append('<option value="">Select Board</option>');
                $('#class_id').empty().append('<option value="">Select Class</option>');
                $('#subject_id').empty().append('<option value="">Select Subject</option>');
                $('#chapter_id').empty().append('<option value="">Select Chapter</option>');
                $('#topic_id').empty().append('<option value="">Select Topic</option>');
            }
        });

        $('#board_id').change(function() {
            var boardId = $(this).val();
            if (boardId.length > 0) {
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
            if (classId.length > 0) {
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
            if (subjectId.length > 0) {
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
            if (chapterId.length > 0) {
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
