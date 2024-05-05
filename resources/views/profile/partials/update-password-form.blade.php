<section>
    {{-- <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
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
        <div class="offset-xl-1 col-xl-4 order-xl-1">
        <p class="bg-body-light p-4 rounded-3 text-muted fs-sm">
            Changing your sign in password is an easy way to keep your account secure.
        </p>
        </div>
        <div class="col-xl-6 order-xl-0">
        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            @method('put')
            <div class="mb-4">
            <label class="form-label" for="update_password_current_password">Current Password</label>
            <input type="password" class="form-control" id="update_password_current_password" name="current_password">
            <x-input-error-field :messages="$errors->get('current_password')" class="mt-2" />
            </div>
            <div class="row mb-4">
            <div class="col-12">
                <label class="form-label" for="update_password_password">New Password</label>
                <input type="password" class="form-control" id="update_password_password" name="password">
                <x-input-error-field :messages="$errors->get('password')" class="mt-2" />
            </div>
            </div>
            <div class="row mb-4">
            <div class="col-12">
                <label class="form-label" for="update_password_password_confirmation">Confirm New Password</label>
                <input type="password" class="form-control" id="update_password_password_confirmation" name="password_confirmation">
                <x-input-error-field :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
            </div>
            <button type="submit" class="btn btn-alt-primary">
            <i class="fa fa-check-circle opacity-50 me-1"></i> Update Password
            </button>
        </form>
        </div>
    </div>
</section>
