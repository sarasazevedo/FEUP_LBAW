<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Display a login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request)
    {
        // Forget any previously stored blocked user ID in the session
        $request->session()->forget('blocked_user_id');
    
        // Validate the request data
        $credentials = $request->validate([
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:3'],
        ]);
    
        // Determine if the login input is an email or username
        $loginType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    
        // Prepare the credentials array for authentication
        $credentials = [
            $loginType => $credentials['login'],
            'password' => $credentials['password'],
        ];
    
    
        // Retrieve the user by the login type (email or username)
        $user = User::where($loginType, $credentials[$loginType])->first();
    
        // Check if the user is blocked
        if ($user && $user->is_blocked) {
            // Store the blocked user ID in the session
            $request->session()->put('blocked_user_id', $user->id);
            // Return back with an error message indicating the account is blocked
            return back()->withErrors([
                'login' => 'Your account is blocked',
                'blocked' => true,
            ])->onlyInput('login');
        }
    
        // Attempt to authenticate the user with the provided credentials
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Regenerate the session to prevent session fixation attacks
            $request->session()->regenerate();
            // Redirect to the intended page after successful login
            return redirect()->intended('/');
        }
    
        // Return back with an error message if authentication fails
        return back()->withErrors([
            'login' => 'The provided credentials are not correct.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
{
    // Log out the currently authenticated user
    Auth::logout();

    // Invalidate the current session
    $request->session()->invalidate();

    // Regenerate the session token to prevent CSRF attacks
    $request->session()->regenerateToken();

    // Forget any previously stored blocked user ID in the session
    $request->session()->forget('blocked_user_id');

    // Redirect the user to the home page after logout
    return redirect('/');
}

}