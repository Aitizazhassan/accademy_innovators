<x-guest-layout>

    <!-- Page Content -->
    <div class="bg-image" style="background-image: url('assets/media/photos/photo14@2x.jpg');">
        <div class="row g-0 justify-content-center bg-black-75">
            <div class="hero-static col-sm-8 col-md-6 col-xl-4 d-flex align-items-center p-2 px-sm-0">
                <!-- Sign Up Block -->
                <div class="block block-transparent block-rounded w-100 mb-0 overflow-hidden">
                    <div
                        class="block-content block-content-full px-lg-5 px-xl-6 py-4 py-md-5 py-lg-6 bg-body-extra-light">
                        <!-- Header -->
                        <div class="mb-2 text-center">
                            <a class="link-fx fw-bold fs-1" href="#">
                                <span class="text-dark">Capasso</span><span class="text-primary">Ent</span>
                            </a>
                            <p class="text-uppercase fw-bold fs-sm text-muted">Create New Account</p>
                        </div>
                        <!-- END Header -->

                        <form class="" action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <input type="text" name="first_name" value="{{ old('first_name') }}" autofocus
                                        class="form-control" id="signup-first-name" placeholder="First Name">
                                    <span class="input-group-text">
                                        <i class="fa fa-user-circle"></i>
                                    </span>
                                </div>
                                <x-input-error-field :messages="$errors->get('first_name')" class="mt-2" />
                            </div>
                            <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <input type="text" name="last_name" value="{{ old('last_name') }}" autofocus
                                        class="form-control" id="signup-last-name" placeholder="Last Name">
                                    <span class="input-group-text">
                                        <i class="fa fa-user-circle"></i>
                                    </span>
                                </div>
                                <x-input-error-field :messages="$errors->get('last_name')" class="mt-2" />
                            </div>
                            {{-- <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <input type="text" name="name" :value="old('name')" autofocus
                                        class="form-control" id="signup-username" placeholder="Username">
                                    <span class="input-group-text">
                                        <i class="fa fa-user-circle"></i>
                                    </span>
                                </div>
                                <x-input-error-field :messages="$errors->get('name')" class="mt-2" />
                            </div> --}}
                            <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                                        id="signup-email" placeholder="Email">
                                    <span class="input-group-text">
                                        <i class="fa fa-envelope-open"></i>
                                    </span>
                                </div>
                                <x-input-error-field :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <input type="text" name="employee_id" value="{{ old('employee_id') }}" autofocus
                                        class="form-control" id="signup-employee-id" placeholder="Employee ID">
                                    <span class="input-group-text">
                                        <i class="fa fa-id-badge"></i>
                                    </span>
                                </div>
                                <x-input-error-field :messages="$errors->get('employee_id')" class="mt-2" />
                            </div>
                            <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <input type="text" name="phone_number" value="{{ old('phone_number') }}" autofocus
                                        class="form-control" id="signup-phone-number" placeholder="Phone Number">
                                    <span class="input-group-text">
                                        <i class="fa fa-phone"></i>
                                    </span>
                                </div>
                                <x-input-error-field :messages="$errors->get('phone_number')" class="mt-2" />
                            </div>
                            <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <input type="password" class="form-control" id="signup-password" name="password"
                                        placeholder="Password">
                                    <span class="input-group-text">
                                        <i class="fa fa-asterisk"></i>
                                    </span>
                                </div>
                                <x-input-error-field :messages="$errors->get('password')" class="mt-2" />
                            </div>
                            <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <input type="password" class="form-control" id="signup-password-confirm"
                                        name="password_confirmation" placeholder="Password Confirm">
                                    <span class="input-group-text">
                                        <i class="fa fa-asterisk"></i>
                                    </span>
                                </div>
                                <x-input-error-field :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                            <div class="text-center mb-4">
                                <button type="submit" class="btn btn-hero btn-primary">
                                    <i class="fa fa-fw fa-plus opacity-50 me-1"></i> Sign Up
                                </button>
                                <p class="mt-3 mb-0 d-lg-flex justify-content-center">
                                <p class="text-uppercase fw-bold fs-sm text-muted">Already registered?</p>
                                <a class="btn btn-sm btn-alt-secondary d-block d-lg-inline-block mb-1"
                                    href="{{ route('login') }}">
                                    <i class="fa fa-sign-in-alt opacity-50 me-1"></i> Sign In
                                </a>
                                </p>
                            </div>
                        </form>
                        <!-- END Sign Up Form -->
                    </div>
                </div>
            </div>
            <!-- END Sign Up Block -->
        </div>
    </div>
    <!-- END Page Content -->
</x-guest-layout>
