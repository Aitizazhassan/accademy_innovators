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


            <form method="POST" action="{{ route('topic.store') }}">
                @csrf
                <div class="row push p-sm-2 p-lg-4">
                    
                     <!-- Class Name Selection -->
                     <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="class_id">Class </label>
                            <select name="class_id[]" id="class_id" class="form-control form-control-sm select2-multiple" multiple="multiple" required>
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
                          <select name="subject_id[]" id="subject_id" class="form-control form-contol-sm select2-multiple" multiple required>
                               <option disabled value="">Select Subject</option>
                           </select>
                           <x-input-error-field :messages="$errors->get('subject_id')" class="mt-2" />
                       </div>
                   </div>
                    <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="profile-edit-name">Chapter Name</label>
                            <select name="chapter_id[]" id="chapter_id"
                                class="form-control form-contol-sm select2-multiple" multiple required>
                                <option value="">select Chapter</option>
                                {{-- @forelse ($topic as $row)
                                    <option value="{{ $row->id }}"
                                        {{ old('chapter_id') == $row->id ? 'selected' : '' }}>{{ $row->name }}</option>
                                @empty
                                    <option value="">No subject found</option>
                                @endforelse --}}
                            </select>
                            <x-input-error-field :messages="$errors->get('chapter_id')" class="mt-2" />
                        </div>
                    </div>
                    <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="topic-names">Topic Name</label>
                            <select name="name[]" id="topic-names" class="form-control select2-multiple"
                                multiple="multiple"></select>
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
        $('#chapter-select').select2();
        var getSubjectsUrl = "{{ route('getSubjects', ':class_id') }}";
        var getChaptersUrl = "{{ route('getChapters', ':subject_id') }}";

        $('#topic-names').select2({
            tags: true,
            tokenSeparators: [','],
            placeholder: "Enter Topic Names",
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
            var classIds = $(this).val();
            if (classIds && classIds.length > 0) {
                $.ajax({
                    url: getSubjectsUrl.replace(':class_id', classIds),
                    type: 'GET',
                    success: function(data) {
                        $('#subject_id').empty().append('<option value="">Select Subject</option>');
                        $.each(data, function(key, value) {
                            $('#subject_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                        $('#chapter_id').empty().append('<option value="">Select Chapter</option>');
                    },
                    error: function() {
                        $('#subject_id').empty().append('<option value="">Select Subject</option>');
                        $('#chapter_id').empty().append('<option value="">Select Chapter</option>');
                    }
                });
            } else {
                $('#subject_id').empty().append('<option value="">Select Subject</option>');
                $('#chapter_id').empty().append('<option value="">Select Chapter</option>');
            }
        });

        $('#subject_id').change(function() {
            var subjectIds = $(this).val();
            if (subjectIds && subjectIds.length > 0) {
                $.ajax({
                    url: getChaptersUrl.replace(':subject_id', subjectIds),
                    type: 'GET',
                    success: function(data) {
                        $('#chapter_id').empty().append('<option value="">Select Chapter</option>');
                        $.each(data, function(key, value) {
                            $('#chapter_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    },
                    error: function() {
                        $('#chapter_id').empty().append('<option value="">Select Chapter</option>');
                    }
                });
            } else {
                $('#chapter_id').empty().append('<option value="">Select Chapter</option>');
            }
        });
    });
</script>
