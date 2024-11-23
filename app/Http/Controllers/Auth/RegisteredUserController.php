<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use App\Models\Participant;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;

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
        $request->validate([
            'first_name' => ['required', 'string', 'max:25'],
            'last_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'gender' => ['required', 'in:Laki - Laki,Perempuan'],
            'phone' => ['nullable', 'numeric'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = new User([
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $user->assignRole('participant');
        $user->save();
        event(new Registered($user));

        Participant::create([
            'name' => Str::title($request->first_name) . ' ' . Str::title($request->last_name),
            'gender' => $request->gender,
            'phone' => $request->phone,
            'photo' => $fileFilename ?? NULL,
            'user_id' => $user->id,
        ]);

        Auth::login($user);

        return redirect(route('dashboard.index', absolute: false));
    }
}
