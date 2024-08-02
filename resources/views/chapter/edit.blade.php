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

            <form action="{{ route('chapter.update', $chapter->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="row push p-sm-2 p-lg-4">
                    <div class="col-xl-6 order-xl-0">
                        <div class="form-group mb-4">
                            <label for="class">Class Name</label>
                            <select name="class_id" id="class_id" class="form-control form-contol-sm select2-single" required>
                            <option disabled value="">Select</option>
                                @forelse ($classes as $row)
                                    <option value="{{ $row->id }}" {{ old('class_id', $chapter->classroom->id == $row->id) ? 'selected' : '' }}>
                                        {{ $row->name }}
                                    </option>
                                @empty
                                    <option value="">No Class found</option>
                                @endforelse
                            </select>
                            <x-input-error-field :messages="$errors->get('class_id')" class="mt-2" />
                        </div>
                    </div>
                    <div class="col-xl-6 order-xl-0">
                        <div class="form-group mb-4">
                            <label for="Subject">Subject Name</label>
                            <select name="subject_id" id="subject_id" class="form-control form-contol-sm select2-single" required>
                            <option disabled value="">Select</option>
                                @forelse ($subjects as $row)
                                    <option value="{{ $row->id }}" {{ old('subject_id', $chapter->subject->id == $row->id) ? 'selected' : '' }}>
                                        {{ $row->name }}
                                    </option>
                                @empty
                                    <option value="">No subjects found</option>
                                @endforelse
                            </select>
                            <x-input-error-field :messages="$errors->get('subject_id')" class="mt-2" />
                        </div>
                    </div>
                    <div class="col-xl-6 order-xl-0">
                        <div class="mb-4">
                            <label class="form-label" for="profile-edit-name">Chapter Name</label>
                            <input type="text" name="name" value="{{ old('name',$chapter->name) }}" autocomplete="name"
                                class="form-control form-contol-sm select2" id="profile-edit-name" placeholder="Enter Name">
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
        $('.js-example-basic-multiple').select2();
        $('.select2-single').select2();
        var getSubjectsUrl = "{{ route('getSubjects', ':class_id') }}";  

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
                    }
                });
            } else {
                $('#subject_id').empty().append('<option value="">Select Subject</option>');
                $('#chapter_id').empty().append('<option value="">Select Chapter</option>');
            }
        });
    });
</script>
