<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailModel;
use App\Models\User;

class MailController extends Controller
{
    public function send(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
        ]);
    
        // Find the user by email or return a JSON response if not found
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Email not registered.']);
        }
    
        // Prepare the mail data
        $mailData = [
            'email' => $request->email,
            'reset_link' => url('/password-reset?email=' . urlencode($request->email)),
        ];
    
        try {
            // Send the email using the Mail facade
            Mail::to($request->email)->send(new MailModel($mailData));
            return response()->json(['success' => true, 'message' => 'The email has been sent.']);
        } catch (\Swift_TransportException $e) {
            // Return a JSON response indicating an SMTP connection error occurred
            return response()->json(['success' => false, 'message' => 'SMTP connection error occurred.']);
        } catch (\Exception $e) {
            // Return a JSON response indicating a general error occurred
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
}