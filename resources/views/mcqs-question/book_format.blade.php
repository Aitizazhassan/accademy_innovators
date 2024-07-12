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
                    <!-- Country Name Selection -->
                    <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="country_id">Country Name</label>
                            <select name="country_id" id="country_id" class="form-control form-control-sm select2-single">
                                <option value="">Select Country</option>
                                @forelse ($countries as $row)
                                    <option value="{{ $row->id }}" {{ old('country_id') == $row->id ? 'selected' : '' }}>
                                        {{ $row->name }}
                                    </option>
                                @empty
                                    <option value="">No Country found</option>
                                @endforelse
                            </select>
                            <x-input-error :messages="$errors->get('country_id')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Board Name Selection -->
                    <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="board_id">Board Name</label>
                            <select name="board_id" id="board_id" class="form-control form-control-sm select2-single">
                                <option value="">Select Board</option>
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

                    <!-- select pathern-->
                    <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="select_pathern">Pathern</label>
                            <select name="select_pathern" id="select_pathern" class="form-control form-control-sm select2-single">
                                <option value="chapter_wise">Chapter Wise</option>
                                <option value="grand_test">Grand Test</option>
                                <option value="mock_test">MOCK TEST</option>
                            </select>
                            <x-input-error :messages="$errors->get('select_pathern')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Subject Name Selection -->
                    <div class="col-xl-4 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="subject_id">Select Subject</label>
                            <select name="subject_id" id="subject_id" class="form-control form-control-sm select2-single">
                            </select>
                            <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Chapter Name Selection -->
                    {{-- <div class="col-xl-4 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="chapter_id">Chapter Name</label>
                            <select name="chapter_id" id="chapter_id" class="form-control form-control-sm select2-single">
                                <option value="">Select Chapter</option>
                            </select>
                            <x-input-error :messages="$errors->get('chapter_id')" class="mt-2" />
                        </div>
                    </div> --}}

                    <!-- Topic Name Selection -->
                    {{-- <div class="col-xl-4 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="topic_id">Topic Name</label>
                            <select name="topic_id" id="topic_id" class="form-control form-control-sm select2-single">
                                <option value="">Select Topic</option>
                            </select>
                            <x-input-error :messages="$errors->get('topic_id')" class="mt-2" />
                        </div>
                    </div> --}}

                    <!-- Submit Button -->
                    <div class="mb-4">
                        <button type="submit" class="btn btn-alt-primary">
                            <i class="fa fa-check-circle opacity-50 me-1"></i> Get MCQS
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
        $('.select2-single').select2();

        var getBoardUrl = "{{ route('getBoards', ':country_id') }}";
        var getClassUrl = "{{ route('getClass', ':board_id') }}";
        var getSubjectsUrl = "{{ route('getSubjects', ':class_id') }}";
        var getChaptersUrl = "{{ route('getChapters', ':subject_id') }}";
        var getTopicsUrl = "{{ route('getTopics', ':chapter_id') }}";

        $('#country_id').change(function() {
            var countryId = $(this).val();
            if (countryId) {
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
                        // $('#subject_id').empty().append(
                        //     '<option value="">Select Subject</option>');
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

        $('#select_pathern').change(function(e) {
            e.preventDefault();
            var selectedPathern = $(this).val();
            var subjectSelect = $('#subject_id');
            var classId = $('#class_id').val();

            subjectSelect.empty(); // Clear existing options
            // subjectSelect.append('<option value="">Select Subject</option>');

            // Fetch and populate subjects
            getSubjects(classId, function(subjects) {
                switch (selectedPathern) {
                    case 'chapter_wise':
                        subjectSelect.attr('multiple', false); // Single select
                        break;
                    case 'grand_test':
                        subjectSelect.attr('multiple', 'multiple'); // Multiple select
                        break;
                    case 'mock_test':
                        subjectSelect.attr('multiple', 'multiple'); // Multiple select
                        break;
                    default:
                        subjectSelect.attr('multiple', false); // Default to single select
                }

                subjects.forEach(function(subject) {
                    subjectSelect.append('<option value="' + subject.id + '">' + subject.name + '</option>');
                });
                $('.select2-single').select2();
            });
        });

        function getSubjects(classId, callback) {
            if (classId) {
                $.ajax({
                    url: getSubjectsUrl.replace(':class_id', classId),
                    type: 'GET',
                    success: function(data) {
                        callback(data);
                    },
                    error: function() {
                        console.error('Failed to fetch subjects');
                        callback([]);
                    }
                });
            } else {
                callback([]);
            }
        }
    }); 
</script>
