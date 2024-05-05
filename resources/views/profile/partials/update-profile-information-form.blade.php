<section>
    {{-- <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form> --}}
    <div class="row push p-sm-2 p-lg-4">
        <div class="col-xl-6 order-xl-0">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="mb-4">
                    <label class="form-label" for="profile-edit-first-name">First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                        autocomplete="name" class="form-control" id="profile-edit-first-name"
                        placeholder="Enter First Name">
                    <x-input-error-field :messages="$errors->get('first_name')" class="mt-2" />
                </div>
                <div class="mb-4">
                    <label class="form-label" for="profile-edit-last-name">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                        autocomplete="name" class="form-control" id="profile-edit-last-name"
                        placeholder="Enter Last Name">
                    <x-input-error-field :messages="$errors->get('last_name')" class="mt-2" />
                </div>
                <x-input-error-field :messages="$errors->get('last_name')" class="mt-2" />
                {{-- <div class="mb-4">
                    <label class="form-label" for="profile-edit-name">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        autocomplete="name" class="form-control" id="profile-edit-name" placeholder="Enter Name">
                </div>
                <x-input-error-field :messages="$errors->get('name')" class="mt-2" /> --}}
                <div class="mb-4">
                    <label class="form-label" for="profile-edit-email">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control"
                        id="profile-edit-email" placeholder="Enter your email.." required autocomplete="email">
                    <x-input-error-field :messages="$errors->get('email')" class="mt-2" />
                </div>
                @if ($user->hasRole('admin'))
                    <div class="mb-4">
                        <label class="form-label" for="profile-edit-employee-id">Employee ID</label>
                        <input type="text" name="employee_id" value="{{ old('employee_id', $user->employee_id) }}"
                            autocomplete="name" class="form-control" id="profile-edit-employee-id"
                            placeholder="Enter Employee ID">
                        <x-input-error-field :messages="$errors->get('employee_id')" class="mt-2" />
                    </div>
                @endif
                <div class="mb-4">
                    <label class="form-label" for="profile-edit-phone-number">Phone number</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                        autocomplete="name" class="form-control" id="profile-edit-phone-number"
                        placeholder="Enter Phone number">
                    <x-input-error-field :messages="$errors->get('phone_number')" class="mt-2" />
                </div>
                @if ($user->hasRole('admin'))
                    <div class="mb-4">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="pending" {{ $user->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $user->status == 'approved' ? 'selected' : '' }}>Approved
                            </option>
                        </select>
                    </div>
                @endif
                <div class="mb-4">
                    {{-- <label class="form-label">Your Avatar</label>
                    <div class="push">
                        <img class="img-avatar" src="{{ asset('assets/media/avatars/avatar13.jpg') }}" alt="">
                        </div>
                        <label class="form-label" for="dm-profile-edit-avatar">Choose a new avatar</label>
                        <input class="form-control" type="file" id="dm-profile-edit-avatar">
                    </div> --}}
                    <button type="submit" class="btn btn-alt-primary">
                        <i class="fa fa-check-circle opacity-50 me-1"></i> Update Profile
                    </button>
            </form>
        </div>
    </div>

</section>
