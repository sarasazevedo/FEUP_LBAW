<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ManageUsersController extends Controller
{
    public function show($id)
    {
        // Authorize the user for the show action
        $this->authorize('show', Auth::user());
    
        // Redirect to the manage users page
        return redirect()->route('pages.manage_users');
    }
    
    public function index()
    {
        // Authorize the user for the index action
        $this->authorize('index', Auth::user());
    
        // Retrieve all users
        $users = User::all();
    
        // Return the manage users view with the users data
        return view('pages.manage_users', compact('users'));
    }
    
    public function blockUser($id)
    {
        // Authorize the user for the block action
        $this->authorize('block', Auth::user());
    
        // Find the user by ID or fail if not found
        $user = User::findOrFail($id);
    
        // Toggle the blocked status of the user
        $user->is_blocked = !$user->is_blocked;
        $user->save();
    
        // Return a JSON response indicating success
        return response()->json(['success' => true, 'userBlocked' => true]);
    }
    
    public function deleteUser($id)
    {
        // Authorize the user for the delete action
        $this->authorize('delete', Auth::user());
    
        // Find the user by ID or fail if not found
        $user = User::findOrFail($id);
        
        // Change user details to anonymous values
        $user->username = 'anonymous_' . $user->username;
        $user->email = 'anonymous_' . $user->email;
        $user->description = 'This user has been anonymized.';
        $user->password = bcrypt('anonymous_' . $user->username);
        $user->is_deleted = true;
    
        // Save the changes
        $user->save();
    
        // Return a JSON response indicating success
        return response()->json(['success' => true, 'userDeleted' => true]);
    }
}