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
                    <a class="link-fx fw-bold fs-1" href="#">
                    {{-- <span class="text-dark">Capasso</span><span class="text-primary">Ent</span> --}}
                    <img class="logo-img-front img-fluid" src="{{ asset('images/logo/logo.jpg') }}" alt="">
                    </a>
                    <p class="text-uppercase fw-bold fs-sm text-muted">Password Reminder</p>
                </div>
                <!-- END Header -->

                <!-- Reminder Form -->
                <!-- Session Status Success -->
                <x-alert-success class="mb-4" :status="session('status')" />
                <form class="" action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                    <div class="input-group input-group-lg">
                        <input type="email" name="email" :value="old('email')" required autofocus class="form-control" id="email" placeholder="Email">
                        <span class="input-group-text">
                        <i class="fa fa-user-circle"></i>
                        </span>
                    </div>
                    <x-input-error-field :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="text-center mb-4">
                    <button type="submit" class="btn btn-hero btn-primary">
                        <i class="fa fa-fw fa-reply opacity-50 me-1"></i> Reset Password
                    </button>
                    </div>
                    <p class="mt-3 mb-0 d-lg-flex justify-content-lg-between">
                        <a class="btn btn-sm btn-alt-secondary d-block d-lg-inline-block mb-1"
                            href="{{route('login')}}">
                            <i class="fa fa-sign-in-alt opacity-50 me-1"></i> Sign In
                        </a>
                        {{-- <a class="btn btn-sm btn-alt-secondary d-block d-lg-inline-block mb-1"
                            href="{{route('register')}}">
                            <i class="fa fa-plus opacity-50 me-1"></i> New Account
                        </a> --}}
                    </p>
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
