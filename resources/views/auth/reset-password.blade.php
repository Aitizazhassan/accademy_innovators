<x-guest-layout>
    <!-- Page Content -->
    <div class="bg-image" style="background-image: url('assets/media/photos/photo16@2x.jpg');">
        <div class="row g-0 justify-content-center bg-black-75">
            <div class="hero-static col-sm-8 col-md-6 col-xl-4 d-flex align-items-center p-2 px-sm-0">
            <!-- Reminder Block -->
            <div class="block block-transparent block-rounded w-100 mb-0 overflow-hidden">
                <div class="block-content block-content-full px-lg-5 px-xl-6 py-4 py-md-5 py-lg-6 bg-body-extra-light">
                <!-- Header -->
                <div class="mb-2 text-center">
                    <a class="link-fx fw-bold fs-1" href="">
                    {{-- <span class="text-dark">Capasso</span><span class="text-primary">Ent</span> --}}
                    <img class="logo-img-front img-fluid" src="{{ asset('images/logo/logo.jpg') }}" alt="">
                    </a>
                    <p class="text-uppercase fw-bold fs-sm text-muted">Password Reset</p>
                </div>
                <!-- END Header -->

                <!-- Reminder Form -->
                <!-- Session Status Success -->
                <x-alert-success class="mb-4" :status="session('status')" />
                <form method="POST" action="{{ route('password.store') }}">
                    @csrf
                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    <div class="mb-4">
                    <div class="input-group input-group-lg">
                        <input type="email" name="email" value="{{old('email', $request->email)}}" class="form-control" required autofocus autocomplete="username">
                        <span class="input-group-text">
                        <i class="fa fa-user-circle"></i>
                        </span>
                    </div>
                    <x-input-error-field :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <div class="input-group input-group-lg">
                            <input type="password" value="{{old('password')}}" class="form-control" id="password" name="password" placeholder="Password"  required autocomplete="new-password">
                            <span class="input-group-text">
                            <i class="fa fa-asterisk"></i>
                            </span>
                        </div>
                        <x-input-error-field :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <div class="input-group input-group-lg">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                            <span class="input-group-text">
                            <i class="fa fa-asterisk"></i>
                            </span>
                        </div>
                        <x-input-error-field :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                    <div class="text-center mb-4">
                    <button type="submit" class="btn btn-hero btn-primary">
                        <i class="fa fa-fw fa-reply opacity-50 me-1"></i> Reset Password
                    </button>
                    </div>
                </form>
                <!-- END Reminder Form -->
                </div>
            </div>
            <!-- END Reminder Block -->
            </div>
        </div>
        </div>
        <!-- END Page Content -->
</x-guest-layout>
