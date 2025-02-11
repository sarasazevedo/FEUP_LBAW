<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class PasswordController extends Controller
{
    public function showResetForm(Request $request)
    {
        // Get the email from the query parameters
        $email = $request->query('email');
    
        // Check if the email is present
        if (!$email) {
            // Redirect to the login page with an error message if the email is not present
            return redirect('/login')->withErrors(['email' => 'Invalid password reset link.']);
        }
    
        // Return the reset password view with the email data
        return view('auth.reset_password', compact('email'));
    }
    
    public function reset(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:3',
        ]);
    
        // Find the user by email
        $user = User::where('email', $request->email)->first();
    
        // Check if the user exists
        if ($user) {
            // Update the user's password
            $user->password = bcrypt($request->password);
            $user->save();
    
            // Return a JSON response indicating success
            return response()->json(['success' => true, 'message' => 'Password updated successfully.']);
        }
    
        // Return a JSON response indicating the user was not found
        return response()->json(['success' => false, 'message' => 'User not found.']);
    }
}