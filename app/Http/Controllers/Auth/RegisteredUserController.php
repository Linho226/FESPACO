<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $canCreateAdmin = User::where('is_admin', true)->doesntExist();

        return view('auth.register', compact('canCreateAdmin'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $canCreateAdmin = User::where('is_admin', true)->doesntExist();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'create_admin' => ['nullable', 'boolean'],
        ]);

        $isAdmin = $canCreateAdmin && $request->boolean('create_admin');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $isAdmin,
        ]);

        event(new Registered($user));

        $status = $isAdmin
            ? 'Compte administrateur cree. Connectez-vous pour acceder au tableau de bord.'
            : 'Compte cree. Connectez-vous pour continuer.';

        return redirect()->route('login')->with('status', $status);
    }
}