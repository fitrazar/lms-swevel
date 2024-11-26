<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Instructor;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $role = $user->roles->pluck('name')[0];
        $validatedData = $request->validated();

        if ($role == 'instructor') {
            if ($request->hasFile('photo')) {
                $path = 'instructor/photo/' . Auth::user()->name;
                if ($user->photo) {
                    Storage::delete($path . '/' . $user->photo);
                }
                $validatedData['photo'] = time() . '.' . $request->file('photo')->getClientOriginalExtension();
                $request->file('photo')->storeAs($path, $validatedData['photo']);
            }

            Instructor::where('user_id', $user->id)->update($validatedData);

        } else {
            if ($request->hasFile('photo')) {
                $path = 'participant/photo/' . Auth::user()->name;
                if ($user->photo) {
                    Storage::delete($path . '/' . $user->photo);
                }
                $validatedData['photo'] = time() . '.' . $request->file('photo')->getClientOriginalExtension();
                $request->file('photo')->storeAs($path, $validatedData['photo']);
            }

            Participant::where('user_id', $user->id)->update($validatedData);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
