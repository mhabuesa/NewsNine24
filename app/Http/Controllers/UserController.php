<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ImageSaveTrait;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    use ImageSaveTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('backend.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        // Handle image upload
        $image_name = null;
        if($request->hasFile('image')){
            $image_name = $this->saveImage('profile', $request->file('image'), 400, 400);
        }
        // Create new user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'image' => $image_name,
        ]);

        // Redirect with success message
        return redirect()->route('users.index')->with('success', 'User created successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('backend.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        $user = User::findOrFail($id);

        // Handle image upload
        if($request->hasFile('image')){
            // Delete old image if exists
            if($user->image){
                $this->deleteImage('profile', $user->image);
            }
            $image_name = $this->saveImage('profile', $request->file('image'), 400, 400);
        }

        // Update user details
        User::where('id', $user->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'image' => $image_name ?? null,
        ]);
        $user->save();

        // Redirect with success message
        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        try {
            // Delete user
            $user->delete();
        } catch (\Exception $e) {
            Log::error($e);
            return error($e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'User Deleted Successfully'], 200);
    }
}
