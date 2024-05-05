<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\RegisterUserRequest;
use App\Jobs\NotifyAdminOnUserRegistration;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterUserRequest $request): RedirectResponse
    {
        $validatedData = $request->validated(); // Retrieve validated data from the request

        // Inject 'name' attribute into the request data
        $validatedData['name'] = $validatedData['first_name'] . ' ' . $validatedData['last_name'];

        $user = User::create($validatedData);

        event(new Registered($user));

        // Send email notification to admin
        NotifyAdminOnUserRegistration::dispatch($user)->onQueue('default');

        if ($user->status === 'approved') {
            Auth::login($user);
            return redirect(RouteServiceProvider::HOME);
        } else {
            auth()->logout();
            return redirect()->route('login')->with([
                'success' => 'Your account is registered as pending approval. Admin is notified by email! Please wait for admin approval and then login.',
            ]);
        }
    }
}
