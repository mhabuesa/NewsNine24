<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Traits\ImageSaveTrait;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    use ImageSaveTrait;

    /**
     * Display the user's profile form.
     */
    public function index()
    {
        // dd(url()->current());
        return view('backend.profile.index');
    }
    /**
     * Show the form for editing the user's profile.
     */
    public function edit(Request $request): View
    {
        return view('backend.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $request->validate([
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if (!empty($user->image)) {
                $this->deleteImage(public_path($user->image));
            }

            $image_name = $this->saveImage('profile', $request->file('image'), 400, 400);
        }

        // Update user details
        User::where('id', $user->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'image' => $image_name ?? null,
        ]);

        return redirect()->route('profile.index')->with('success', 'Profile Updated Successfully');
    }

    /**
     * Update the user's profile password.
     */
    public function profile_password(Request $request)
    {
        // Validate the request
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        // Get the user (profile)
        $user = Auth::user();

        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('profile.index')->with('error', 'Current Password does not match');
        }

        // Update the password
        $user->password = Hash::make($request->password);
        $user->save();

        // Redirect with success message
        return redirect()->route('profile.index')->with('success', 'Password Updated Successfully');
    }
}
