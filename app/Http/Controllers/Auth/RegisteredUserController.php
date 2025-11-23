<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

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
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'is_family' => ['required', 'boolean'],
            'family_name' => ['nullable', 'required_if:is_family,1', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            'city' => ['nullable', 'string', 'max:100'],
            'privacy_level' => ['nullable', 'in:anonymous,first_name,full_name'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'role' => ['required', 'in:seeker,helper,both'],
        ]);
          if ($validated['is_family'] && !isset($validated['privacy_level'])) {
            $validated['privacy_level'] = 'first_name';
        }
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_family' => $validated['is_family'],
            'family_name' => $validated['family_name'] ?? null,
            'contact_phone' => $validated['contact_phone'] ?? null,
            'city' => $validated['city'] ?? null,
            'privacy_level' => $validated['privacy_level'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
