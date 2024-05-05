<x-app-layout>
    @section('title', 'Create User')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Create User</h1>
            <x-breadcrumbs />
        </div>
        </div>
    </div>
    <!-- END Hero -->
    <!-- Page Content -->
    <div class="content">
        <!-- Dynamic Table Responsive -->
        <div class="block block-bordered block-rounded">
            <div class="row push p-sm-2 p-lg-4">
                <div class="col-xl-6 order-xl-0">
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label" for="profile-edit-first-name">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}"
                                autocomplete="name" class="form-control form-control-sm" id="profile-edit-first-name"
                                placeholder="Enter First Name">
                            <x-input-error-field :messages="$errors->get('first_name')" class="mt-2" />
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="profile-edit-last-name">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}"
                                autocomplete="name" class="form-control form-control-sm" id="profile-edit-last-name"
                                placeholder="Enter Last Name">
                            <x-input-error-field :messages="$errors->get('last_name')" class="mt-2" />
                        </div>
                        <x-input-error-field :messages="$errors->get('last_name')" class="mt-2" />
                        <div class="mb-4">
                            <label class="form-label" for="profile-edit-email">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-sm"
                                id="profile-edit-email" placeholder="Enter your email.." required autocomplete="email">
                            <x-input-error-field :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="password">Password</label>
                            <input type="password" name="password" value="{{ old('password') }}" required
                                autocomplete="password" class="form-control form-control-sm" id="password" placeholder="Enter Password">
                        </div>
                        <x-input-error-field :messages="$errors->get('password')" class="mt-2" />
                        <div class="mb-4">
                            <label class="form-label" for="profile-edit-phone-number">Phone number</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number') }}"
                                autocomplete="name" class="form-control form-control-sm" id="profile-edit-phone-number"
                                placeholder="Enter Phone number">
                            <x-input-error-field :messages="$errors->get('phone_number')" class="mt-2" />
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="status">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="pending">Pending</option>
                                <option value="approved">Approved
                                </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="role">Role</label>
                            <select class="form-select" id="role" name="role">
                                @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            {{-- <label class="form-label">Your Avatar</label>
                            <div class="push">
                                <img class="img-avatar" src="{{ asset('assets/media/avatars/avatar13.jpg') }}" alt="">
                                </div>
                                <label class="form-label" for="dm-profile-edit-avatar">Choose a new avatar</label>
                                <input class="form-control form-control-sm" type="file" id="dm-profile-edit-avatar">
                            </div> --}}
                            <button type="submit" class="btn btn-alt-primary">
                                <i class="fa fa-check-circle opacity-50 me-1"></i> Create
                            </button>
                    </form>
                </div>   
            </div>      
        </div>
        <!-- Dynamic Table Responsive -->
    </div>
    <!-- END Page Content -->
</x-app-layout>
