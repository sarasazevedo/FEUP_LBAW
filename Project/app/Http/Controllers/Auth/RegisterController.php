<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Client;
use App\Models\Restaurant;
use App\Http\Controllers\PostController;

class RegisterController extends Controller
{
    /**
     * Show the sign up form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:250',
            'username' => 'required|string|max:250|unique:user',
            'email' => 'required|email|max:250|unique:user',
            'password' => 'required|min:3|confirmed',
            'description' => 'nullable|string|max:250',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'role' => 'required|in:client,restaurant',
            'type_id' => 'required_if:role,restaurant|exists:restaurant_type,id',
            'capacity' => 'required_if:role,restaurant|integer|min:1',
        ]);
    
        // Create a new user instance
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'description' => $request->description,
            'image' => 'images/profile/default.png',
        ]);
    
        // Create a client or restaurant record based on the role
        if ($request->role === 'client') {
            Client::create(['id' => $user->id]);
        } elseif ($request->role === 'restaurant') {
            Restaurant::create([
                'id' => $user->id,
                'type_id' => $request->input('type_id'),
                'capacity' => $request->input('capacity'),
            ]);
        }
    
        // Authenticate the user
        Auth::login($user);
    
        // Regenerate session
        $request->session()->regenerate();
    
        // Redirect to the profile page with a success message
        return redirect()->route('profile.show', ['id' => $user->id])
            ->withSuccess('You have successfully registered.');
    }
}
