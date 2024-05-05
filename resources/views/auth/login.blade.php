<x-guest-layout>
    <!-- Page Content -->
    <div class="bg-image" style="background-image: url('assets/media/photos/photo19@2x.jpg');">
        <div class="row g-0 justify-content-center bg-primary-dark-op">
            <div class="hero-static col-sm-8 col-md-6 col-xl-4 d-flex align-items-center p-2 px-sm-0">
            <!-- Sign In Block -->
            <div class="block block-transparent block-rounded w-100 mb-0 overflow-hidden">
                <div class="block-content block-content-full px-lg-5 px-xl-6 py-4 py-md-5 py-lg-6 bg-body-extra-light">
                <!-- Header -->
                <div class="mb-2 text-center">
                    <a class="link-fx fw-bold fs-1" href="">
                    {{-- <span class="text-dark">Acadmey</span><span class="text-primary">Inn</span> --}}
                    <img class="logo-img-front img-fluid" src="{{ asset('images/logo/logo.jpg') }}" alt="">
                    </a>
                    <p class="text-uppercase fw-bold fs-sm text-muted">Sign In</p>
                </div>
                <!-- END Header -->

                <!-- Sign In Form -->
                {{-- <x-auth-session-status class="mb-4" :status="session('status')" /> --}}
                <!-- Session Status -->
                <x-alert-success class="mb-4" :status="session('success')" />
                <x-alert-error class="mb-4" :status="session('error')" />
                <form class="" action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                    <div class="input-group input-group-lg">
                        <input type="email" name="email" :value="old('email')" class="form-control" id="email" placeholder="Email">
                        <span class="input-group-text">
                        <i class="fa fa-user-circle"></i>
                        </span>
                    </div>
                    <x-input-error-field :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                    <div class="input-group input-group-lg">
                        <input type="password" :value="old('password')" class="form-control" id="password" name="password" placeholder="Password">
                        <span class="input-group-text">
                        <i class="fa fa-asterisk"></i>
                        </span>
                    </div>
                    <x-input-error-field :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <div class="d-sm-flex justify-content-sm-between align-items-sm-center text-center text-sm-start mb-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember_me" name="remember" checked>
                        <label class="form-check-label" for="login-remember-me">Remember Me</label>
                    </div>
                    <div class="fw-semibold fs-sm py-1">
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Forgot Password?</a>
                        @endif
                    </div>
                    </div>
                    <div class="text-center mb-4">
                    <button type="submit" class="btn btn-hero btn-primary">
                        <i class="fa fa-fw fa-sign-in-alt opacity-50 me-1"></i> Sign In
                    </button>
                    </div>
                    <p class="mt-3 mb-0 d-lg-flex justify-content-center">
                        {{-- <a class="btn btn-sm btn-alt-secondary d-block d-lg-inline-block mb-1"
                            href="{{route('register')}}">
                            <i class="fa fa-plus opacity-50 me-1"></i> New Account
                        </a> --}}
                    </p>
                </form>
                <!-- END Sign In Form -->
                </div>
                <div class="block-content bg-body">
                <div class="d-flex justify-content-center text-center push">
                    <a class="item item-circle item-tiny me-1 bg-default" data-toggle="theme" data-theme="default" href="#"></a>
                    <a class="item item-circle item-tiny me-1 bg-xwork" data-toggle="theme" data-theme="assets/css/themes/xwork.min.css" href="#"></a>
                    <a class="item item-circle item-tiny me-1 bg-xmodern" data-toggle="theme" data-theme="assets/css/themes/xmodern.min.css" href="#"></a>
                    <a class="item item-circle item-tiny me-1 bg-xeco" data-toggle="theme" data-theme="assets/css/themes/xeco.min.css" href="#"></a>
                    <a class="item item-circle item-tiny me-1 bg-xsmooth" data-toggle="theme" data-theme="assets/css/themes/xsmooth.min.css" href="#"></a>
                    <a class="item item-circle item-tiny me-1 bg-xinspire" data-toggle="theme" data-theme="assets/css/themes/xinspire.min.css" href="#"></a>
                    <a class="item item-circle item-tiny me-1 bg-xdream" data-toggle="theme" data-theme="assets/css/themes/xdream.min.css" href="#"></a>
                    <a class="item item-circle item-tiny me-1 bg-xpro" data-toggle="theme" data-theme="assets/css/themes/xpro.min.css" href="#"></a>
                    <a class="item item-circle item-tiny bg-xplay" data-toggle="theme" data-theme="assets/css/themes/xplay.min.css" href="#"></a>
                </div>
                </div>
            </div>
            <!-- END Sign In Block -->
            </div>
        </div>
        </div>
        <!-- END Page Content -->
</x-guest-layout>
