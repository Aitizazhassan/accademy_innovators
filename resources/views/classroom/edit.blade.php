<x-app-layout>
    @section('title', 'Class Edit')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Class </h1>
                <x-breadcrumbs />
            </div>
        </div>
    </div>
    <!-- END Hero -->
    <!-- Page Content -->
    <div class="content">
        <!-- Dynamic Table Responsive -->
        <div class="block block-bordered block-rounded">

                    <form action="{{ route('classroom.update', $classroom->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row push p-sm-2 p-lg-4">
                            <div class="col-xl-6 order-xl-0">
                                <div class="mb-4">
                                    <label class="form-label" for="profile-edit-name">Board Name</label>
                                    <select name="board_id[]" id="board_id"
                                        class="form-control form-contol-sm select2 js-example-basic-multiple" multiple>
                                        <option  disabled value="">Select</option>
                                        @forelse ($boards as $data)
                                        <option value="{{ $data->id }}"
                                            @if(in_array($data->id, old('board_id', $classroom->boards->pluck('id')->toArray())))
                                                selected
                                            @endif>{{ $data->name }}</option>
                                        @empty
                                            <option value="">No sessions found</option>
                                        @endforelse
                                    </select>
                                    <x-input-error :messages="$errors->get('borad_id')" class="mt-2" />
                                </div>
                            </div>
                            <div class="col-xl-6 order-xl-0">
                                <div class="mb-4">
                                    <label class="form-label" for="profile-edit-name">Class Name</label>
                                    <input type="text" name="name" value="{{ old('name', $classroom->name) }}"
                                        autocomplete="name" class="form-control form-contol-sm select2"
                                        id="profile-edit-name" placeholder="Enter Name">
                                    <x-input-error-field :messages="$errors->get('name')" class="mt-2" />
                                </div>
                            </div>
                            <div class="mb-4">
                                <button type="submit" class="btn btn-alt-primary">
                                    <i class="fa fa-check-circle opacity-50 me-1"></i> update
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
