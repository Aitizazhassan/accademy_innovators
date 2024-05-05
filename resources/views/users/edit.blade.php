<x-app-layout>
    @section('title', 'Edit User')
    <!-- Page Content -->
    <div class="content content-full content-boxed">
        <!-- Hero -->
        <div class="rounded border overflow-hidden push">
            <div class="bg-image pt-9"
                style="background-image: url('{{ asset('assets/media/photos/photo19@2x.jpg') }}');"></div>
            <div class="px-4 py-3 bg-body-extra-light d-flex flex-column flex-md-row align-items-center">
                <a class="d-block img-link mt-n5" href="be_pages_generic_profile_v2.html">
                    <img class="img-avatar img-avatar128 img-avatar-thumb"
                        src="{{ asset('assets/media/avatars/avatar13.jpg') }}" alt="">
                </a>
                <div class="ms-3 flex-grow-1 text-center text-md-start my-3 my-md-0">
                    <h1 class="fs-4 fw-bold mb-1">{{ $user->name }}</h1>
                    <h2 class="fs-sm fw-medium text-muted mb-0">
                        {{ $user->email }}
                    </h2>
                </div>
            </div>
        </div>
        <!-- END Hero -->

        <!-- Edit Account -->
        <div class="block block-bordered block-rounded">
            <ul class="nav nav-tabs nav-tabs-alt" role="tablist">
                <li class="nav-item">
                    <button class="nav-link space-x-1 {{ $errors->has('password') ? '' : 'active' }}"
                        id="account-profile-tab" data-bs-toggle="tab" data-bs-target="#account-profile" role="tab"
                        aria-controls="account-profile"
                        aria-selected="{{ !$errors->has('password') ? 'true' : 'false' }}">
                        <i class="fa fa-user-circle d-sm-none"></i>
                        <span class="d-none d-sm-inline">Profile</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link space-x-1 {{ $errors->has('password') ? 'active' : '' }}"
                        id="account-password-tab" data-bs-toggle="tab" data-bs-target="#account-password" role="tab"
                        aria-controls="account-password"
                        aria-selected="{{ $errors->has('password') ? 'true' : 'false' }}">
                        <i class="fa fa-asterisk d-sm-none"></i>
                        <span class="d-none d-sm-inline">Password</span>
                    </button>
                </li>
            </ul>
            <div class="block-content tab-content">
                <div class="tab-pane {{ $errors->has('password') ? '' : 'active' }}" id="account-profile"
                    role="tabpanel" aria-labelledby="account-profile-tab" tabindex="0">
                    @include('users.partials.update-profile-information-form')
                </div>
                <div class="tab-pane {{ $errors->has('password') ? 'active' : '' }}" id="account-password"
                    role="tabpanel" aria-labelledby="account-password-tab" tabindex="0">
                    @include('users.partials.update-password-form')
                </div>
            </div>
        </div>

        <!-- END Edit Account -->
    </div>
    <!-- END Page Content -->
</x-app-layout>
